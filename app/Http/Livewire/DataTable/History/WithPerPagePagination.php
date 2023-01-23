<?php

namespace App\Http\Livewire\DataTable\History;

use Livewire\WithPagination;

trait WithPerPagePagination {

    use WithPagination;

    public $pageSize = 10;

    public function initializeWithPerPagePagination() {
        $this->pageSize = session()->get('listHistory.pageSize', $this->pageSize);
    }

    public function updatedPageSize($value) {

        session()->put('listHistory.pageSize', $value);
        $this->resetPage();

    }

    public function applyPerPage($query) {
        return $query->paginate($this->pageSize);
    }

}
