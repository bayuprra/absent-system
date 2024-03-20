<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absent extends Model
{
    use HasFactory;
    protected $table = "absent";
    protected $fillable = [
        'karyawan_id',
        'latitude',
        'longitude',
        'onRadius'
    ];
}
