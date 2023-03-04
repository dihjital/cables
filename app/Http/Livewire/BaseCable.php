<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithValidation;
use App\Models\Cable;
use App\Models\CablePair;
use App\Models\ConnectivityDevice;
use App\Models\Owner;
use Livewire\Component;

class BaseCable extends Component
{

    use WithValidation;

    public $selectCD = [
        'startCDOwner' => 0,
        'endCDOwner' => 0,
        'startCDId' => 0,
        'endCDId' => 0,
        'startConnectionPoint' => '',
        'endConnectionPoint' => ''
    ];

    public int $cableTypeId = 0;
    public int $cablePurposeId = 0;
    public int $cablePairStatusId = 0;

    public $installTime;

    public string $cableName = '';
    public string $cableComment = '';

    protected $listeners = [
        'ownerAdded'
    ];

    public function ownerAdded(Owner $owner) {
        $this->selectCD['startCDOwner'] = $owner->id;
        $this->render();
    }

    public function updatedSelectCDStartCDOwner($value) {
        $this->selectCD['startCDId'] = 0;
        $this->selectCD['startConnectionPoint'] = '';
    }

    public function updatedSelectCDEndCDOwner($value) {
        $this->selectCD['endCDId'] = 0;
        $this->selectCD['endConnectionPoint'] = '';
    }

    public function updatedSelectCDStartCDId($value) { $this->selectCD['startConnectionPoint'] = ''; }

    public function updatedSelectCDEndCDId($value) { $this->selectCD['endConnectionPoint'] = ''; }

    public function updatedCableTypeId($value) { $this->cableName = $this->generateCableNameHint($value); }

    public function resetStartConnectionPoint() { $this->selectCD['startConnectionPoint'] = ''; }

    public function resetEndConnectionPoint() { $this->selectCD['endConnectionPoint'] = ''; }

    protected function generateCableNameHint(int $cableType = 0, string $endCdLocationName = ''): string {

        $cableType = $cableType ?: $this->cableTypeId;

        if (!$endCdLocationName) {
            if ($this->selectCD['endCDId']) {
                $endCdLocationName =
                    ConnectivityDevice::whereKey($this->selectCD['endCDId'])
                        ->with('location')
                        ->get()
                        ->first()
                        ->location->name;
            } else {
                return '';
            }
        }

        return Cable::getNextCableName($cableType, $endCdLocationName);

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
            ->filter(function ($value, $key) {
                return $key !== '';
            })
            ->toArray() : [];
    }

    protected function prepareCablePair(int $cable_id, string $pair = 'start'): CablePair {

        $cable_pair = new CablePair;

        $cable_pair->conn_dev_id = $this->selectCD[$pair.'CDId'];
        $cable_pair->conn_point = $this->selectCD[$pair.'ConnectionPoint'];
        $cable_pair->cable_id = $cable_id;
        $cable_pair->cable_pair_status_id = $this->cablePairStatusId;

        return $cable_pair;

    }

}
