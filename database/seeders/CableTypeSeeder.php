<?php

namespace Database\Seeders;

use App\Models\CableType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CableTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cable_types =  [
            [
                'abbreviation' => 'C',
                'name' => 'Coaxial'
            ],
            [
                'abbreviation' => 'U',
                'name' => 'Twisted Pair'
            ],
            [
                'abbreviation' => 'F',
                'name' => 'Fiber'
            ],
        ];

        foreach ($cable_types as $key => $type) {
            CableType::create($type);
        }
    }
}
