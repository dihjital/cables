<?php

namespace Database\Seeders;

use App\Models\ConnectivityDeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectivityDeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cd_types = [
            ['name' => 'Intermediate Distribution Cabinet'],
            ['name' => 'Main Distribution Frame'],
            ['name' => 'Double Data Jack Unit'],
            ['name' => 'Customer Device'],
            ['name' => 'Dataplex Equipment'],
            ['name' => 'Telco Device'],
        ];

        foreach ($cd_types as $key => $cd_type) {
            ConnectivityDeviceType::create($cd_type);
        }

    }
}
