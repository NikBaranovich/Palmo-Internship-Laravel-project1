<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Enums\UserRoles;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:'. UserRoles::Admin->value);
    }

    public function show()
    {
        return view('admin.dashboard');
    }
}
