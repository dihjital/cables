<?php

namespace App\Http\Livewire\DataTable\Cable;

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
            ->when($this->sortField === 'i_time', fn($q) => $q->orderByInstallDate($this->sortDirection))
            ->when($this->sortField === 'type', fn($q) => $q->orderByType($this->sortDirection))
            ->when($this->sortField === 'name', fn($q) => $q->orderByFullName($this->sortDirection));
    }

}

