<?php

namespace App\Http\Livewire\DataTable\History;

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
            ->when($this->sortField === 'history.action', fn($q) => $q->orderByAction($this->sortDirection))
            ->when($this->sortField === 'user.name', fn($q) => $q->orderByUserName($this->sortDirection))
            ->when($this->sortField === 'history.updated_at', fn($q) => $q->orderByUpdatedTime($this->sortDirection));
    }

}

