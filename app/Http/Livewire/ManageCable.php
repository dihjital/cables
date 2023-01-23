<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithBulkActions;
use App\Http\Livewire\DataTable\Cable\WithCachedRows;
use App\Http\Livewire\DataTable\Cable\WithFiltering;
use App\Http\Livewire\DataTable\Cable\WithPerPagePagination;
use App\Http\Livewire\DataTable\Cable\WithSorting;
use App\Models\Cable;
use Livewire\Component;

class ManageCable extends Component
{

    use WithPerPagePagination, WithFiltering, WithBulkActions, WithCachedRows, WithSorting;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    public function getRowsQueryProperty() {

        $query = Cable::query()
            ->with(['cable_type',
                'cd_start',
                'cd_end',
                'owner',
                'cable_purpose'
            ]);

        // return $this->applyFiltering($query);
        return $this->applySorting($this->applyFiltering($query));

    }

    public function getRowsProperty() {
        return $this->cache(function() {
            return $this->applyPerPage($this->rowsQuery);
        });
    }

    public function render()
    {

        // return view('livewire.manage-cable', [
        //    'cables' => $this->applyPerPage($this->applyFiltering(Cable::sortable()
        //        ->with(['cable_type', 'cd_start', 'cd_end', 'owner', 'cable_purpose'])))
        // ]);

        return view('livewire.manage-cable', [
            'cables' => $this->rows
        ]);

    }
}
