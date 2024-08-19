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
        'comp_name',
        'merk',
        'type',
        'specification',
        'serial_number',
        'os',
        'installed_apps',
        'acquisition_date',
        'disposal_date',
        'useful_life',
        'acquisition_value',
        'status',
        'hand_over_date',
        'nik',
        'user',
        'job_position',
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
