<?php

namespace App\Models;

use App\Enums\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ConnectivityDevice extends Model
{
    use HasFactory, SoftDeletes;

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
            get: fn ($value) => $value = $this->cables()->count()
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

    public function histories () {
        return $this->belongsToMany(User::class, 'histories', 'model_id')
            ->withTimestamps()
            ->withPivot(['model_type', 'before', 'after'])
            ->latest('pivot_updated_at');
    }

    /** @noinspection PhpVoidFunctionResultUsedInspection */
    public function history($action = null, $userId = null, $diff = null) {

        $action = $action ?: Action::Add;
        $userId = $userId ?: Auth::id();
        $diff = $diff ?: $this->getDiff();

        return $this->histories()->attach($userId, array_merge([
            'action' => $action,
            'model_type' => get_class($this)],
            $diff));

    }

    protected function getDiff() {

        $changed  = $this->getDirty();

        $before = json_encode(array_intersect_key($this->fresh()?->toArray() ?? [], $changed));
        $after = json_encode($changed);

        return compact('before', 'after');

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

}
