<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithBulkActions;
use App\Http\Livewire\DataTable\Cable\WithCachedRows;
use App\Http\Livewire\DataTable\Cable\WithFiltering;
use App\Http\Livewire\DataTable\Cable\WithPerPagePagination;
use App\Http\Livewire\DataTable\Cable\WithSorting;
use App\Models\Cable;
use App\Models\CablePair;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageCable extends Component
{

    use WithPerPagePagination, WithFiltering, WithBulkActions, WithCachedRows, WithSorting;

    public bool $showCommentModal = false;
    public bool $showDeleteModal = false;
    public bool $showUpdateModal = false;

    public Cable $currentCable;

    public int $cablePairStatusId = 0;
    public int $cablePurposeId = 0;

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

    public function update() {

        if (count($this->selectedItems) > 1) {

            $this->validate([
                'cablePairStatusId' => [
                    'required',
                    Rule::exists('cable_pair_statuses', 'id')
                ],
                'cablePurposeId' => [
                    'required',
                    Rule::exists('cable_purposes', 'id')
                ]
            ], [
                'required' => 'A(z) :attribute mező megadása kötelező.',
                'exists' => 'A(z) :attribute érték nem létezik az adatbázisban.'
            ], [
                'cablePairStatusId' => 'Kábelpár státusza',
                'cablePurposeId' => 'Kábel felhasználási módja'
            ]);

            // Update cable purpose and cable pair status ...

            (clone $this->rowsQuery)
                ->unless($this->selectAll, fn($query) => $query->whereKey($this->selectedItems))
                ->reorder()
                ->orderBy('id', 'asc')
                ->chunkById(100, function ($rows) {
                    // is there any change ... ?
                    $rows->filter(function ($row) {
                        return ($row->cable_purpose_id !== $this->cablePurposeId) ||
                               (!$row->connection_points
                                   ->filter(function ($item) {
                                   return $item->cable_pair_status_id == $this->cablePairStatusId;
                               })->count());
                    // filter out invalid combinations ...
                    })->filter(function ($row) {
                        if ($this->cablePairStatusId === 3) {
                            if ($row->start_point || $row->end_point) return false;
                        } else {
                            if (!$row->start_point && !$row->end_point) return false;
                        }
                        return true;
                    // execute the changes ...
                    })->map(function ($row) {
                        $row->cable_purpose_id = $this->cablePurposeId;
                        $row->connection_points()
                            ->update(['cable_pair_status_id' => $this->cablePairStatusId]);
                        return $row;
                    // save the changes in the database.
                    })->map->save();
                });

            $this->toggleUpdateModal(Cable::make());
            $this->resetBulkActions();

        }

    }

    public function toggleUpdateModal(Cable $cable) {

        if (count($this->selectedItems) > 1) {
            if ($this->showUpdateModal) {
                $this->showUpdateModal = false;
                $this->reset('cablePurposeId', 'cablePairStatusId');
                $this->resetErrorBag();
            } else {
                $this->cablePairStatusId = $cable->connection_points->first()->cable_pair_status_id ?? 0;
                $this->cablePurposeId = $cable->cable_purpose_id ?? 0;
                $this->showUpdateModal = true;
            }
        } else {
            // re-route to edit-cable livewire component ...
            return redirect()->route('cables.edit', ['cable' => $cable->id]);
        }

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
