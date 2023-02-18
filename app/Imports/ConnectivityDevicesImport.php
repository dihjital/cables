<?php

namespace App\Imports;

use App\Models\ConnectivityDevice;
use App\Models\ConnectivityDeviceType;
use App\Models\Location;
use App\Models\LocationZone;
use App\Models\Owner;
use App\Models\Zone;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class ConnectivityDevicesImport implements ToModel, WithValidation, WithHeadingRow
{

    use RemembersRowNumber;

    protected array $fieldColumnMap = [];

    public function __construct() {
        //
    }

    public function setFieldColumnMap(array $fieldColumnMap = []) {
        $this->fieldColumnMap = $fieldColumnMap;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ConnectivityDevice($this->prepareAttributes($row));
    }

    private function prepareAttributes(array $row): ?array {

        // Mandatory fields
        $full_name = $row[$this->fieldColumnMap['full_name']];
        $start = $row[$this->fieldColumnMap['start']];
        $end = $row[$this->fieldColumnMap['end']];

        // Split full_name into chunks e.g. 1F/152-G02 => G02, 1F and 152 respectively
        list($cd_name, $zone_name, $location_name) = $this->splitFullName($full_name);

        $zone_id = Zone::firstWhere('name', $zone_name)->id;
        $location_id = Location::firstWhere('name', $location_name)->id;

        // Optional fields
        if (!empty($this->fieldColumnMap['connectivity_device_type'])) {
            $cd_type_name = $row[$this->fieldColumnMap['connectivity_device_type']];
            $cd_type_id = ConnectivityDeviceType::firstWhere('name', $cd_type_name)->id;
        }

        if (!empty($this->fieldColumnMap['owner'])) {
            $owner_name = $row[$this->fieldColumnMap['owner']];
            $owner_id = Owner::firstWhere('name', $owner_name)->id;
        }

        return [
            'name' => $cd_name,
            'zone_id' => $zone_id,
            'location_id' => $location_id,
            'start' => $start,
            'end' => $end,
            'owner_id' => $owner_id ?? 1,
            'connectivity_device_type_id' => $cd_type_id ?? 1
        ];

    }

    /**
     * @param string $full_name
     *
     * @return array [cd_name, zone_name, location_name]
     */
    private function splitFullName(?string $full_name): ?array {
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

        return [
            $this->fieldColumnMap['full_name'] => function($attribute, $value, $onFailure) {
                list($cd_name, $zone_name, $location_name) = $this->splitFullName($value);
                if (ConnectivityDevice::where('name', $cd_name)
                        ->withTrashed()
                        ->get()
                        ->filter(function ($item) use ($value) {
                            return $item->full_name === $value;
                        })->count())
                        $onFailure('A kapcsolati eszköz már létezik');
                if (!Zone::firstWhere('name', $zone_name) ||
                    !Location::firstWhere('name', $location_name) ||
                    !LocationZone::query()
                        ->where('location_id', Location::firstWhere('name', $location_name)->id)
                        ->where('zone_id', Zone::firstWhere('name', $zone_name)->id)->count()) {
                        $onFailure('Nincsen ilyen zóna vagy lokáció vagy nincsenek összerendelve!');
                }
            },
            $this->fieldColumnMap['owner'] => Rule::exists('owners', 'name'),
            $this->fieldColumnMap['connectivity_device_type'] => Rule::exists('connectivity_device_types', 'name')
        ];
    }

    public function import(string $file_path): ?array {
        return (new HeadingRowImport)->toArray($file_path);
    }

}
