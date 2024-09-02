<?php

namespace App\Http\Controllers;

use App\Models\network;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.network.index');
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
        // dd($request);

        $request->validate([
            'provider' => 'required|string',
            'issue' => 'required|string',
            'details' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'resolution' => 'nullable|string',
        ]);

        // Simpan data ke database
        network::create([
            'provider' => $request->input('provider'),
            'issue' => $request->input('issue'),
            'details' => $request->input('details'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'resolution' => $request->input('resolution'),
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

    public function network2(Request $request) {
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
