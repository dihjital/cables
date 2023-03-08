<?php

namespace App\Models\Traits;

use App\Enums\Action;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait WithHistory
{

    public function histories()
    {
        return $this->belongsToMany(User::class, 'histories', 'model_id')
            ->withTimestamps()
            ->withPivot(['model_type', 'before', 'after'])
            ->latest('pivot_updated_at');
    }

    /** @noinspection PhpVoidFunctionResultUsedInspection */
    public function history($action = null, $userId = null, $diff = null)
    {

        $action = $action ?: Action::Add;
        $userId = $userId ?: Auth::id();
        $diff = $diff ?: $this->getDiff();

        $count = count(array_filter($diff, function ($item) {
            return $item !== '[]'; // JSON representation of an empty array
        }));

        if ($count)
            return $this->histories()->attach($userId, array_merge([
                'action' => $action,
                'model_type' => get_class($this)],
                $diff));

    }

    protected function getDiff(): array
    {

        $changed = $this->getDirty();

        $before = json_encode(array_intersect_key($this->fresh()?->toArray() ?? [], $changed));
        $after = json_encode($changed);

        return compact('before', 'after');

    }

}
