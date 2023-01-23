<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $appends = [
        'number_of_connectivity_devices',
        'number_of_connectivity_devices_by_zone'
    ];

    public function scopeFilter($query, array $filter) {

        $query->when($filter['location'] ?? false, fn($query, $location) =>
        $query
            ->where('name', 'like', '%' . $location . '%')
        );

        $query->when($filter['zone'] ?? false, fn($query, $zone) =>
            $query->whereHas('zones', fn ($query) =>
                $query->where('name', $zone)
            )
        );

    }

    public function getNumberOfConnectivityDevicesByZoneAttribute()
    {
        // return $this->connectivity_devices()->count();
        return $this->connectivity_devices()->get()->countBy('zone_id');
    }

    public function getNumberOfConnectivityDevicesAttribute()
    {
        return $this->connectivity_devices()->count();
    }

    public function zones () {
        return $this->belongsToMany(Zone::class, 'location_zones')->withTimestamps();
    }

    public function connectivity_devices () {
        return $this->hasMany(ConnectivityDevice::class);
    }

}
