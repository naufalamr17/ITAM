<?php

namespace App\Http\Controllers;

use App\Imports\YourDataImport;
use App\Models\dispose;
use App\Models\inventory;
use App\Models\repairstatus;
use App\Models\userhist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function index()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            // Ambil semua inventory yang statusnya bukan 'Dispose' dan urutkan berdasarkan acquisition_date desc
            $inventory = Inventory::where('status', '!=', 'Dispose')
                ->orderBy('acquisition_date', 'desc')
                ->get();
        } else {
            $inventory = Inventory::where('status', '!=', 'Dispose')
                ->where('location', Auth::user()->location)
                ->orderBy('acquisition_date', 'desc')
                ->get();
        }

        // Grouping inventory based on description
        $group1 = $inventory->whereIn('description', ['Wireless', 'Switch']);
        $group2 = $inventory->whereIn('description', ['Scanner', 'Printer']);
        $group3 = $inventory->whereIn('description', ['PC', 'Laptop']);

        return view('pages.asset.input', compact('group1', 'group2', 'group3'));
    }

    public function addinventory()
    {
        $user = Auth::user();
        $userLocation = $user->location;

        // dd($userLocation);

        return view('pages.asset.inputasset', compact('userLocation'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'asset_code' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'required|string',
            'merk' => 'nullable|string',
            'type' => 'nullable|string',
            'specification' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'os' => 'nullable|string',
            'installed_apps' => 'nullable|string',
            'acquisition_date' => 'required|date',
            'disposal_date' => 'nullable|date',
            'acquisition_value' => 'nullable|numeric',
            'hand_over_date' => 'nullable|date',
            'nik' => 'nullable|string',
            'user' => 'nullable|string',
            'job_position' => 'nullable|string',
            'dept' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // Simpan data aset ke dalam database
        $asset = Inventory::create($validatedData);

        // dd($asset);

        if ($request->store_to_database == 'true') {
            // Ambil ID aset yang baru saja disimpan
            $inv_id = $asset->id;

            // dd($inv_id);

            // Buat catatan di tabel userhist
            $hist = Userhist::create([
                'inv_id' => $inv_id,
                'hand_over_date' => $validatedData['hand_over_date'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'nik' => $validatedData['nik'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'user' => $validatedData['user'], // Sesuaikan dengan atribut yang sesuai
                'job_position' => $validatedData['job_position'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'dept' => $validatedData['dept'], // Sesuaikan dengan atribut yang sesuai
                'note' => $validatedData['note'], // Sesuaikan dengan atribut yang sesuai
            ]);
        }

        return redirect()->route('inventory')->with('success', 'Inventory created successfully.');
    }

    public function destroy($id)
    {
        $inventory = inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->back()->with('success', 'Inventory deleted successfully.');
    }

    public function edit($id)
    {
        $asset = inventory::findOrFail($id);
        $userhist = Userhist::where('inv_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        // dd($userhist);
        return view('pages.asset.editasset', compact('asset', 'userhist'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        // $request->validate([
        //     'old_asset_code' => 'nullable',
        //     'location' => 'required',
        //     'asset_category' => 'required',
        //     'asset_position_dept' => 'required',
        //     'asset_type' => 'required',
        //     'description' => 'required',
        //     'serial_number' => 'nullable',
        //     'acquisition_date' => 'required|date',
        //     'useful_life' => 'required|numeric',
        //     'acquisition_value' => 'numeric|default:0',
        //     'hand_over_date' => 'nullable|date',
        //     'user' => 'nullable',
        //     'dept' => 'nullable',
        //     'note' => 'nullable',
        // ]);

        $asset = inventory::findOrFail($id);
        $userhist = Userhist::where('inv_id', $id)
            ->where('hand_over_date', $asset->hand_over_date)
            ->first();

        // $asset_code = $asset->asset_code;

        // $parts = explode('-', $asset_code);

        // Mendefinisikan PIC Dept berdasarkan acquisition_value
        // if ($request->acquisition_value > 2500000) {
        //     $pic_dept = 'FAT & GA';
        //     $id1 = 'FG';
        // } else {
        //     $pic_dept = 'GA';
        //     $id1 = 'GA';
        // }

        // if ($request->location == 'Head Office') {
        //     $id2 = '01';
        // } else if ($request->location == 'Office Kendari') {
        //     $id2 = '02';
        // } else if ($request->location == 'Site Molore') {
        //     $id2 = '03';
        // }

        // Menentukan id3 berdasarkan asset_category
        // if ($request->asset_category == 'Kendaraan') {
        //     $id3 = '01';
        // } elseif ($request->asset_category == 'Mesin') {
        //     $id3 = '02';
        // } elseif ($request->asset_category == 'Alat Berat') {
        //     $id3 = '03';
        // } elseif ($request->asset_category == 'Alat Lab') {
        //     $id3 = '04';
        // } elseif ($request->asset_category == 'Alat Preparasi') {
        //     $id3 = '05';
        // } elseif ($request->asset_category == 'Peralatan') {
        //     $id3 = '06';
        // } else {
        //     $id3 = '07'; // Default code if no matching category is found
        // }

        // $ids = $id1 . ' ' . $id2 . '-' . $id3;
        // $idc = $parts[0] . '-' . $parts[1];

        // dd($ids, $idc);

        // if ($idc == $ids) {
        // dd('halo');
        $asset->update($request->all());
        // } else {
        //     $iddb = Inventory::where('asset_code', 'LIKE', "%$ids%")->get(['asset_code']);

        //     if ($iddb->isEmpty()) {
        //         $iteration = str_pad(1, 4, '0', STR_PAD_LEFT);
        // dd($iteration);
        // $id = $id1 . ' ' . $id2 . '-' . $id3 . '-' . $iteration;
        // $asset['asset_code'] = $id;
        // $asset['pic_dept'] = $pic_dept;
        // $asset->update($request->all());
        // dd($pic_dept);
        // } else {
        //     $existingIterations = [];

        // Loop melalui hasil query dan simpan urutan yang ada
        // foreach ($iddb as $inventory) {
        //     $asset_code = $inventory->asset_code;
        //     $parts = explode('-', $asset_code);
        //     $iteration = end($parts); // Ambil bagian terakhir dari hasil explode
        //     $existingIterations[] = $iteration;
        // }
        // for ($i = 1; $i <= 9999; $i++) {
        //     $iteration = str_pad($i, 4, '0', STR_PAD_LEFT);
        //     if (!in_array($iteration, $existingIterations)) {
        // Urutan kosong ditemukan
        // $newAssetCode = $ids . '-' . $iteration;
        // break;
        // }
        // }
        // dd($existingIterations, $newAssetCode);
        // $asset['asset_code'] = $newAssetCode;
        // $asset['pic_dept'] = $pic_dept;
        // dd($asset);
        // $asset->update($request->all());
        // }
        // dd($iddb);
        // }

        if ($request->store_to_database == 'true') {
            // dd($request);

            // Ambil ID aset yang baru saja disimpan
            $inv_id = $asset->id;

            // dd($inv_id);

            // Buat catatan di tabel userhist
            $hist = Userhist::create([
                'inv_id' => $inv_id,
                'hand_over_date' => $request['hand_over_date'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'nik' => $request['nik'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'user' => $request['user'], // Sesuaikan dengan atribut yang sesuai
                'job_position' => $request['job_position'], // Pastikan untuk menyesuaikan dengan atribut yang sesuai
                'dept' => $request['dept'], // Sesuaikan dengan atribut yang sesuai
                'note' => $request['note'], // Sesuaikan dengan atribut yang sesuai
            ]);
        }

        return redirect()->route('inventory')->with('success', 'Asset updated successfully.');
    }

    public function history()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $userhist = Userhist::join('inventories', 'userhists.inv_id', '=', 'inventories.id')
                ->select(
                    'inventories.asset_code as kode_asset',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.status',
                    'userhists.hand_over_date as serah_terima',
                    'userhists.user',
                    'userhists.dept',
                    'userhists.note'
                )
                ->orderBy('userhists.created_at', 'desc')
                ->get();
        } else {
            $userhist = Userhist::join('inventories', 'userhists.inv_id', '=', 'inventories.id')
                ->select(
                    'inventories.asset_code as kode_asset',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.status',
                    'userhists.hand_over_date as serah_terima',
                    'userhists.user',
                    'userhists.dept',
                    'userhists.note'
                )
                ->where('inventories.location', Auth::user()->location)
                ->orderBy('userhists.created_at', 'desc')
                ->get();
        }
        return view('pages.asset.history', compact('userhist'));
    }

    public function repair()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $inventory = inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'repairstatuses.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'repairstatuses.note'
                )->get();
        } else {
            $inventory = inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'repairstatuses.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'repairstatuses.note'
                )
                ->where('inventories.location', Auth::user()->location)
                ->get();
        }

        // dd($inventory);

        return view('pages.asset.repair', compact('inventory'));
    }

    public function inputrepair()
    {
        return view('pages.asset.inputrepair');
    }

    public function storerepair(Request $request)
    {
        // dd($request);
        // Validate the request data
        $request->validate([
            'tanggal_kerusakan' => 'nullable|date',
            'tanggal_pengembalian' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        // Find the inventory based on the asset code
        $inventory = inventory::where('asset_code', $request->asset_code)->first();

        // Update the status of the inventory
        $inventory->status = $request->status;
        $inventory->save();

        // dd($request->status);

        if ($request->status == "Breakdown") {
            // Create the RepairStatus record
            repairstatus::create([
                'inv_id' => $inventory->id,
                'status' => $request->status,
                'tanggal_kerusakan' => $request->tanggal_kerusakan_breakdown,
                'note' => $request->remarks_breakdown,
            ]);
        } else  if ($request->status == "Repair") {
            // Create the RepairStatus record
            repairstatus::create([
                'inv_id' => $inventory->id,
                'status' => $request->status,
                'tanggal_kerusakan' => $request->tanggal_kerusakan_repair,
                'tanggal_pengembalian' => $request->tanggal_pengembalian_repair,
                'note' => $request->remarks_repair,
            ]);
        } else if ($request->status == "Good") {
            // Check the latest RepairStatus record for the inventory
            $latestStatus = repairstatus::where('inv_id', $inventory->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestStatus) {
                // Update the tanggal_pengembalian to today
                $latestStatus->tanggal_pengembalian = Carbon::now();
                $latestStatus->save();
            }
        }

        return redirect()->route('repair_inventory')->with('success', 'Repair status updated successfully.');
    }

    public function getInventoryData(Request $request)
    {
        $assetCode = $request->input('asset_code');
        $inventory = Inventory::where('asset_code', $assetCode)->first();

        if ($inventory) {
            $data = [
                'id' => $inventory->id,
                'location' => $inventory->location,
                'description' => $inventory->description,
                'serial_number' => $inventory->serial_number,
                'acquisition_date' => $inventory->acquisition_date,
                'useful_life' => $inventory->useful_life,
                'acquisition_value' => $inventory->acquisition_value,
                'status' => $inventory->status,
                'merk' => $inventory->merk,
                'type' => $inventory->type,
                'specification' => $inventory->specification,
                // Tambahkan data lain yang ingin Anda kembalikan
            ];

            return response()->json($data);
        } else {
            return response()->json(['error' => 'Inventaris tidak ditemukan.'], 404);
        }
    }

    public function dispose()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'inventories.status',
                    'disposes.id',
                    'disposes.tanggal_penghapusan',
                    'disposes.note',
                    'disposes.approval',
                    'disposes.disposal_document',
                )->get();
        } else {
            $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.acquisition_date',
                    'inventories.location',
                    'inventories.status',
                    'disposes.id',
                    'disposes.tanggal_penghapusan',
                    'disposes.note',
                    'disposes.approval',
                    'disposes.disposal_document',
                )
                ->where('inventories.location', Auth::user()->location)
                ->get();
        }


        // dd($inventory);

        return view('pages.asset.dispose', compact('inventory'));
    }

    public function inputdispose()
    {
        return view('pages.asset.inputdispose');
    }

    public function storedispose(Request $request)
    {
        // dd($request);

        $assetCode = $request->input('asset_code');
        $inventory = Inventory::where('asset_code', $assetCode)->first();

        // Update the status of the inventory
        // Handle file upload
        if ($request->hasFile('disposal_document')) {
            $fileName = time() . '_' . $request->file('disposal_document')->getClientOriginalName();
            $filePath = $request->file('disposal_document')->storeAs('uploads', $fileName, 'public');
        }
        $inventory->status = 'Waiting Dispose';
        $inventory->disposal_date = $request->disposal_date;
        $inventory->save();

        dispose::create([
            'inv_id' => $inventory->id,
            'tanggal_penghapusan' => $request->disposal_date,
            'note' => $request->remarks_repair,
            'disposal_document' => $filePath
        ]);

        return redirect()->route('dispose_inventory')->with('success', 'Successfully.');
    }

    public function report()
    {
        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $inventoryData = Inventory::leftJoin('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->leftJoin('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->leftJoin('userhists', 'inventories.id', '=', 'userhists.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.merk',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.acquisition_date',
                    'inventories.useful_life',
                    'inventories.user',
                    'inventories.dept',
                    'inventories.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'disposes.tanggal_penghapusan',
                    DB::raw('COALESCE(disposes.note, repairstatuses.note) as remarks')
                )
                ->orderBy('inventories.acquisition_date', 'desc')
                ->get();
        } else {
            $inventoryData = Inventory::leftJoin('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->leftJoin('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->leftJoin('userhists', 'inventories.id', '=', 'userhists.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.merk',
                    'inventories.description',
                    'inventories.serial_number',
                    'inventories.location',
                    'inventories.acquisition_date',
                    'inventories.useful_life',
                    'inventories.user',
                    'inventories.dept',
                    'inventories.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'disposes.tanggal_penghapusan',
                    DB::raw('COALESCE(disposes.note, repairstatuses.note) as remarks')
                )
                ->where('inventories.location', Auth::user()->location)
                ->orderBy('inventories.acquisition_date', 'desc')
                ->get();
        }

        return view('pages.report.list', compact('inventoryData'));
    }

    public function inputexcel()
    {
        return view('pages.asset.inputexcel');
    }

    public function storeexcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new YourDataImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data Imported Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to import data: ' . $e->getMessage()]);
        }
    }
}
