<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class divisiSeeder extends Seeder
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
                'nama'      => 'HR'
            ],
            [
                'nama'      => 'IT'
            ]
        ];

        foreach ($data as $item) {
            Divisi::create($item);
        }
    }
}
