<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\User\WithBulkActions;
use App\Http\Livewire\DataTable\User\WithPerPagePagination;
use App\Http\Livewire\DataTable\User\WithSorting;
use App\Models\User;
use Livewire\Component;

class ManageUser extends Component
{

    use WithSorting, WithPerPagePagination, WithBulkActions;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    public function setAdminPrivileges(User $user) {

        $stored_user = User::findOrFail($user->id);

        $stored_user->isAdmin = ! $stored_user->isAdmin;
        $stored_user->save();

    }

    public function enableUser(User $user) {

        $stored_user = User::findOrFail($user->id);

        $stored_user->enabled = ! $stored_user->enabled;
        $stored_user->save();

    }

    public function render()
    {
        return view('livewire.manage-user', [
            'users' => $this->applyPerPage($this->applySorting(User::query()))
        ]);
    }
}
