<?php

namespace App\Http\Livewire\DataTable\Cable;

use App\Models\Owner;

trait WithFiltering {

    public array $search = [
        'full_name' => null,
        'status' => null
    ];

    protected bool $oldStatus = false;

    public function initializeWithFiltering() {
        $search_terms = session()->get('managecable.search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }
    }

    public function updatedSearch($value, $key) {

        if ($this->oldStatus && $key === 'status')
            $this->search[$key] = null;

        session()->put('managecable.search', $this->search);
        $this->resetPage();

    }

    public function updatingSearch($value, $key) {
        if ($key === 'status' && $this->search[$key] === $value)
            $this->oldStatus = true;
        else
            $this->oldStatus = false;
    }

    public function resetFiltering() {

        $this->search = [
            'full_name' => null,
            'status' => null
        ];

        session()->put('managecable.search', $this->search);

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['full_name'], fn($q, $search) => $q->getByFullName($search))
            ->when($this->search['status'], fn($q, $search) => $q->getByStatus($search));

    }
}
