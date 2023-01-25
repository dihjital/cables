<?php

namespace App\Http\Livewire\DataTable\User;

trait WithSorting {

    public $sortField;
    public $sortDirection = 'asc';

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
            ->when($this->sortField === 'updated_at', fn($q) => $q->orderByUpdatedAt($this->sortDirection))
            ->when($this->sortField === 'name', fn($q) => $q->orderByName($this->sortDirection));
    }

}

