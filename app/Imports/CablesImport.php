<?php

namespace App\Imports;

use App\Models\Cable;
use App\Models\CablePair;
use App\Models\CablePairStatus;
use App\Models\CablePurpose;
use App\Models\CableType;
use App\Models\ConnectivityDevice;
use App\Models\Location;
use App\Models\LocationZone;
use App\Models\Zone;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CablesImport implements WithValidation, WithHeadingRow, ToCollection {

    protected array $fieldColumnMap = [];
    protected int $rowsImported = 0;

    public function __construct() {
        //
    }

    public function setFieldColumnMap(array $fieldColumnMap = []) {
        $this->fieldColumnMap = $fieldColumnMap;
    }

    public function getRowNumber(): int {
        return $this->rowsImported;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //    return new Cable($this->prepareAttributes($row));
    // }

    protected function validateConnectionPoint (?string $connectivityDevice = '', ?string $connectionPoint = ''): bool {
        if (!$connectionPoint) return true;
        if (!$connectivityDevice) return false;
        return in_array($connectionPoint,
            ConnectivityDevice::query()->getByFullName($connectivityDevice)?->first()->calculateCdRange());
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            $rowNumber = 2; // 1. row is the header row

            foreach ($validator->getData() as $row) {
                if (!$this->validateConnectionPoint(
                        $row[$this->fieldColumnMap['startCD']],
                        $row[$this->fieldColumnMap['start']]
                    ))
                    $validator
                        ->errors()
                        ->add("$rowNumber.".$this->fieldColumnMap['start'],
                                'Nincsen ilyen kapcsolati pontja a kapcsolati eszköznek');
                $rowNumber++;
            }

        });

    }

    public function collection(Collection $rows) {

        foreach ($this->prepareAttributes($rows) as $row) {

            $cable = Cable::create([
                'name' => $row['name'],
                'cable_type_id' => $row['cable_type_id'],
                'start' => $row['start'],
                'end' => $row['end'],
                'i_time' => $row['i_time'],
                'patch' => $row['patch'],
                'owner_id' => $row['owner_id'],
                'cable_purpose_id' => $row['cable_purpose_id']
            ]);

            // Start point
            CablePair::create([
                'conn_dev_id' => $row['start_point']['conn_dev_id'],
                'conn_point' => $row['start_point']['conn_point'],
                'cable_id' => $cable->id,
                'cable_pair_status_id' => $row['start_point']['cable_pair_status_id']
            ]);

            // End point
            CablePair::create([
                'conn_dev_id' => $row['end_point']['conn_dev_id'],
                'conn_point' => $row['end_point']['conn_point'],
                'cable_id' => $cable->id,
                'cable_pair_status_id' => $row['end_point']['cable_pair_status_id']
            ]);

            $this->rowsImported++;

        }

    }

    private function prepareAttributes(Collection $rows): ?Collection {

        $cables = [];

        foreach ($rows as $row) {

            // Mandatory fields
            $full_name = $row[$this->fieldColumnMap['full_name']];
            $startCD = $row[$this->fieldColumnMap['startCD']];
            $start = $row[$this->fieldColumnMap['start']];
            $endCD = $row[$this->fieldColumnMap['endCD']];
            $end = $row[$this->fieldColumnMap['end']];
            $status = $row[$this->fieldColumnMap['status']];
            $purpose = $row[$this->fieldColumnMap['purpose']];

            // Split full_name into chunks e.g. C1070001 => C and 1070001 respectively
            list($cableType, $cableName) = $this->splitFullName($full_name);

            $cableType_id = CableType::firstWhere('abbreviation', $cableType)->id;

            $startCD_id = ConnectivityDevice::query()->getByFullName($startCD)->get()->pluck('id')->first();
            $endCD_id = ConnectivityDevice::query()->getByFullName($endCD)->get()->pluck('id')->first();

            $status_id = CablePairStatus::firstWhere('name', $status)->id;
            $purpose_id = CablePurpose::firstWhere('name', $purpose)->id;

            // Optional fields
            if (!empty($this->fieldColumnMap['i_time'])) {
                $i_time = \Carbon\Carbon::createFromFormat('Y M d', $row[$this->fieldColumnMap['i_time']])
                    ->toDateTimeString();
            }

            $cables[] = [
                'name' => $cableName,
                'cable_type_id' => $cableType_id,
                'start' => $startCD_id,
                'end' => $endCD_id,
                'i_time' => $i_time ?? '',
                'patch' => $patch ?? 0,
                'owner_id' => $owner_id ?? 1,
                'cable_purpose_id' => $purpose_id,
                'start_point' => [
                    'conn_dev_id' => $startCD_id,
                    'conn_point' => $start ?? '',
                    'cable_pair_status_id' => $status_id
                ],
                'end_point' => [
                    'conn_dev_id' => $endCD_id,
                    'conn_point' => $end ?? '',
                    'cable_pair_status_id' => $status_id
                ]
            ];

        }

        return collect($cables);

    }

    /**
     * @param string $full_name
     *
     * @return array [cableType, cableName]
     */
    private function splitFullName(?string $full_name): ?array {
        return [
            substr($full_name, 0, 1),
            substr($full_name, 1)
        ];
    }

    /**
     * @param string $full_name
     *
     * @return array [cd_name, zone_name, location_name]
     */
    private function splitCDFullName(?string $full_name): ?array {
        return [
            substr($full_name, -3),
            substr($full_name, 0, 2),
            substr($full_name, 3, 3)
        ];
    }

    public function rules(): array
    {
        // TODO: rewrite to check if zone and location exists in LocationZone model
        // a full_name ellenőrzése szintén átgondolást igényel
        // azt kell megnézni, hogy ilyen cd name zone id es location id létezik-e a connection devices táblában
        // azt is ellenőrizni kell, hogy ez a pont már megvan-e adva a cable_pairs táblában ...
        // ellenőrizni kell, hogy a státusz függvényében
        // spare esetében sem kezdő, sem pedig végpont nem lehet megadva
        // a többi esetben pedig meg kell, hogy legalább az egyik pont adva

        return [
            $this->fieldColumnMap['full_name'] => function($attribute, $value, $onFailure) {
                list($cableType, $cableName) = $this->splitFullName($value);
                if (Cable::where('name', $cableName)
                        ->withTrashed()
                        ->get()
                        ->filter(function ($item) use ($value) {
                            return $item->full_name === $value;
                        })->count())
                        $onFailure('A kábel már létezik a rendszer adatbázisában');
            },
            $this->fieldColumnMap['startCD'] => function($attribute, $value, $onFailure) {
                list($cd_name, $zone_name, $location_name) = $this->splitCDFullName($value);
                if (!ConnectivityDevice::where('name', $cd_name)
                    ->get()
                    ->filter(function ($item) use ($value) {
                        return $item->full_name === $value;
                    })->count())
                    $onFailure('Nincsen ilyen kapcsolati eszköz');
                if (!Zone::firstWhere('name', $zone_name) ||
                    !Location::firstWhere('name', $location_name) ||
                    !LocationZone::query()
                        ->where('location_id', Location::firstWhere('name', $location_name)->id)
                        ->where('zone_id', Zone::firstWhere('name', $zone_name)->id)->count()) {
                    $onFailure('Nincsen ilyen zóna vagy lokáció vagy nincsenek összerendelve!');
                }
            },
            $this->fieldColumnMap['start'] => [
                'nullable',
               'regex:/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si'
            ],
            $this->fieldColumnMap['endCD'] => function($attribute, $value, $onFailure) {
                list($cd_name, $zone_name, $location_name) = $this->splitCDFullName($value);
                if (!ConnectivityDevice::where('name', $cd_name)
                    ->get()
                    ->filter(function ($item) use ($value) {
                        return $item->full_name === $value;
                    })->count())
                    $onFailure('Nincsen ilyen kapcsolati eszköz');
                if (!Zone::firstWhere('name', $zone_name) ||
                    !Location::firstWhere('name', $location_name) ||
                    !LocationZone::query()
                        ->where('location_id', Location::firstWhere('name', $location_name)->id)
                        ->where('zone_id', Zone::firstWhere('name', $zone_name)->id)->count()) {
                    $onFailure('Nincsen ilyen zóna vagy lokáció vagy nincsenek összerendelve!');
                }
            },
            $this->fieldColumnMap['end'] => [
                'nullable',
                'regex:/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si'
            ],
            $this->fieldColumnMap['status'] => Rule::exists('cable_pair_statuses', 'name'),
            $this->fieldColumnMap['purpose'] => Rule::exists('cable_purposes', 'name')
        ];
    }

    public function import(string $file_path): ?array {
        return (new HeadingRowImport)->toArray($file_path);
    }

}
