<?php

namespace App\Http\Livewire\DataTable\Zone;

trait WithFiltering {

    public array $search = [
        'zone_name' => null,
        'location_name' => null
    ];

    public function initializeWithFiltering() {
        $search_terms = session()->get('managezone.search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }
    }

    public function updatedSearch() {
        session()->put('managezone.search', $this->search);
        $this->resetPage();
    }

    public function resetFiltering() {

        $this->search = [
            'zone_name' => null,
            'location_name' => null
        ];

        session()->put('managezone.search', $this->search);

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['zone_name'], fn($q, $search) => $q->getByName($search));

    }
}
