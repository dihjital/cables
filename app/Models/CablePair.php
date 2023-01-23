<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CablePair extends Model
{
    use HasFactory;

    protected $fillable = [
        'conn_dev_id',
        'conn_point',
        'cable_id',
        'cable_pair_status_id'
    ];

    public function cable_pair_status() {
        return $this->belongsTo(CablePairStatus::class);
    }

    public function cable() {
        return $this->belongsTo(Cable::class);
    }

    public function conn_dev() {
        return $this->belongsTo(ConnectivityDevice::class, 'conn_dev_id', 'id');
    }

}
