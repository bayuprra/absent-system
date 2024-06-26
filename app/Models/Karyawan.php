<?php

namespace App\Models;

use Akun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = "karyawan";
    protected $fillable = [
        "nama",
        "jenis_kelamin",
        "no_telepon",
        "divisi_id",
        'akun_id'
    ];

    public function akun()
    {
        return $this->belongsTo(akunModel::class, 'akun_id');
    }
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
