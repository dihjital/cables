<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'zone_id'
    ];


}
