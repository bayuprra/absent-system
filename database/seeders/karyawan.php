<?php

namespace Database\Seeders;

use App\Models\Karyawan as ModelsKaryawan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class karyawan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama'              => 'Admin',
                'jenis_kelamin'     => 'Perempuan',
                'no_telepon'        => '008867678',
                'divisi'            => 'HR',
                'akun_id'           => '2',
                'created_at'        => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $item) {
            ModelsKaryawan::create($item);
        }
    }
}
