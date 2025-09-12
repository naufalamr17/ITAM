<?php

namespace App\Http\Controllers;

use App\Models\Rfm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RfmController extends Controller
{
    /**
     * Tampilkan halaman index (FE DataTable).
     */
    public function index()
    {
        return view('pages.rfm.index');
    }

    /**
     * Endpoint JSON untuk DataTables.
     */
    public function data(Request $request)
    {
        $rfms = Rfm::select(['id', 'no_rfm', 'deskripsi', 'dokumen_pdf', 'created_at']);

        return DataTables::of($rfms)
            ->addIndexColumn() // DT_RowIndex
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('rfms.show', $row->id);
                $deleteUrl = route('rfms.destroy', $row->id);

                return '
                    <div class="d-flex justify-content-center gap-2">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning mt-3">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Hapus RFM ini?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger mt-3">Hapus</button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getRomanMonth($month)
    {
        $romanMonths = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        return $romanMonths[intval($month)];
    }

    /**
     * Simpan RFM baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_rfm' => ['required', 'string', 'max:191'], // nomor urut saja
            'lokasi' => ['required', 'in:JKT,KDI,SITE'],
            'deskripsi' => ['required', 'string'],
            'dokumen_pdf' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:10240'],
        ]);

        // Ambil data dari input
        $digitAwal = $validated['no_rfm']; // nomor urut
        $lokasi = $validated['lokasi'];    // JKT, KDI, SITE
        $bulan = $this->getRomanMonth(now()->month);
        $tahun = now()->year;

        // Format final No RFM
        $validated['no_rfm'] = "{$digitAwal}/RFM/MLP-{$lokasi}/{$bulan}/{$tahun}";

        // Upload file jika ada
        if ($request->hasFile('dokumen_pdf')) {
            $validated['dokumen_pdf'] = $request->file('dokumen_pdf')->store('rfms', 'public');
        }

        Rfm::create($validated);

        return redirect()->route('rfm')->with('success', 'RFM berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit RFM.
     */
    public function show(Rfm $rfm)
    {
        return view('pages.rfm.edit', compact('rfm'));
    }

    /**
     * Update data RFM.
     */
    public function update(Request $request, Rfm $rfm)
    {
        $validated = $request->validate([
            'no_rfm' => [
                'required',
                'string',
                'max:191',
                Rule::unique('rfms', 'no_rfm')->ignore($rfm->id),
            ],
            'deskripsi' => ['required', 'string'],
            'dokumen_pdf' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:10240'],
        ]);

        if ($request->hasFile('dokumen_pdf')) {
            // Hapus file lama jika ada
            if ($rfm->dokumen_pdf && Storage::disk('public')->exists($rfm->dokumen_pdf)) {
                Storage::disk('public')->delete($rfm->dokumen_pdf);
            }
            $validated['dokumen_pdf'] = $request->file('dokumen_pdf')->store('rfms', 'public');
        }

        $rfm->update($validated);

        return redirect()->route('rfm')->with('success', 'RFM berhasil diperbarui.');
    }

    /**
     * Hapus RFM dan file terkait.
     */
    public function destroy(Rfm $rfm)
    {
        if ($rfm->dokumen_pdf && Storage::disk('public')->exists($rfm->dokumen_pdf)) {
            Storage::disk('public')->delete($rfm->dokumen_pdf);
        }

        $rfm->delete();

        return redirect()->route('rfm')->with('success', 'RFM berhasil dihapus.');
    }

    /**
     * Download dokumen PDF.
     */
    public function download(Rfm $rfm)
    {
        if (!$rfm->dokumen_pdf || !Storage::disk('public')->exists($rfm->dokumen_pdf)) {
            return back()->with('error', 'Dokumen PDF tidak ditemukan.');
        }

        return Storage::disk('public')->download($rfm->dokumen_pdf, basename($rfm->dokumen_pdf));
    }
}
