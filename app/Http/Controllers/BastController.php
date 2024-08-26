<?php

namespace App\Http\Controllers;

use App\Models\Bast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
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
                    $editUrl = route('bast.edit', $bast->id);
                    $deleteUrl = route('bast.destroy', $bast->id);
                    $printUrl = route('bast.print', $bast->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-secondary mt-3">Edit</a>
                    <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger mt-3">Delete</button>
                    </form>
                    <a href="' . $printUrl . '" class="btn btn-sm btn-info mt-3">Print</a>
                ';
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

    public function edit($id)
    {
        $bast = Bast::findOrFail($id);
        $employees = DB::connection('approval')->select('SELECT * FROM employees');
        // dd($employees);
        return view('pages.bast.edit', compact('bast', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'pic' => 'required|string|max:255',
            'nik_user' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
        ]);

        $bast = Bast::findOrFail($id);
        $bast->update($request->all());

        return redirect()->route('bast')->with('success', 'BAST updated successfully!');
    }

    public function destroy($id)
    {
        $bast = Bast::findOrFail($id);
        $bast->delete();

        return redirect()->route('bast')->with('success', 'BAST deleted successfully!');
    }

    public function print($id)
    {
        // Retrieve leave request data based on $id
        $bast = Bast::find($id);

        if (!$bast) {
            return redirect()->back()->with('error', 'BAST not found.');
        }

        // Retrieve employee data based on pic
        $employee1 = DB::connection('approval')->table('employees')
            ->where('nama', $bast->pic)
            ->first();

        // Retrieve second employee data based on nik_user
        $employee2 = DB::connection('approval')->table('employees')
            ->where('nik', $bast->nik_user)
            ->first();

        // Check if employee records are found
        $nama1 = $employee1 ? $employee1->nama : 'N/A'; // Use default value if not found
        $jabatan1 = $employee1 ? $employee1->job_position : 'N/A'; // Use default value if not found

        $nama2 = $employee2 ? $employee2->nama : 'N/A'; // Use default value if not found
        $jabatan2 = $employee2 ? $employee2->job_position : 'N/A'; // Use default value if not found

        // Load your template Word document
        $templatePath = public_path('BAST.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Format the date
        Carbon::setLocale('id');
        $formattedDate = Carbon::parse($bast->date)->translatedFormat('l, d F Y'); // Format date in Indonesian

        // Replace placeholders in the template with actual data
        $templateProcessor->setValue('No', $bast->no);
        $templateProcessor->setValue('date', $formattedDate);
        $templateProcessor->setValue('nama1', $nama1);
        $templateProcessor->setValue('nik1', $bast->nik_user); // Example of setting different values
        $templateProcessor->setValue('jabatan1', $jabatan1);
        $templateProcessor->setValue('nama2', $nama2);
        $templateProcessor->setValue('nik2', $bast->nik_user); // Set nik2 to nik_user if needed
        $templateProcessor->setValue('jabatan2', $jabatan2);
        $templateProcessor->setValue('barang', $bast->jenis_barang);
        $templateProcessor->setValue('merk', $bast->merk);
        $templateProcessor->setValue('tipe', $bast->type);
        $templateProcessor->setValue('serial', $bast->serial_number);
        $templateProcessor->setValue('warna', $bast->color);
        $templateProcessor->setValue('spec', $bast->spesifikasi);

        // Save the processed document
        $outputFileName = 'BAST_' . $bast->nik_user . '.docx';
        $outputFilePath = storage_path('app/public/' . $outputFileName);
        $templateProcessor->saveAs($outputFilePath);

        // Return a response with the generated Word document for download
        return response()->download($outputFilePath, $outputFileName)->deleteFileAfterSend(true);
    }
}
