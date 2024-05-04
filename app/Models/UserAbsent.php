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
        'latitude',
        'longitude',
        'flag'
    ];
    public $timestamps = false;

    function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');;
    }
    function absenttime()
    {
        return $this->belongsTo(AbsenTime::class, 'absenttime_id');
    }

    function getUserAbsentData()
    {
        $data = [];

        $karyawanData = DB::table('karyawan')->get();

        foreach ($karyawanData as $karyawan) {
            $userAbsentData = DB::table('userAbsent as u')
                ->join('absentTime as a', 'a.id', '=', 'u.absenttime_id')
                ->where('u.karyawan_id', $karyawan->id)
                ->select('a.tanggal as tanggalAbsent', 'u.id', 'u.checkin', 'u.checkout', 'u.flag', 'u.latitude', 'u.longitude', 'u.karyawan_id')
                ->get();

            $absences = [];

            foreach ($userAbsentData as $absentRecord) {
                $absences[] = [
                    'id' => $absentRecord->id,
                    'tanggalAbsent' => $absentRecord->tanggalAbsent,
                    'checkin' => $absentRecord->checkin,
                    'checkout' => $absentRecord->checkout,
                    'latitude' => $absentRecord->latitude,
                    'longitude' => $absentRecord->longitude,
                    'flag' => $absentRecord->flag,
                    'karyawan_id' => $absentRecord->karyawan_id
                ];
            }

            $data[$karyawan->nama . '&&' . $karyawan->id] = $absences;
        }
        return $data;
    }
}
