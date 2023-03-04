<?php

namespace App\Http\Controllers;

use App\Models\Cable;
use Illuminate\Http\Request;

class AdminCableController extends Controller
{

    public function index () { return view('admin.cables.index'); }

    public function create () { return view('admin.cables.create');}

    public function edit (Cable $cable) {
        return view('admin.cables.edit', [
            'cable' => $cable
        ]);
    }


}
