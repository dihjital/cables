<?php

namespace App\Models;

use App\Enums\Action;
use App\Models\Traits\WithCDDescription;
use App\Models\Traits\WithHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConnectivityDevice extends Model
{
    use HasFactory, SoftDeletes, WithHistory, WithCDDescription;

    protected $casts = [
        'action' => Action::class
    ];

    protected $fillable = [
        'name',
        'zone_id',
        'location_id',
        'start',
        'end',
        'owner_id',
        'connectivity_device_type_id'
    ];

    protected $appends = [
        'full_name',
        'cable_count'
    ];

    public static function boot () {

        parent::boot();

        // TODO: tömeges módosításnál és törlésnél ezek nem futnak le
        // ezeket át kell alakítani úgy, hogy cursor-t használunk

        static::updating(function ($connectivity_device) {
            $connectivity_device->history(Action::Modify);
        });

        static::deleted(function ($connectivity_device) {
            $connectivity_device->history(Action::Delete);
        });

        static::created(function ($connectivity_device) {
            $connectivity_device->history(Action::Add);
        });

    }

    public function fullName(): Attribute {
        return Attribute::make(
          get: fn ($value) => $value = $this->zone?->name.'/'.$this->location?->name.'-'.$this?->name
        );
    }

    public function cableCount(): Attribute {
        return Attribute::make(
            get: fn ($value) => $this->cables()->count()
            // get: fn ($value) => $value = $this->withCount('cables')->get()
        );
    }

    public function owner () {
        return $this->belongsTo(Owner::class);
    }

    public function connectivity_device_type () {
        return $this->belongsTo(ConnectivityDeviceType::class);
    }

    public function zone () {
        return $this->belongsTo(Zone::class);
    }

    public function location () {
        return $this->belongsTo(Location::class);
    }

    public function cable_pairs () {
        return $this->hasMany(CablePair::class, 'conn_dev_id', 'id');
    }

    public function cables () {
        return $this->belongsToMany(Cable::class, CablePair::class, 'conn_dev_id');
    }

    public function scopeGetByOwner(Builder $query, int $owner) {
        return $query->where('owner_id', $owner);
    }

    public function scopeGetByType(Builder $query, int $type) {
        return $query->where('connectivity_device_type_id', $type);
    }

    public function scopeGetByFullName(Builder $query, string $fullName) {
        return $query->whereRaw("id IN
                    (SELECT connectivity_devices.id
                        FROM connectivity_devices
                            LEFT JOIN
                                zones ON zones.id = connectivity_devices.zone_id
                            LEFT JOIN
                                locations ON locations.id = connectivity_devices.location_id
                        WHERE
                            position('" . $fullName . "' IN
                            concat(zones.name,'/',locations.name,'-',connectivity_devices.name)))");
    }

    public function scopeOrderByOwnerName(Builder $query, string $direction = 'asc') {
        return $query->orderBy(Owner::select('name')
            ->whereColumn('owners.id', 'owner_id')
            ->orderBy('name', $direction)->limit(1), $direction);
    }

    public function scopeOrderByType(Builder $query, string $direction = 'asc') {
        return $query->orderBy(ConnectivityDeviceType::select('name')
            ->whereColumn('connectivity_device_types.id', 'connectivity_device_type_id')
            ->orderBy('name', $direction)->limit(1), $direction);
    }

    public function scopeOrderByFullName(Builder $query, string $direction = 'asc') {
        return $query->orderBy(Zone::select('name')
            ->whereColumn('zones.id', 'zone_id')
            ->orderBy('name', $direction)->limit(1), $direction)
            ->orderBy(Location::select('name')
            ->whereColumn('locations.id', 'location_id')
            ->orderBy('name', $direction)->limit(1), $direction)
            ->orderBy('name', $direction);
    }

    public function listUsedCdPorts() {

        return collect(ConnectivityDevice::query()
            ->whereKey($this->id)
            ->with([
                'cable_pairs',
                'cable_pairs.cable_pair_status'
            ])
            ->first()
            ->cable_pairs)->pluck('cable_pair_status.name', 'conn_point');

    }

    public function calculateUsableCdRange() {

        return collect(
            array_map(
                function ($item) {
                    return [
                        'conn_point' => $item,
                        'status' => 'Free'
                    ];
                }, $this->calculateCdRange()))
            ->pluck('status', 'conn_point')
            ->merge(collect($this->listUsedCdPorts()));

    }

    public function subtractConnectionPoints(string $start = '', string $end = ''): object {

        $start = $start ?: $this->start;
        $end = $end ?: $this->end;

        $start_matches = $end_matches = [];

        preg_match("/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si",
            $start, $start_matches);

        preg_match("/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si",
            $end, $end_matches);

        return (object) [
            'start_zone' => $start_matches[1],
            'end_zone' => $end_matches[1],
            'start_stripe' => $start_matches[2],
            'end_stripe' => $end_matches[2],
            'start_port' => $start_matches[3],
            'end_port' => $end_matches[3]
        ];

    }

    public function calculateCdRange(string $start = '', string $end = ''): array {

        $start = $start ?: $this->start;
        $end = $end ?: $this->end;

        $cdRange = $this->subtractConnectionPoints($start, $end);

        $conn_points = [];

        for($i = $cdRange->start_stripe; $i <= $cdRange->end_stripe; $i++) {
            for($j = $cdRange->start_port; $j <= $cdRange->end_port; $j++) {
                $conn_points[] = sprintf('Z%03dS%02dP%03d', $cdRange->start_zone, $i, $j);
            }
        }

        return $conn_points;

    }

    public function getNextFreeConnectionPointName(): string {

        return $this->calculateUsableCdRange()
            ->filter(function ($value, $key) {
                return $value === 'Free';
            })->keys()->take(1)->shift() ?: '';

    }

}
