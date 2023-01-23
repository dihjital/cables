<?php

namespace Database\Seeders;

use App\Models\CablePurpose;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CablePurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cable_purposes = [
            [
                'name' => 'Data Network'
            ],
            [
                'name' => 'Telephone'
            ],
            [
                'name' => 'LAN'
            ],
            [
                'name' => 'MLL'
            ],
            [
                'name' => 'ISDN30'
            ],
            [
                'name' => 'ISDN2'
            ],
            [
                'name' => 'Gerinckábel'
            ],
        ];

        foreach ($cable_purposes as $key => $cable_purpose) {
            CablePurpose::create($cable_purpose);
        }

    }
}
