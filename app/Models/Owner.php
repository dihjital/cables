<?php

namespace App\Models;

use App\Enums\Action;
use App\Models\Traits\WithHistory;
use App\Models\Traits\WithOwnersDescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, SoftDeletes, WithHistory, WithOwnersDescription;

    protected $fillable = [
        'name'
    ];

    public static function boot () {

        parent::boot();

        // TODO: tömeges módosításnál és törlésnél ezek nem futnak le
        // ezeket át kell alakítani úgy, hogy cursor-t használunk
        // illetve a pivot táblák módosításakor nem történik bejegyzés
        // ezeket a pivot tábla modelljére kell tenni

        static::updating(function ($owner) {
            $owner->history(Action::Modify);
        });

        static::deleted(function ($owner) {
            $owner->history(Action::Delete);
        });

        static::created(function ($owner) {
            $owner->history(Action::Add);
        });

    }

    public function connectivity_devices () {
        return $this->hasMany(ConnectivityDevice::class);
    }

}
