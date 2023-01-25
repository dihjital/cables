<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index () {

        Gate::authorize('manage-users');

        return view('admin.users.index');

    }
}
