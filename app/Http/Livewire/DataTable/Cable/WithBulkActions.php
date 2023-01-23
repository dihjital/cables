<?php

namespace App\Http\Livewire\DataTable\Cable;

use App\Exports\ConnectivityDevicesExport;

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
            return (new ConnectivityDevicesExport())
                ->forFullName($this->search['full_name'] ?? null)
                ->forCDType($this->search['cd_type'] ?? null)
                ->forOwner($this->search['owner'] ?? null)
                ->forKeys($this->selectAll ? null : $this->selectedItems)
                ->download('connectivity_devices.csv');

    }

}

