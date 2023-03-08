<?php

namespace App\Http\Livewire\DataTable\User;

trait WithSorting {

    public $sortField = 'last_login_at';
    public $sortDirection = 'desc';

    public function sortBy($field) {

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;

    }

    public function applySorting($query) {
        return $query
            ->when($this->sortField === 'last_login_at', fn($q) => $q->orderByLastLoginAt($this->sortDirection))
            ->when($this->sortField === 'name', fn($q) => $q->orderByName($this->sortDirection));
    }

}

