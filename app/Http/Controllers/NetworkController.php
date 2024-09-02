<?php

namespace App\Http\Controllers;

use App\Models\network;
use Illuminate\Http\Request;

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
        //
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
}
