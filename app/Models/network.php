<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class network extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'issue',
        'details',
        'start_time',
        'end_time',
        'duration',
        'resolution',
    ];
}
