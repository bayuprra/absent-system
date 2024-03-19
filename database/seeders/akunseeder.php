<?php

namespace Database\Seeders;

use App\Models\AkunModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AkunSeeder extends Seeder
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
                'username'         => 'admin@gmail.com',
                'password'      => bcrypt("admin123"),
                'role_id'       => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'username'         => 'user1@gmail.com',
                'password'      => bcrypt("user1234"),
                'role_id'       => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];

        foreach ($data as $item) {
            AkunModel::create($item);
        }
    }
}
