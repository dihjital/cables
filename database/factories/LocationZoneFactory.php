<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationZone>
 */
class LocationZoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'location_id' => Location::factory(),
            'zone_id' => Zone::factory()
        ];
    }
}
