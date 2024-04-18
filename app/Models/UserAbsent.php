<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');;
    }

    function getUserAbsentData()
    {
        $data = [];

        $karyawanData = DB::table('karyawan')->get();

        foreach ($karyawanData as $karyawan) {
            $userAbsentData = DB::table('userAbsent as u')
                ->join('absentTime as a', 'a.id', '=', 'u.absenttime_id')
                ->where('u.karyawan_id', $karyawan->id)
                ->select('a.tanggal as tanggalAbsent', 'u.checkin', 'u.checkout', 'u.flag')
                ->get();

            $absences = [];

            foreach ($userAbsentData as $absentRecord) {
                $absences[] = [
                    'tanggalAbsent' => $absentRecord->tanggalAbsent,
                    'checkin' => $absentRecord->checkin,
                    'checkout' => $absentRecord->checkout,
                    'flag' => $absentRecord->flag
                ];
            }

            $data[$karyawan->nama] = $absences;
        }
        return $data;
    }
}
