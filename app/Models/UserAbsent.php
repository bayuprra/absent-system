<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAbsent extends Model
{
    use HasFactory;
    protected $table = "userAbsent";
    protected $fillable = [
        'karyawan_id',
        'absenttime_id',
        'checkin',
        'checkout',
        'flag'
    ];
    public $timestamps = false;
}
