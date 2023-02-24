<?php

namespace App\Imports;

use App\Models\Cable;
use App\Models\CablePair;
use App\Models\CablePairStatus;
use App\Models\CablePurpose;
use App\Models\CableType;
use App\Models\ConnectivityDevice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\Cable\WithCablesImportValidation;

class CablesImport implements WithValidation, WithHeadingRow, ToCollection {

    use WithCablesImportValidation;

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

    public function import(string $file_path): ?array {
        return (new HeadingRowImport)->toArray($file_path);
    }

}
