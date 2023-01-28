<?php

namespace App\Http\Livewire\DataTable\History;

trait WithFiltering {

    public array $search = [
        'action' => null
    ];

    protected bool $oldAction = false;

    public function initializeWithFiltering() {
        $search_terms = session()->get('listhistory.search', $this->search);
        foreach ($search_terms as $term => $value) {
            $this->search[$term] = $value;
        }
    }

    public function updatedSearch($value, $key) {

        if ($this->oldAction && $key === 'action')
            $this->search[$key] = null;

        session()->put('listhistory.search', $this->search);
        $this->resetPage();

    }

    public function updatingSearch($value, $key) {
        if ($key === 'action' && $this->search[$key] === $value)
            $this->oldAction = true;
        else
            $this->oldAction = false;
    }

    public function resetFiltering() {

        $this->search = [
            'action' => null
        ];

        session()->put('listhistory.search', $this->search);

    }

    public function applyFiltering($query) {

        return $query
            ->when($this->search['action'], fn($q, $search) => $q->getByAction($search));

    }
}
