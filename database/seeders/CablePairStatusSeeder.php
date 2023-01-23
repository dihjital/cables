<?php

namespace Database\Seeders;

use App\Models\CablePairStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CablePairStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cable_pair_statuses =  [
            [
                'name' => 'Disconnected'
            ],
            [
                'name' => 'In use'
            ],
            [
                'name' => 'Spare'
            ],
        ];

        foreach ($cable_pair_statuses as $key => $cable_pair_status) {
            CablePairStatus::create($cable_pair_status);
        }

    }
}
