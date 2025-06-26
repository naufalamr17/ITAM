<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'location',
        'category',
        'merk',
        'specification',
        'serial_number',
        'acquisition_date',
        'acquisition_value',
        'hand_over_date',
        'nik',
        'remarks',
    ];
}
