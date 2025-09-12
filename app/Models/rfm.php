<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfm extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     * Laravel otomatis mengenali nama tabel jamak,
     * jadi ini opsional karena tabel kita sudah bernama 'rfms'.
     */
    protected $table = 'rfms';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'no_rfm',
        'deskripsi',
        'dokumen_pdf',
    ];

    /**
     * Jika ingin menyembunyikan kolom tertentu saat data dikonversi ke JSON.
     */
    protected $hidden = [
        // Contoh: 'created_at', 'updated_at'
    ];

    /**
     * Casting data, misal jika butuh konversi otomatis ke tipe tertentu.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
