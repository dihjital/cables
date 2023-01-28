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

    public bool $showCommentModal = false;

    public Cable $currentCable;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    protected $rules = [
        'currentCable.comment' => 'string|max:255',
    ];

    public function mount() {
        $this->currentCable = new Cable();
    }

    public function save() {

        // TODO: Ellenőrzés működését tesztelni
        $this->validate();
        $this->currentCable->save();

        session()->flash('success',
                         'A '.$this->currentCable->full_name.' kábelhez a megjegyzés sikeresen felvitele került');

        $this->showCommentModal = false;

    }

    public function toggleCommentModal (Cable $cable): void {

        $this->currentCable = $cable ?? null;

        $this->showCommentModal = true;

    }

    public function getRowsQueryProperty() {

        $query = Cable::query()
            ->with(['cable_type',
                'cd_start',
                'cd_end',
                'owner',
                'cable_purpose'
            ]);

        return $this->applySorting($this->applyFiltering($query));

    }

    public function getRowsProperty() {
        return $this->cache(function() {
            return $this->applyPerPage($this->rowsQuery);
        });
    }

    public function render() {
        return view('livewire.manage-cable', ['cables' => $this->rows]);
    }
}
