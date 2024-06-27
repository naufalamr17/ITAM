<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'location',
        'description',
        'merk',
        'type',
        'specification',
        'serial_number',
        'os',
        'acquisition_date',
        'disposal_date',
        'useful_life',
        'acquisition_value',
        'status',
        'hand_over_date',
        'nik',
        'user',
        'dept',
    ];

    public function userhist()
    {
        return $this->hasMany(userhist::class);
    }

    public function repairStatuses()
    {
        return $this->hasMany(repairstatus::class);
    }

    public function dispose()
    {
        return $this->hasMany(dispose::class);
    }
}
