<?php

namespace App\Exports;

use App\Models\ConnectivityDevice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConnectivityDevicesExport implements FromQuery, WithMapping, WithHeadings
{

    use Exportable;

    protected ?int $cd_type;
    protected ?int $owner_id;
    protected ?string $full_name;
    protected ?array $keys;

    public function __construct()
    {
        //
    }

    public function forFullName(?string $full_name) {
        $this->full_name = $full_name;
        return $this;
    }

    public function forCDType(?int $cd_type) {
        $this->cd_type = $cd_type;
        return $this;
    }

    public function forOwner(?int $owner_id) {
        $this->owner_id = $owner_id;
        return $this;
    }

    public function forKeys(?array $keys) {
        $this->keys = $keys;
        return $this;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return ConnectivityDevice::all();
    }

    public function query()
    {
        return ConnectivityDevice::query()
            ->when($this->full_name, function ($q, $search) {
                $q->whereRaw("id IN
                    (SELECT connectivity_devices.id
                        FROM connectivity_devices
                            LEFT JOIN
                                zones ON zones.id = connectivity_devices.zone_id
                            LEFT JOIN
                                locations ON locations.id = connectivity_devices.location_id
                        WHERE
                            position('" . $search . "' IN
                            concat(zones.name,'/',locations.name,'-',connectivity_devices.name)))");
            })
            ->when($this->cd_type, function ($q, $search) {
                $q->where('connectivity_device_type_id', $search);
            })
            ->when($this->owner_id, function ($q, $search) {
                $q->where('owner_id', $search);
            })
            ->when($this->keys, function ($q, $search) {
                $q->whereKey($this->keys);
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Teljes név',
            'Kezdőpont',
            'Végpont',
            'Típus',
            'Tulajdonos',
            'Kábelek száma'
        ];
    }

    public function map($cd): array
    {
        return [
            $cd->id,
            $cd->full_name,
            $cd->start,
            $cd->end,
            $cd->connectivity_device_type->name,
            $cd->owner->name,
            $cd->cable_count
        ];
    }

}
