<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Cable extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',
        'cable_type_id',
        'start',
        'end',
        'i_time',
        'patch',
        'owner_id',
        'cable_purpose_id'
    ];

    public $sortable = [
        'name',
        'cable_type_id',
        'start',
        'end',
        'i_time',
        'owner_id',
        'cable_purpose_id'
    ];

    protected $appends = [
        'full_name',
        'status',
        'start_point',
        'end_point'
    ];

    protected $dates = [
        'i_time'
    ];

    public function fullName(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = $this->cable_type->abbreviation.$this->name
        );
    }

    public function status(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = $this->connection_points->first()->cable_pair_status->name
        );
    }

    public function startPoint(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = $this->connection_points
                    ->where('conn_dev_id', $this->start)
                    ->first()
                    ->conn_point ?? ''
        );
    }

    public function endPoint(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = $this->connection_points
                ->where('conn_dev_id', $this->end)
                ->first()
                ->conn_point ?? ''
        );
    }

    public function getStatusColorAttribute() {
        return [
            'Disconnected' => 'color: white; background-color: #42a5f5',
            'In use' => '',
            'Spare' => 'color: white; background-color: #ef5350'
         ][$this->status];
    }

    public function getDateForHumansAttribute() {
        return $this->i_time->format('Y M d');
    }

    public function scopeGetByStatus(Builder $query, int $status) {
        return $query->whereHas('connection_points', function ($q) use ($status) {
            $q->where('cable_pair_status_id', $status);
        });
    }

    public function scopeGetByFullName(Builder $query, string $fullName) {
        return $query->whereRaw("id IN
                    (SELECT cables.id
                    FROM cables
	                LEFT JOIN
	                    cable_types ON cables.cable_type_id = cable_types.id
                    WHERE
                            position('" . $fullName . "' IN
                            concat(cable_types.abbreviation, cables.name)))");
    }

    public function scopeOrderByType(Builder $query, string $direction = 'asc') {
        return $query->orderBy(CableType::select('name')
            ->whereColumn('cable_types.id', 'cable_type_id')
            ->orderBy('name', $direction)->limit(1), $direction);
    }

    public function scopeOrderByInstallDate(Builder $query, string $direction = 'asc') {
        return $query->orderBy('i_time', $direction);
    }

    public function scopeOrderByFullName(Builder $query, string $direction = 'asc') {
        return $query->orderBy(CableType::select('name')
            ->whereColumn('cable_types.id', 'cable_type_id')
            ->orderBy('name', $direction)->limit(1), $direction)
            ->orderBy('name', $direction);
    }

    public function cable_type() {
        return $this->belongsTo(CableType::class);
    }

    public function cd_start() {
        return $this->belongsTo(ConnectivityDevice::class, 'start', 'id');
    }

    public function cd_end() {
        return $this->belongsTo(ConnectivityDevice::class, 'end', 'id');
    }

    public function owner() {
        return $this->belongsTo(Owner::class);
    }

    public function cable_purpose() {
        return $this->belongsTo(CablePurpose::class);
    }

    public function connection_points() {
        return $this->hasMany(CablePair::class);
    }

}
