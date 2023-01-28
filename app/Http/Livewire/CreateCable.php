<?php

namespace App\Http\Livewire;

use App\Models\CablePairStatus;
use App\Models\CablePurpose;
use App\Models\CableType;
use App\Models\ConnectivityDevice;
use App\Models\Owner;
use Carbon\Carbon;
use Livewire\Component;

class CreateCable extends Component
{

    public $selectCD = [
        'startCDOwner' => 0,
        'endCDOwner' => 0,
        'startCDId' => 0,
        'endCDId' => 0,
        'startConnectionPointId' => 0,
        'endConnectionPointId' => 0
    ];

    public int $cableTypeId = 0;
    public int $cablePurposeId = 0;
    public int $cablePairStatusId = 0;

    public $installTime;

    public string $cableName = '';
    public string $cableComment = '';

    public function mount() {
        $this->installTime = Carbon::now()->toDateString();
    }

    public function updatedSelectCDStartCDOwner($value) {
        $this->selectCD['startCDId'] = 0;
        $this->selectCD['startConnectionPointId'] = 0;
    }

    public function updatedSelectCDEndCDOwner($value) {
        $this->selectCD['endCDId'] = 0;
        $this->selectCD['endConnectionPointId'] = 0;
    }

    public function updatedSelectCDStartCDId($value) {
        $this->selectCD['startConnectionPointId'] = 0;
    }

    public function updatedSelectCDEndCDId($value) {
        $this->selectCD['endConnectionPointId'] = 0;
    }

    public function updatedCableTypeId($value) {

        $abbreviation =
            CableType::whereKey($value)
                ->get()
                ->first()
                ->abbreviation;

        if ($this->selectCD['endCDId'])
            $endCdLocationName =
                ConnectivityDevice::whereKey($this->selectCD['endCDId'])
                    ->with('location')
                    ->get()
                    ->first()
                    ->location->name;

        $this->cableName = $abbreviation.($endCdLocationName ?? '');

    }

    protected function createCablePairsList(array $range = []): array {
        return count($range) ?
            array_map(function ($key, $value) {
                return (object) [
                    'conn_point' => $key,
                    'status' => $value
                ];
            }, array_keys($range), array_values($range)) : [];
    }

    // TODO: ki kell szűrni azokat, ahol nincsen kapcsolati pont csak státusz ...
    protected function getUsableCdRange(string $cdId = ''): array {
        return $cdId ?
            ConnectivityDevice::query()
            ->whereKey($cdId)
            ->get()
            ->first()
            ->calculateUsableCdRange()
            ->toArray() : [];
    }

    public function render() {
        return view('livewire.create-cable', [
            'cableTypes' => CableType::all(),
            'cablePurposes' => CablePurpose::all(),
            'cablePairStatuses' => CablePairStatus::all(),
            'owners' => Owner::query()
                ->orderBy('name', 'asc')
                ->select('id', 'name')
                ->get(),
            'startCDList' => ConnectivityDevice::query()
                ->where('owner_id', $this->selectCD['startCDOwner'])
                ->orderByFullName('asc')
                ->get(),
            'endCDList' => ConnectivityDevice::query()
                ->where('owner_id', $this->selectCD['endCDOwner'])
                ->orderByFullName('asc')
                ->get(),
            'startCablePairsList' =>
                $this->createCablePairsList($this->getUsableCdRange($this->selectCD['startCDId'])),
            'endCablePairsList' =>
                $this->createCablePairsList($this->getUsableCdRange($this->selectCD['endCDId']))
        ]);
    }

}
