<?php

namespace App\Http\Controllers;

use App\Models\Cable;
use Illuminate\Http\Request;

class AdminCableController extends Controller
{
    public function index () {
        return view('admin.cables.index');
    }
}
