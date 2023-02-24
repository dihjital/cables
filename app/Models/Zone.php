<?php

namespace App\Models;

use App\Enums\Action;
use App\Models\Traits\WithHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory, SoftDeletes, WithHistory;

    protected $fillable = [
        'name'
    ];

    public static function boot () {

        parent::boot();

        // TODO: tömeges módosításnál és törlésnél ezek nem futnak le
        // ezeket át kell alakítani úgy, hogy cursor-t használunk
        // illetve a pivot táblák módosításakor nem történik bejegyzés
        // ezeket a pivot tábla modelljére kell tenni

        static::updating(function ($zone) {
            $zone->history(Action::Modify);
        });

        static::deleted(function ($zone) {
            $zone->history(Action::Delete);
        });

        static::created(function ($zone) {
            $zone->history(Action::Add);
        });

    }

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

    public function scopeGetByName(Builder $query, string $zone_name) {
        return $query->where('name', 'like', '%'.$zone_name.'%');
    }

    public function scopeOrderByLocationName(Builder $query, string $direction = 'asc') {
        return $query->with(['locations' => function($q) use ($direction) {
            $q->orderBy('name', $direction);
        }]);
    }

    public function scopeOrderById(Builder $query, string $direction = 'asc') {
        return $query->orderBy('id', $direction);
    }

    public function scopeOrderByName(Builder $query, string $direction = 'asc') {
        return $query->orderBy('name', $direction);
    }

    public function locations () {
        return $this->belongsToMany(Location::class, 'location_zones')->withTimestamps();
    }

    public function connectivity_devices () {
        return $this->hasMany(ConnectivityDevice::class);
    }

}
