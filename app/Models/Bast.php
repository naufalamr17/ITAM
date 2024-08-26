<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bast extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'pic',
        'nik_user',
        'jenis_barang',
        'merk',
        'type',
        'serial_number',
        'color',
        'spesifikasi'
    ];
}
