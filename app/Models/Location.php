<?php

namespace App\Models;

use App\Enums\Action;
use App\Models\Traits\WithHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes, WithHistory;

    protected $fillable = [
        'name'
    ];

    protected $appends = [
        'number_of_connectivity_devices',
        'number_of_connectivity_devices_by_zone'
    ];

    public static function boot () {

        parent::boot();

        // TODO: tömeges módosításnál és törlésnél ezek nem futnak le
        // ezeket át kell alakítani úgy, hogy cursor-t használunk
        // illetve a pivot táblák módosításakor nem történik bejegyzés
        // ezeket a pivot tábla modelljére kell tenni

        static::updating(function ($location) {
            $location->history(Action::Modify);
        });

        static::deleted(function ($location) {
            $location->history(Action::Delete);
        });

        static::created(function ($location) {
            $location->history(Action::Add);
        });

    }

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

    public function scopeGetByName(Builder $query, string $location_name) {
        return $query->where('name', 'like', '%'.$location_name.'%');
    }

    public function scopeOrderByZoneName(Builder $query, string $direction = 'asc') {
        return $query->with(['zones' => function($q) use ($direction) {
            $q->orderBy('name', $direction);
        }]);
    }

    public function scopeOrderById(Builder $query, string $direction = 'asc') {
        return $query->orderBy('id', $direction);
    }

    public function scopeOrderByName(Builder $query, string $direction = 'asc') {
        return $query->orderBy('name', $direction);
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
