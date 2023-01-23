<?php

namespace App\Exports;

use App\Models\Cable;
use Maatwebsite\Excel\Concerns\FromCollection;

class CablesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cable::all();
    }
}
