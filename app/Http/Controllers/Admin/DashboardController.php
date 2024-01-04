<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:' . UserRole::Admin->value);
    }

    public function show()
    {
        return view('admin.dashboard');
    }
}
