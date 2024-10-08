<?php

namespace App\Http\Controllers;

use App\Models\network;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculate downtime for Telkom
        $telkomDowntime = Network::where('provider', 'Telkom')
            ->whereMonth('start_time', $currentMonth)
            ->whereYear('start_time', $currentYear)
            ->get()
            ->map(function ($item) {
                if ($item->end_time) {
                    return Carbon::parse($item->end_time)->diffInMinutes(Carbon::parse($item->start_time));
                }
                return 0;
            })
            ->sum();

        // Convert Telkom downtime to hours and minutes
        $telkomHours = floor($telkomDowntime / 60);
        $telkomMinutes = $telkomDowntime % 60;
        $telkomDowntimeFormatted = sprintf('%dh %dm', $telkomHours, $telkomMinutes);

        // Calculate downtime for Bomm Akses
        $bommAksesDowntime = Network::where('provider', 'Bomm Akses')
            ->whereMonth('start_time', $currentMonth)
            ->whereYear('start_time', $currentYear)
            ->get()
            ->map(function ($item) {
                if ($item->end_time) {
                    return Carbon::parse($item->end_time)->diffInMinutes(Carbon::parse($item->start_time));
                }
                return 0;
            })
            ->sum();

        // Convert Bomm Akses downtime to hours and minutes
        $bommAksesHours = floor($bommAksesDowntime / 60);
        $bommAksesMinutes = $bommAksesDowntime % 60;
        $bommAksesDowntimeFormatted = sprintf('%dh %dm', $bommAksesHours, $bommAksesMinutes);

        return view('pages.network.index', [
            'telkomDowntimeFormatted' => $telkomDowntimeFormatted,
            'bommAksesDowntimeFormatted' => $bommAksesDowntimeFormatted,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'issue' => 'required|string',
            'details' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'resolution' => 'nullable|string',
        ]);

        // Ambil input
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        // Hitung durasi jika end_time terisi
        $duration = null;
        if ($endTime) {
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $interval = $start->diff($end);
        
            // Total jam dan menit
            $totalHours = ($interval->days * 24) + $interval->h;
            $totalMinutes = $totalHours * 60 + $interval->i;
        
            // Format durasi menjadi jam dan menit
            $duration = sprintf('%d h %d m', $totalHours, $interval->i);
        }

        // Simpan data ke database
        Network::create([
            'provider' => $request->input('provider'),
            'issue' => $request->input('issue'),
            'details' => $request->input('details'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'resolution' => $request->input('resolution'),
            'duration' => $duration, // Simpan durasi
        ]);

        // Redirect atau respon sesuai kebutuhan
        return redirect()->back()->with('success', 'Network problem added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(network $network)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data network berdasarkan ID
        $network = Network::findOrFail($id);
        // dd($network);

        return view('pages.network.edit', compact('network'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'provider'    => 'required|string|max:255',
            'start_time'  => 'required|date_format:Y-m-d\TH:i',
            'end_time'    => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'resolution'  => 'nullable|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the network record by ID
        $network = Network::find($id);

        if (!$network) {
            return redirect()->route('monitoring_network')->withErrors('Network record not found!');
        }

        // Calculate duration if end_time is provided
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $duration = null;

        if ($endTime) {
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $interval = $start->diff($end);
        
            // Total jam dan menit
            $totalHours = ($interval->days * 24) + $interval->h;
            $totalMinutes = $totalHours * 60 + $interval->i;
        
            // Format durasi menjadi jam dan menit
            $duration = sprintf('%d h %d m', $totalHours, $interval->i);
        }
        
        // Output atau simpan durasi
        // dd($duration);

        // Update the network record
        $network->update([
            'provider'    => $request->input('provider'),
            'start_time'  => $startTime,
            'end_time'    => $endTime,
            'resolution'  => $request->input('resolution'),
            'duration'    => $duration, // Save calculated duration
        ]);

        return redirect()->route('monitoring_network')->with('success', 'Network updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the network record by ID
        $network = Network::find($id);

        if (!$network) {
            return redirect()->route('monitoring_network')->withErrors('Network record not found!');
        }

        // Delete the record
        $network->delete();

        return redirect()->route('monitoring_network')->with('success', 'Network problem deleted successfully!');
    }

    public function network1(Request $request)
    {
        if ($request->ajax()) {
            $network1 = Network::where('provider', 'Telkom')->get();

            return DataTables::of($network1)
                ->addIndexColumn() // Jika Anda ingin menambahkan kolom nomor urut
                ->addColumn('action', function ($row) {
                    $editUrl = route('network.edit', $row->id); // Ganti dengan route yang sesuai
                    $deleteUrl = route('network.destroy', $row->id); // Ganti dengan route yang sesuai

                    return '<a href="' . $editUrl . '" class="btn btn-dark btn-sm mt-3">Edit</a> ' .
                        '<form action="' . $deleteUrl . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this item?\');">' .
                        '<input type="hidden" name="_token" value="' . csrf_token() . '">' .
                        '<input type="hidden" name="_method" value="DELETE">' .
                        '<button type="submit" class="btn btn-danger btn-sm mt-3">Delete</button>' .
                        '</form>';
                })
                ->rawColumns(['action']) // Agar DataTables tidak memproses HTML sebagai teks biasa
                ->make(true);
        }
    }

    public function network2(Request $request)
    {
        if ($request->ajax()) {
            $network2 = Network::where('provider', 'Bomm Akses')->get();

            return DataTables::of($network2)
                ->addIndexColumn() // Jika Anda ingin menambahkan kolom nomor urut
                ->addColumn('action', function ($row) {
                    $editUrl = route('network.edit', $row->id); // Ganti dengan route yang sesuai
                    $deleteUrl = route('network.destroy', $row->id); // Ganti dengan route yang sesuai

                    return '<a href="' . $editUrl . '" class="btn btn-dark btn-sm mt-3">Edit</a> ' .
                        '<form action="' . $deleteUrl . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this item?\');">' .
                        '<input type="hidden" name="_token" value="' . csrf_token() . '">' .
                        '<input type="hidden" name="_method" value="DELETE">' .
                        '<button type="submit" class="btn btn-danger btn-sm mt-3">Delete</button>' .
                        '</form>';
                })
                ->rawColumns(['action']) // Agar DataTables tidak memproses HTML sebagai teks biasa
                ->make(true);
        }
    }
}
