<?php

namespace App\Http\Livewire\DataTable\ConnectivityDevice;

use App\Models\Owner;

trait WithFiltering {

    public array $search = [
        'full_name' => null,
        'cd_type' => null,
        'owner' => null
    ];

    public $owner_dropdown = null;
    public $owners = [];

    public function initializeWithFiltering() {
        $search_terms = session()->get('search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }

        $this->owner_dropdown = session()->get('owner_dropdown', $this->owner_dropdown);
    }

    public function selectOwner (Owner $owner) {

        $this->search['owner'] = $owner->id;
        $this->updatedSearch();

        $this->owner_dropdown = $owner->name;
        session()->put('owner_dropdown', $owner->name);

        $this->showOwnersDropDown = false;

    }

    public function updatedOwnerDropdown() {

        if (strlen($this->owner_dropdown >= 2 )) {
            $this->owners =
                Owner::query()
                    ->where('name', 'like', '%'.$this->owner_dropdown.'%')
                    ->orderBy('name', 'asc')
                    ->limit(15)
                    ->get();
        } elseif ($this->owner_dropdown === '') {
            $this->search['owner'] = null;
            $this->updatedSearch();
        }

        session()->put('owner_dropdown', $this->owner_dropdown);

        $this->showOwnersDropDown = true;

    }

    public function updatedSearch() {
        session()->put('search', $this->search);
        $this->resetPage();
    }

    public function resetFiltering() {

        $this->search = [
            'full_name' => null,
            'cd_type' => null,
            'owner' => null
        ];

        $this->owner_dropdown = '';
        $this->updatedOwnerDropdown();

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['full_name'], fn($q, $search) => $q->getByFullName($search))
            ->when($this->search['cd_type'], fn($q, $search) => $q->getByType($search))
            ->when($this->search['owner'], fn($q, $search) => $q->getByOwner($search));

    }
}
