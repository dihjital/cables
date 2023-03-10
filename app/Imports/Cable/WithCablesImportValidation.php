<?php

namespace App\Imports\Cable;

use App\Models\Cable;
use App\Models\CablePair;
use App\Models\ConnectivityDevice;
use App\Models\Location;
use App\Models\LocationZone;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait WithCablesImportValidation {

    /**
     * @param string $full_name
     *
     * @return array [cableType, cableName]
     */
    protected function splitFullName(?string $full_name): ?array {
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
    protected function splitCDFullName(?string $full_name): ?array {
        return [
            substr($full_name, -3),
            substr($full_name, 0, 2),
            substr($full_name, 3, 3)
        ];
    }

    protected function isConnectivityDeviceExists(?string $shortName = '', ?string $fullName = ''): bool {
        if (!$shortName || !$fullName) return false;
        return ConnectivityDevice::where('name', $shortName)
            ->get()
            ->filter(function ($item) use ($fullName) {
                return $item->full_name === $fullName;
            })->count();
    }

    /**
     * @param string $connectionPoint
     * @param string $connectivityDevice
     *
     * @return bool true if connectionPoint fall into the connectivityDevice range, false otherwise
     */
    protected function validateConnectionPoint (?string $connectivityDevice = '', ?string $connectionPoint = ''): bool {
        if (!$connectionPoint) return true;
        if (!$connectivityDevice) return false;
        return in_array($connectionPoint,
            ConnectivityDevice::query()
                ->getByFullName($connectivityDevice)
                ->first()
                ?->calculateCdRange() ?? []);
    }

    /**
     * @param string $connectionPoint
     * @param string $connectivityDevice
     *
     * @return bool true if connectivityDevice has at least 1 In use cable pair on the given connectionPoint
     * @return bool false if connectivityDevice does not have an In use cable pair on the given connectionPoint
     */
    protected function numberOfActiveConnectionPoints (?string $connectivityDevice = '', ?string $connectionPoint = ''): bool {
        if (!$connectionPoint) return false;
        if (!$connectivityDevice) return true;
        return CablePair::query()
            ->where('conn_dev_id', ConnectivityDevice::query()
                ->getByFullName($connectivityDevice)->get()->first()?->id)
            ->where('conn_point', $connectionPoint)
            ->where('cable_pair_status_id', 2) // 2 = In use
            ->get()
            ->count();
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            $rowNumber = 2; // 1. row is the header row

            foreach ($validator->getData() as $row) {

                // We only check these additional rules if the cable pair status is in use ...
                // In case of status === 'Spare' validation rule exclude the connection points from the request
                if ($row[$this->fieldColumnMap['status']] === 'In use') {
                    // Start connection point
                    if (!$this->validateConnectionPoint(
                        $row[$this->fieldColumnMap['startCD']],
                        $row[$this->fieldColumnMap['start']]
                    ))
                        $validator->errors()
                            ->add("$rowNumber.".$this->fieldColumnMap['start'],
                                'Nincsen ilyen kapcsolati pontja a kapcsolati eszk??znek.');
                    // End connection point
                    if (!$this->validateConnectionPoint(
                        $row[$this->fieldColumnMap['endCD']],
                        $row[$this->fieldColumnMap['end']]
                    ))
                        $validator->errors()
                            ->add("$rowNumber.".$this->fieldColumnMap['end'],
                                'Nincsen ilyen kapcsolati pontja a kapcsolati eszk??znek.');
                    // Start connection point 'activeness' count check
                    if ($this->numberOfActiveConnectionPoints(
                        $row[$this->fieldColumnMap['startCD']],
                        $row[$this->fieldColumnMap['start']]))
                        $validator->errors()
                            ->add("$rowNumber." . $this->fieldColumnMap['start'],
                                'Ehhez a kapcsolati ponthoz m??r csatlakozik akt??v k??bel.');
                    // End connection point 'activeness' count check
                    if ($this->numberOfActiveConnectionPoints(
                        $row[$this->fieldColumnMap['endCD']],
                        $row[$this->fieldColumnMap['end']]))
                        $validator->errors()
                            ->add("$rowNumber." . $this->fieldColumnMap['end'],
                                'Ehhez a kapcsolati ponthoz m??r csatlakozik akt??v k??bel.');
                }

                $rowNumber++;
            }

        });

    }

    public function customValidationAttributes() {

        return [
            $this->fieldColumnMap['full_name']  => 'Teljes n??v',
            $this->fieldColumnMap['startCD']    => 'Kezd?? kapcsolati eszk??z',
            $this->fieldColumnMap['start']      => 'Kezd?? kapcsolati pont',
            $this->fieldColumnMap['endCD']      => 'V??gz??d?? kapcsolati eszk??z',
            $this->fieldColumnMap['end']        => 'V??gz??d?? kapcsolati pont',
            $this->fieldColumnMap['status']     => 'K??belp??r st??tusza',
            $this->fieldColumnMap['purpose']    => 'K??bel felhaszn??l??si m??dja',
            $this->fieldColumnMap['i_time']     => 'Telep??t??s d??tuma'
        ];

    }

    public function customValidationMessages() {

        return [
            "*.{$this->fieldColumnMap['full_name']}.required" => 'A(z) :attribute mez?? megad??sa k??telez??.',
            "*.{$this->fieldColumnMap['startCD']}.required" => 'A(z) :attribute mez?? megad??sa k??telez??.',
            "*.{$this->fieldColumnMap['endCD']}.required" => 'A(z) :attribute mez?? megad??sa k??telez??.',
            "*.{$this->fieldColumnMap['status']}.required" => 'A(z) :attribute mez?? megad??sa k??telez??.',
            "*.{$this->fieldColumnMap['purpose']}.required" => 'A(z) :attribute mez?? megad??sa k??telez??.',
            "*.{$this->fieldColumnMap['start']}.regex" => 'A(z) :attribute mez?? form??tuma nem megfelel??.',
            "*.{$this->fieldColumnMap['end']}.regex" => 'A(z) :attribute mez?? form??tuma nem megfelel??.',
            "*.{$this->fieldColumnMap['status']}.exists" => ':attribute nem l??tezik az adatb??zisban.',
            "*.{$this->fieldColumnMap['purpose']}.exists" => ':attribute nem l??tezik az adatb??zisban.',
            "*.{$this->fieldColumnMap['start']}.required_without" => ':attribute be??ll??t??sa k??telez??, amennyiben :values nincsen megadva.',
            "*.{$this->fieldColumnMap['end']}.required_without" => ':attribute be??ll??t??sa k??telez??, amennyiben :values nincsen megadva.',
            "*.{$this->fieldColumnMap['start']}.prohibited_if" => ':attribute nem lehet megadva, amennyiben a k??belp??r st??tusza Spare (:value)',
            "*.{$this->fieldColumnMap['end']}.prohibited_if" => ':attribute nem lehet megadva, amennyiben a k??belp??r st??tusza Spare (:value)',
            "*.{$this->fieldColumnMap['i_time']}.date_format" => 'A(z) :attribute mez?? form??tuma nem megfelel?? (:format)'
        ];

    }

    public function rules(): array {

        // TODO: rewrite to check if zone and location exists in LocationZone model
        // a full_name ellen??rz??se szint??n ??tgondol??st ig??nyel
        // azt kell megn??zni, hogy ilyen cd name zone id es location id l??tezik-e a connection devices t??bl??ban

        return [
            $this->fieldColumnMap['full_name'] => [
                'required',
                function($attribute, $value, $onFailure) {
                    list($cableType, $cableName) = $this->splitFullName($value);
                    if (Cable::where('name', $cableName)
                        ->withTrashed()
                        ->get()
                        ->filter(function ($item) use ($value) {
                            return $item->full_name === $value;
                        })->count())
                        $onFailure('A k??bel m??r l??tezik a rendszer adatb??zis??ban');
                }
            ],
            $this->fieldColumnMap['startCD'] => [
                'required',
                function($attribute, $value, $onFailure) {
                    list($cd_name, $zone_name, $location_name) = $this->splitCDFullName($value);
                    if (!$this->isConnectivityDeviceExists($cd_name, $value))
                        $onFailure('Nincsen ilyen kapcsolati eszk??z');
                    elseif (!Zone::firstWhere('name', $zone_name) ||
                            !Location::firstWhere('name', $location_name) ||
                            !LocationZone::query()
                                ->where('location_id', Location::firstWhere('name', $location_name)->id)
                                ->where('zone_id', Zone::firstWhere('name', $zone_name)->id)->count())
                        $onFailure('Nincsen ilyen z??na vagy lok??ci?? vagy nincsenek ??sszerendelve!');
                }
            ],
            $this->fieldColumnMap['start'] => [
                'nullable',
                'bail',
                "prohibited_if:*.{$this->fieldColumnMap['status']},Spare",
                "exclude_if:*.{$this->fieldColumnMap['status']},Spare",
                "required_without:*.{$this->fieldColumnMap['end']}",
                'regex:/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si'
            ],
            $this->fieldColumnMap['endCD'] => [
                'required',
                function($attribute, $value, $onFailure) {
                    list($cd_name, $zone_name, $location_name) = $this->splitCDFullName($value);
                    if (!$this->isConnectivityDeviceExists($cd_name, $value))
                        $onFailure('Nincsen ilyen kapcsolati eszk??z');
                    elseif (!Zone::firstWhere('name', $zone_name) ||
                            !Location::firstWhere('name', $location_name) ||
                            !LocationZone::query()
                                ->where('location_id', Location::firstWhere('name', $location_name)->id)
                                ->where('zone_id', Zone::firstWhere('name', $zone_name)->id)->count())
                        $onFailure('Nincsen ilyen z??na vagy lok??ci?? vagy nincsenek ??sszerendelve!');
                }
            ],
            $this->fieldColumnMap['end'] => [
                'nullable',
                'bail',
                "prohibited_if:*.{$this->fieldColumnMap['status']},Spare",
                "exclude_if:*.{$this->fieldColumnMap['status']},Spare",
                "required_without:*.{$this->fieldColumnMap['start']}",
                'regex:/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si'
            ],
            $this->fieldColumnMap['i_time'] => [
                'nullable',
                'date_format:Y M d'
            ],
            $this->fieldColumnMap['status'] => [
                'required',
                Rule::exists('cable_pair_statuses', 'name')
            ],
            $this->fieldColumnMap['purpose'] => [
                'required',
                Rule::exists('cable_purposes', 'name')
            ]
        ];

    }

}
