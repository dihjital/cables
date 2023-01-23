<?php

namespace App\Http\Livewire;

use App\Imports\ConnectivityDevicesImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

use Validator;

class ImportConnectivitydevices extends Component
{

    use WithFileUploads;

    public $showImportModal = false;
    public $upload;
    public $columns;
    public array $fieldColumnMap = [
        'full_name' => '',
        'start' => '',
        'end' => '',
        'connectivity_device_type' => '',
        'owner' => ''
    ];

    public array $importFailures = [];

    protected array $rules = [
        'fieldColumnMap.full_name' => 'required',
        'fieldColumnMap.start' => 'required',
        'fieldColumnMap.end' => 'required'
    ];

    protected array $attributes = [
        'fieldColumnMap.full_name' => 'Teljes név',
        'fieldColumnMap.start' => 'Kezdőpont',
        'fieldColumnMap.end' => 'Végpont'
    ];

    public function toggleModal() {
        $this->showImportModal = ! $this->showImportModal;
        if (!$this->showImportModal)
            $this->reset();
    }

    public function updatingUpload($value) {
        Validator::make(
            ['upload' => $value],
            ['upload' => 'required|mimes:txt,csv|max:10']
        )->validate();
    }

    public function updatedUpload() {

        $this->columns = (new ConnectivityDevicesImport())
                            ->import($this->upload->getRealPath());

        $this->guessWhichColumnsMapToWhichFields();

    }

    public function import() {

        $this->validate();

        $cdImport = new ConnectivityDevicesImport();

        try {

            $cdImport->setFieldColumnMap($this->fieldColumnMap);
            Excel::import($cdImport, $this->upload->getRealPath());

            $this->toggleModal();

            $this->emitUp('showEmittedFlashMessage', ($cdImport->getRowNumber() - 1).' db. kapcsolati eszköz betöltése sikerült!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $this->importFailures = $e->failures();
        }

    }

    public function guessWhichColumnsMapToWhichFields() {

        $guesses = [
            'full_name' => ['teljes_nev', 'teljesnev', 'Teljes név', 'Teljes nev'],
            'start' => ['start', 'Kezdőpont', 'kezdopont'],
            'end' => ['end', 'Végpont', 'vegpont'],
            'connectivity_device_type' => ['Típus', 'tipus'],
            'owner' => ['Tulajdonos', 'tulajdonos']
        ];

        foreach ($this->columns[0][0] as $column) {
            $match = collect($guesses)->search(fn($options) => in_array(strtolower($column), $options));
            if ($match) $this->fieldColumnMap[$match] = $column;
        }

    }

}
