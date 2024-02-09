<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\EventCollection;
use App\Models\EntertainmentVenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected User $user
    ) {
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->user->create($request->validated());
        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Credentials do not match'
            ], 401);
        }
        $user = $this->user->where('email', $request->email)->first();

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
