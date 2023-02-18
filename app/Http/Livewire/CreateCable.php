<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithValidation;
use App\Models\Cable;
use App\Models\CablePair;
use App\Models\CablePairStatus;
use App\Models\CablePurpose;
use App\Models\CableType;
use App\Models\ConnectivityDevice;
use App\Models\Owner;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateCable extends Component
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

    public $massInsert = 1;

    public function mount() {
        $this->installTime = Carbon::now()->toDateString();
    }

    // Do not allow '' as an entry for this field. It should be always numeric
    public function updatedMassInsert($value) { $this->massInsert = $value ?: 1; }

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

    public function save() {

        $iteration = $this->massInsert;

        while ($iteration) {

            $this->validateSave();

            DB::transaction(function () {

                $cable = $this->prepareCable();
                $cable->save();

                $this->prepareCablePair($cable->id, 'start')->save();
                $this->prepareCablePair($cable->id, 'end')->save();

            });

            if ($this->selectCD['startConnectionPoint'])
                if (!($this->selectCD['startConnectionPoint'] =
                    ConnectivityDevice::find($this->selectCD['startCDId'])
                        ->getNextFreeConnectionPointName()))
                    break;

            if ($this->selectCD['endConnectionPoint'])
                if (!($this->selectCD['endConnectionPoint'] =
                    ConnectivityDevice::find($this->selectCD['endCDId'])
                        ->getNextFreeConnectionPointName()))
                    break;

            if (!($this->cableName = $this->generateCableNameHint()))
                break;

            $iteration--;

        }

        return redirect(route('cables.index'))
            ->with('success', $this->massInsert - $iteration.' db. kábel sikeresen rögzítésre került a rendszerben');

    }

    protected function prepareCable(): Cable {

        $cable = new Cable;

        // $cable->name = substr($this->cableName, 1);
        $cable->name = $this->cableName;
        $cable->cable_type_id = $this->cableTypeId;
        $cable->start = $this->selectCD['startCDId'];
        $cable->end = $this->selectCD['endCDId'];
        $cable->i_time = $this->installTime ?: Carbon::now()->toDateString();
        $cable->owner_id = 1; // Always defaults to Dataplex
        $cable->cable_purpose_id = $this->cablePurposeId;
        $cable->comment = $this->cableComment;

        return $cable;

    }

    protected function prepareCablePair(int $cable_id, string $pair = 'start'): CablePair {

        $cable_pair = new CablePair;

        $cable_pair->conn_dev_id = $this->selectCD[$pair.'CDId'];
        $cable_pair->conn_point = $this->selectCD[$pair.'ConnectionPoint'];
        $cable_pair->cable_id = $cable_id;
        $cable_pair->cable_pair_status_id = $this->cablePairStatusId;

        return $cable_pair;

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
