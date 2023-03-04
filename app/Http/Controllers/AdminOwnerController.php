<?php

namespace App\Http\Controllers;

use App\Models\Owner;

class AdminOwnerController extends Controller
{

    public function index() { return view('admin.owners.index'); }

    /*public function create () { return view('admin.zones.create');}

    public function edit (Zone $zone) {
        return view('admin.zones.edit', [
            'zone' => $zone
        ]);
    }*/

}
