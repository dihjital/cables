<?php

namespace App\Http\Livewire\DataTable\Owner;

trait WithFiltering {

    public array $search = [
        'owner_name' => null
    ];

    public function initializeWithFiltering() {
        $search_terms = session()->get('manageowner.search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }
    }

    public function updatedSearch() {
        session()->put('manageowner.search', $this->search);
        $this->resetPage();
    }

    public function resetFiltering() {

        $this->search = [
            'owner_name' => null
        ];

        session()->put('manageowner.search', $this->search);

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['owner_name'], fn($q, $search) => $q->getByName($search));

    }
}
