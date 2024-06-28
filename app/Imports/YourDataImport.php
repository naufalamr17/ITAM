<?php

namespace App\Imports;

use App\Models\inventory;
use App\Models\YourModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class YourDataImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        inventory::create([
            'asset_code' => $row['kode_asset'],
            'location' => ucwords(strtolower($row['lokasi'])),
            'description' => $row['kategori'],
            'merk' => $row['merk'],
            'type' => $row['type'],
            'specification' => $row['specification'],
            'serial_number' => $row['serial_number'],
            'os' => $row['os'],
            'installed_apps' => $row['installed_apps'],
            'acquisition_date' => $row['tanggal_perolehan'],
            'acquisition_value' => $row['nilai_perolehan'],
            'hand_over_date' => $row['hand_over_date'],
            'nik' => $row['nik'],
            'user' => $row['user'],
            'job_position' => $row['job_position'],
            'dept' => $row['dept'],
        ]);
    }
}
