<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

    
        $data['personal_image'] = $request->file('personal_image')->store('users/personal', 'public');
        $data['identity_image'] = $request->file('identity_image')->store('users/identity', 'public');

        $user = User::create([
            'phone'           => $data['phone'],
            'password'        => Hash::make($data['password']),
            'role'            => $data['role'],
            'approval_status' => 'pending',
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'birth_date'      => $data['birth_date'],
            'personal_image'  => $data['personal_image'],
            'identity_image'  => $data['identity_image'],
        ]);

        return response()->json([
            'message' => 'Registration submitted. Awaiting admin approval.',
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'role' => $user->role,
                'approval_status' => $user->approval_status,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $data['phone'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->role !== 'admin' && $user->approval_status !== 'approved') {
            return response()->json([
                'message' => 'Account not approved yet',
                'approval_status' => $user->approval_status,
            ], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'role' => $user->role,
                'approval_status' => $user->approval_status,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}