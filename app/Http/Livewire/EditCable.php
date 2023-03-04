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

class EditCable extends BaseCable
{

    use WithValidation;

    public Cable $cable;

    public function mount(Cable $cable) {

        // initialize form using the passed $cable parameter
        // need to check if it really exists ...

        $this->cable = $cable;

        $this->selectCD['startCDOwner'] = ConnectivityDevice::find($cable->start)->owner_id;
        $this->selectCD['endCDOwner'] = ConnectivityDevice::find($cable->end)->owner_id;
        $this->selectCD['startCDId'] = $cable->start;
        $this->selectCD['endCDId'] = $cable->end;
        $this->selectCD['startConnectionPoint'] = $cable->start_point ?? '';
        $this->selectCD['endConnectionPoint'] = $cable->end_point ?? '';

        $this->cableTypeId = $cable->cable_type_id;
        $this->cablePurposeId = $cable->cable_purpose_id;
        $this->cablePairStatusId = CablePairStatus::where('name', $cable->status)->first()->id;
        $this->cableName = $cable->name ?? '';
        $this->cableComment = $cable->comment ?? '';

        $this->installTime = Carbon::parse($cable->i_time)->format('Y-m-d');

    }

    public function update() {

        $this->validateUpdate();

        DB::transaction(function () {

            $this->prepareCable()->save();

            // delete the current cable pairs ...
            CablePair::where('cable_id', $this->cable->id)->delete();

            $this->prepareCablePair($this->cable->id, 'start')->save();
            $this->prepareCablePair($this->cable->id, 'end')->save();

        });

        $this->cable->refresh();

        return redirect(route('cables.index'))
            ->with('success', $this->cable->full_name.' kábel sikeresen módosításra került a rendszerben');

    }

    protected function prepareCable(): Cable {

        $this->cable->name = $this->cableName;
        $this->cable->cable_type_id = $this->cableTypeId;
        $this->cable->start = $this->selectCD['startCDId'];
        $this->cable->end = $this->selectCD['endCDId'];
        $this->cable->i_time = $this->installTime ?: Carbon::now()->toDateString();
        $this->cable->owner_id = 1; // Always defaults to Dataplex
        $this->cable->cable_purpose_id = $this->cablePurposeId;
        $this->cable->comment = $this->cableComment;

        return $this->cable;

    }

    public function render() {
        return view('livewire.edit-cable', [
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
