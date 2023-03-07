<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Cable\WithValidation;
use App\Models\Cable;
use App\Models\ConnectivityDevice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateCable extends BaseCable
{

    use WithValidation;

    public $massInsert = 1;

    public function mount() {
        $this->installTime = Carbon::now()->toDateString();
    }

    // Do not allow '' as an entry for this field. It should be always numeric
    public function updatedMassInsert($value) { $this->massInsert = $value ?: 1; }

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

    public function render() { return view('livewire.create-cable', $this->prepareForRendering()); }

}
