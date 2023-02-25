<?php

namespace App\Http\Livewire\DataTable\Location;

trait WithSorting {

    public $sortField = 'id';
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
            ->when($this->sortField === 'id', fn($q) => $q->orderById($this->sortDirection))
            ->when($this->sortField === 'name', fn($q) => $q->orderByName($this->sortDirection))
            ->when($this->sortField === 'zone.name', fn($q) => $q->orderByZoneName($this->sortDirection));
    }

}

