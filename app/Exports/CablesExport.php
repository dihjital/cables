<?php

namespace App\Exports;

use App\Models\Cable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CablesExport implements FromQuery, WithMapping, WithHeadings
{

    use Exportable;

    protected ?string $full_name;
    protected ?int $cable_status;
    protected ?array $keys;

    public function __construct()
    {
        //
    }

    public function forFullName(?string $full_name) {
        $this->full_name = $full_name;
        return $this;
    }

    public function forStatus(?int $cable_status) {
        $this->cable_status = $cable_status;
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
        return Cable::query()
            ->when($this->full_name, function ($q, $search) {
                $q->whereRaw("id IN
                (SELECT cables.id
                    FROM cables
	                LEFT JOIN
	                    cable_types ON cables.cable_type_id = cable_types.id
                    WHERE
                            position('" . $search . "' IN
                            concat(cable_types.abbreviation, cables.name)))");
            })
            ->when($this->cable_status, function ($q, $search) {
                $q->whereHas('connection_points', function ($q) use ($search) {
                    $q->where('cable_pair_status_id', $search);
                });
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
            'Kezdő eszköz',
            'Kezdőpont',
            'Végződő eszköz',
            'Végpont',
            'Állapot',
            'Telepítés dátuma',
            'Típus',
            'Felhasználás'
        ];
    }

    public function map($cable): array
    {
        return [
            $cable->id,
            $cable->full_name,
            $cable->cd_start->full_name,
            $cable->start_point,
            $cable->cd_end->full_name,
            $cable->end_point,
            $cable->status,
            $cable->date_for_humans,
            $cable->cable_type->name,
            $cable->cable_purpose->name
        ];
    }

}
