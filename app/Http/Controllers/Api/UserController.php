<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        return User::query()
            ->where('email', 'LIKE', "%{$request->query('email')}%")
            ->get(['id', 'name', 'email'])
            ->toArray();
    }
}
