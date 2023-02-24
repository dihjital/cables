<?php

namespace App\Http\Controllers;

use App\Models\Zone;

class AdminZoneController extends Controller
{

    public function index() { return view('admin.zones.index'); }

    public function create () { return view('admin.zones.create');}

    public function edit (Zone $zone) {
        return view('admin.zones.edit', [
            'zone' => $zone
        ]);
    }

}
