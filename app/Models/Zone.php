<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function scopeFilter($query, array $filter) {

        $query->when($filter['zone'] ?? false, fn($query, $zone) =>
            $query
                ->where('name', 'like', '%' . $zone . '%')
        );

        $query->when($filter['location'] ?? false, fn($query, $location) =>
            $query->whereHas('locations', fn ($query) =>
                $query->where('name', $location)
            )
        );

    }

    public function locations () {
        return $this->belongsToMany(Location::class, 'location_zones')->withTimestamps();
    }

    public function connectivity_devices () {
        return $this->hasMany(ConnectivityDevice::class);
    }

}
