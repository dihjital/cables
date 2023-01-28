<?php

namespace App\Models;

use App\Enums\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $casts = [
      'action' => Action::class
    ];

    protected $fillable = [
        'user_id',
        'action',
        'model_id',
        'model_type',
        'before',
        'after'
    ];

    public function getDateForHumansAttribute() {
        return $this->updated_at->format('Y M d');
    }

    public function scopeOrderByUserName(Builder $query, string $direction = 'asc') {
        return $query->orderBy(User::select('name')
            ->whereColumn('users.id', 'user_id')
            ->orderBy('name', $direction)->limit(1), $direction);
    }

    public function scopeOrderByAction(Builder $query, string $direction = 'asc') {

        foreach (array_column(Action::cases(), 'value') as $action) {
            $query->orderByRaw("action = ? $direction", [$action]);
        }

        return $query;
    }

    public function scopeOrderByUpdatedTime(Builder $query, string $direction = 'asc') {
        return $query->orderBy('updated_at', $direction);
    }

    public function scopeGetByAction(Builder $query, string $action) {
        // return $query->where('action', constant("\App\Enums\Action::$action"));
        return $query->where('action', Action::from(strtolower($action))->name);
    }

}
