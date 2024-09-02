<?php

namespace App\Http\Controllers;

use App\Models\network;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            $duration = $interval->format('%h h %i m'); // Format sesuai kebutuhan
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
    public function edit(network $network)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, network $network)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(network $network)
    {
        //
    }

    public function network1(Request $request)
    {
        if ($request->ajax()) {
            $network1 = Network::where('provider', 'Telkom')->get();

            return DataTables::of($network1)
                ->addIndexColumn() // Jika Anda ingin menambahkan kolom nomor urut
                ->addColumn('action', function ($row) {
                    $editUrl = route('network.edit', $row->id); // Ganti dengan route yang sesuai
                    return '<a href="' . $editUrl . '" class="btn btn-dark btn-sm mt-3">Edit</a>';
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
                    return '<a href="' . $editUrl . '" class="btn btn-dark btn-sm mt-3">Edit</a>';
                })
                ->rawColumns(['action']) // Agar DataTables tidak memproses HTML sebagai teks biasa
                ->make(true);
        }
    }
}
