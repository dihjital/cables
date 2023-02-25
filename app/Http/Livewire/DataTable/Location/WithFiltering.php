<?php

namespace App\Http\Livewire\DataTable\Location;

trait WithFiltering {

    public array $search = [
        'location_name' => null,
        'zone_name' => null
    ];

    public function initializeWithFiltering() {
        $search_terms = session()->get('managelocation.search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }
    }

    public function updatedSearch() {
        session()->put('managelocation.search', $this->search);
        $this->resetPage();
    }

    public function resetFiltering() {

        $this->search = [
            'location_name' => null,
            'zone_name' => null
        ];

        session()->put('managelocation.search', $this->search);

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['location_name'], fn($q, $search) => $q->getByName($search));

    }
}
