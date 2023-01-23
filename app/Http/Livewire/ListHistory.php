<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\History\WithPerPagePagination;
use App\Http\Livewire\DataTable\History\WithSorting;
use App\Models\History;
use Livewire\Component;

class ListHistory extends Component
{

    use WithPerPagePagination, WithSorting;

    public int $selectedItem = 0;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    public function selectItem(History $history) {
        $this->selectedItem =
            $this->selectedItem === $history->id
                ? 0 : $history->id;
    }

    public function render() {
        return view('livewire.list-history', [
            'historyItems' => $this->applyPerPage(
                $this->applySorting(History::query())
            )
        ]);
    }
}
