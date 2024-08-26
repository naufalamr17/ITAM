<?php

namespace App\Http\Controllers;

use App\Models\Bast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BastController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = "
            SELECT 
                basts.id,
                basts.no,
                basts.date,
                basts.pic,
                basts.nik_user,
                employees.nama AS nama,
                employees.job_position AS job_position,
                basts.jenis_barang,
                basts.merk,
                basts.type,
                basts.serial_number,
                basts.spesifikasi
            FROM itam.basts AS basts
            JOIN approval.employees AS employees 
                ON basts.nik_user = employees.nik
        ";

            // Execute the raw SQL query
            $basts = DB::select($query);

            return DataTables::of($basts)
                ->addColumn('action', function ($bast) {
                    return '<a href="' . route('bast', $bast->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->make(true);
        }

        $employees = DB::connection('approval')->select('SELECT * FROM employees');
        // dd($employees);
        return view('pages.bast.index', compact('employees')); // You'll need to create this view
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'date' => 'required|date',
            'pic' => 'required|string|max:255',
            'nik_user' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
        ]);

        // Get the PIC from the approval database
        $pic = DB::connection('approval')->selectOne('SELECT * FROM employees WHERE nama = ?', [$request->pic]);

        if (!$pic) {
            return redirect()->back()->withErrors(['pic' => 'PIC not found in the approval database.']);
        }

        // Generate the `no` field dynamically
        // Extract year and month from the date input
        $year = Carbon::parse($request->date)->format('Y');
        $month = Carbon::parse($request->date)->format('m');

        // Convert month to Roman numerals
        $romanMonths = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];

        $monthRoman = $romanMonths[$month];

        // Retrieve the latest BAST record for the current year to generate the 3-digit number
        $latestBast = Bast::whereYear('date', $year)->orderBy('created_at', 'desc')->first();
        $nextNumber = 1;

        if ($latestBast) {
            $latestNo = explode('/', $latestBast->no);
            $latestNoNumber = intval($latestNo[0]);
            $nextNumber = $latestNoNumber + 1;
        }

        // Format the 3-digit number with leading zeros
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Create the `no` field
        $no = "{$formattedNumber}/BAST/MLP/{$monthRoman}/{$year}";

        // dd($no);

        // Store the data
        Bast::create(array_merge($request->all(), ['no' => $no]));

        return redirect()->back()->with('success', 'BAST data saved successfully!');
    }
}
