<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class akunModel extends Authenticatable
{
    use HasFactory;
    protected $table = "akun";
    protected $fillable = [
        'username',
        'password',
        'role_id',
    ];
    protected $appends = ['role'];
    protected $hidden = [
        'password',
    ];

    public function getAdminLoginData($account_id)
    {
        return DB::table('akun')
            ->join('role', 'akun.role_id', '=', 'role.id')
            ->select('akun.id', 'akun.username', 'akun.role_id', 'role.nama as nama_role')
            ->where('akun.id', $account_id)
            ->first();
    }
}
