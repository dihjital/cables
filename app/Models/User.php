<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'isAdmin',
        'enabled',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private int $avatarSize = 40;

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

    public function avatarUrl() {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s='.$this->avatarSize;
    }

    public function scopeOrderByName(Builder $query, string $direction = 'asc') {
        return $query->orderBy('name', $direction);
    }

    public function scopeOrderByUpdatedAt(Builder $query, string $direction = 'asc') {
        return $query->orderBy('updated_at', $direction);
    }

}
