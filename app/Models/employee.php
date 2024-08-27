<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasFactory;

    // Specify the connection name
    protected $connection = 'approval';

    // Specify the table name if it's different from the plural form of the model name
    protected $table = 'employees';

    // Specify fillable attributes for mass assignment
    protected $fillable = [
        'nik',
        'nama',
        'organization',
        'job_position',
        'job_level',
        'branch_name',
        'poh',
    ];
}
