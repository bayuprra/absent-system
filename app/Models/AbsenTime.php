<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenTime extends Model
{
    use HasFactory;
    protected $table = "absentTime";
    protected $fillable = [
        'tanggal',
        'status'
    ];
    public $timestamps = false;
}
