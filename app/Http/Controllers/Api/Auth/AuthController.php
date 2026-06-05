<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email au password si sahihi.',
                ], 401);
            }

            $token = $user->createToken('manna-mobile')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Umefanikiwa kuingia.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => $user->role,
                    'dashboard_url' => $user->getDashboardUrl(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:15',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|in:tenant,landlord,agent',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            $token = $user->createToken('manna-mobile')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Akaunti imeundwa. Karibu Manna!',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => $user->role,
                    'dashboard_url' => $user->getDashboardUrl(),
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Umetoka kwenye mfumo.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $request->user()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:15',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->update($request->only(['name', 'phone']));

            return response()->json([
                'success' => true,
                'message' => 'Wasifu umesasishwa.',
                'data' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tuma link ya kubadilisha password kwenye email yako.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
