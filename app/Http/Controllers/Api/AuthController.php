<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie; // <-- কুকি ব্যবহারের জন্য এটি জরুরি

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // টোকেনটি HttpOnly কুকি হিসেবে তৈরি করছি (২৪ ঘণ্টার জন্য)
        $cookie = cookie('auth_token', $token, 60 * 24, null, null, false, true, false, 'lax');

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => $user
        ], 201)->cookie($cookie); // <-- কুকিটি রেসপন্সের সাথে যুক্ত করা হলো
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // টোকেনটি HttpOnly কুকি হিসেবে তৈরি করছি
        $cookie = cookie('auth_token', $token, 60 * 24, null, null, false, true, false, 'lax');

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => $user
        ], 200)->cookie($cookie); // <-- কুকিটি রেসপন্সের সাথে যুক্ত করা হলো
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user()
        ], 200);
    }

    public function logout(Request $request)
    {
        // টোকেন ডিলিট করার পাশাপাশি কুকি ভ্যানিশ বা এক্সপায়ার করে দিচ্ছি
        $cookie = Cookie::forget('auth_token');

        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ], 200)->cookie($cookie);
    }
}