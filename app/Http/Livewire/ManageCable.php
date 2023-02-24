<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithBulkActions;
use App\Http\Livewire\DataTable\Cable\WithCachedRows;
use App\Http\Livewire\DataTable\Cable\WithFiltering;
use App\Http\Livewire\DataTable\Cable\WithPerPagePagination;
use App\Http\Livewire\DataTable\Cable\WithSorting;
use App\Models\Cable;
use App\Models\CablePair;
use Livewire\Component;

class ManageCable extends Component
{

    use WithPerPagePagination, WithFiltering, WithBulkActions, WithCachedRows, WithSorting;

    public bool $showCommentModal = false;
    public bool $showDeleteModal = false;

    public Cable $currentCable;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    protected $rules = [
        'currentCable.comment' => 'string|max:255',
    ];

    protected $listeners = [
        'showEmittedFlashMessage'
    ];

    public function showEmittedFlashMessage($message) {
        session()->flash('success', $message);
    }

    public function mount() {
        $this->currentCable = new Cable();
    }

    public function confirmDelete (Cable $cable) {

        $this->currentCable = $cable ?? null;
        $this->showDeleteModal = true;

    }

    public function delete() {

        // TODO: A selectedItems alapértelmezésbe állítása lehet másképpen is?
        // Ha nem töröltünk egyetlen rekordot sem, akkor nem kell a flash message ...

        $deleteCount = 0;

        if (count($this->selectedItems) > 0) {
            (clone $this->rowsQuery)
                ->unless($this->selectAll, fn($query) => $query->whereKey($this->selectedItems))
                ->reorder()
                ->orderBy('id', 'asc')
                ->chunkById(100, function ($rows) use (&$deleteCount) {
                    $deleteCount = count($rows->filter(function ($row) {
                        if ($row->status !== 'In use') {
                            CablePair::query()->where('cable_id', $row->id)->delete();
                            return true;
                        }
                    })->map->delete());
                });
            $this->selectedItems = [];
        } elseif ($this->currentCable->status !== 'In use') {
            $deleteCount = $this->currentCable->delete();
            CablePair::query()->where('cable_id', $this->currentCable->id)->delete();
        }

        $this->showDeleteModal = false;

        session()->flash('success', "$deleteCount db. kábel sikeresen törlésre került");

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
