<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('Personal Access Token')->accessToken;

            Log::channel('auth')->info("Register info: User id:{$user->id} created");
            DB::commit();

            return response()->json(['token' => $token], 200);

        } catch (\Exception $e) {
            Log::channel('auth')->error("Register error: {$e}");
            DB::rollBack();

            return response()->json(['error' => 'User not registered'], 500);

        }
    }

    public function updateProfile(UserRegisterRequest $request)
    {
        // TODO: in progress
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            DB::commit();

            return response()->json(['message' => 'Profile updated successfully'], 200);

        } catch () {

        }
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Personal Access Token')->accessToken;

                Log::channel('auth')->info("Login info: User id:{$user->id} logged in");

                return response()->json(['token' => $token], 200);

            } else {
                Log::channel('auth')->warning("Login warning: Failed login attempt for email:{$request->email}");
                return response()->json(['error' => 'Unauthorized'], 401);

            }
        } catch (\Exception $e) {
            Log::channel('auth')->error("Login error: {$e}");
            return response()->json(['error' => 'An error occurred while trying to log in'], 500);

        }
    }
}
