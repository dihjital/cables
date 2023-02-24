<?php

namespace App\Http\Livewire;

use App\Imports\CablesImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImportCables extends Component
{

    use WithFileUploads;

    public bool $showImportModal = false;
    public $upload;
    public $columns;

    public array $fieldColumnMap = [
        'full_name' => '',
        'startCD' => '',
        'start' => '',
        'endCD' => '',
        'end' => '',
        'i_time' => '',
        'status' => '',
        'purpose' => ''
    ];

    public array $importFailures = [];

    protected array $rules = [
        'fieldColumnMap.full_name' => 'required',
        'fieldColumnMap.startCD' => 'required',
        'fieldColumnMap.start' => 'required',
        'fieldColumnMap.endCD' => 'required',
        'fieldColumnMap.end' => 'required',
        'fieldColumnMap.status' => 'required',
        'fieldColumnMap.purpose' => 'required'
    ];

    protected array $messages = [
        'required' => 'A(z) :attribute mező megadása kötelező.',
    ];

    // for some reason if I change the visibility to protected this will not work
    public array $attributes = [
        'fieldColumnMap.full_name' => 'Név',
        'fieldColumnMap.startCD' => 'Kezdő kapcsolati eszköz',
        'fieldColumnMap.start' => 'Kezdőpont',
        'fieldColumnMap.endCD' => 'Végződő kapcsolati eszköz',
        'fieldColumnMap.end' => 'Végpont',
        'fieldColumnMap.i_time' => 'Telepítés dátuma',
        'fieldColumnMap.status' => 'Kábelpár állapota',
        'fieldColumnMap.purpose' => 'Felhasználási mód'
    ];

    public function updatedShowImportModal($value) { if (!$value) $this->reset(); }

    public function toggleModal() {
        $this->showImportModal = ! $this->showImportModal;
        if (!$this->showImportModal)
            $this->reset();
    }

    public function updatingUpload($value) {

        Validator::make(
            ['upload' => $value],
            ['upload' => 'required|mimes:txt,csv|max:10'], [
                'required' => 'A(z) :attribute mező megadása kötelező.',
                'mimes' => 'Csak .txt vagy .csv állomány feltöltése lehetséges.',
                'max' => 'Az állomány maximális mérete 10k.'
            ]
        )->validate();
    }

    public function updatedUpload() {

        $this->columns = (new CablesImport())
                            ->import($this->upload->getRealPath());

        $this->guessWhichColumnsMapToWhichFields();

    }

    public function import() {

        $this->validate($this->rules, $this->messages, $this->attributes);

        $cablesImport = new CablesImport();

        try {

            $cablesImport->setFieldColumnMap($this->fieldColumnMap);
            Excel::import($cablesImport, $this->upload->getRealPath());

            $this->toggleModal();

            $this->emitUp('showEmittedFlashMessage', $cablesImport->getRowNumber().' db. kábel betöltése sikerült!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $this->importFailures = $e->failures();
        }

    }

    public function guessWhichColumnsMapToWhichFields() {

        $guesses = [
            'full_name' => ['teljes_nev', 'teljesnev', 'Teljes név', 'Teljes nev'],
            'startCD' => ['startCD', 'kezdo_eszkoz', 'Kezdő kapcsolati eszköz'],
            'start' => ['start', 'Kezdőpont', 'kezdopont'],
            'endCD' => ['endCD', 'vegzodo_eszkoz', 'Kezdő kapcsolati eszköz'],
            'end' => ['end', 'Végpont', 'vegpont'],
            'i_time' => ['i_time', 'telepites_datuma', 'Telepítés dátuma', 'Telepítés időpontja'],
            'status' => ['status', 'allapot', 'Állapot', 'Kábelpár státusza', 'Státusz'],
            'purpose' => ['purpose', 'felhasznalas', 'Felhasználási mód', 'Mód']
        ];

        foreach ($this->columns[0][0] as $column) {
            $match = collect($guesses)->search(fn($options) => in_array(strtolower($column), $options));
            if ($match) $this->fieldColumnMap[$match] = $column;
        }

    }

}
