<?php

namespace App\Http\Livewire\DataTable\User;

use App\Exports\CablesExport;

trait WithBulkActions {

    public array $selectedItems = [];
    public $selectPage = false;
    public $selectAll = false;

    public function initializeWithBulkActions() {

        $this->beforeRender(function () {
            if ($this->selectAll) $this->selectPageRows();
        });

    }

    public function selectPageRows() {
        $this->selectedItems = $this->rows->pluck('id')->toArray();
    }

    public function updatedSelectedItems() {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage($value) {

        if ($value) {
            $this->selectedItems = $this->rows->pluck('id')->toArray();
        } else
            $this->selectedItems = [];

        $this->selectAll = false;

    }

    public function exportSelected() {

        if (count($this->selectedItems) > 0)
            return (new CablesExport())
                ->forFullName($this->search['full_name'] ?? null)
                ->forStatus($this->search['status'] ?? null)
                ->forKeys($this->selectAll ? null : $this->selectedItems)
                ->download('cables.csv');

    }

}

