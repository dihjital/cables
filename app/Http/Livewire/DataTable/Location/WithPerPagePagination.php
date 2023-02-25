<?php

namespace App\Http\Livewire\DataTable\Location;

use Livewire\WithPagination;

trait WithPerPagePagination {

    use WithPagination;

    public $pageSize = 10;

    public function initializeWithPerPagePagination() {
        $this->pageSize = session()->get('managelocation.pageSize', $this->pageSize);
    }

    public function updatedPageSize($value) {

        session()->put('managelocation.pageSize', $value);
        $this->resetPage();

        // a selectedPage nem resetelődik meg más furcsaságok
        $this->reset(['selectedItems', 'selectPage', 'selectAll']);

    }

    public function applyPerPage($query) {
        return $query->paginate($this->pageSize);
    }

}
