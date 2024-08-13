<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Traits\HandlesOAuthRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use HandlesOAuthRequests;

    /**
     * Register user
     *
     * @param UserRegisterRequest $request
     */
    public function register(UserRegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $response = $this->sendOAuthRequest('password', [
                'username' => $user->email,
                'password' => $request->password,
            ]);

            if (!$response->successful()) {
                Log::channel('auth')->error("Register error: {$response->json()}");
                DB::rollBack();

                return response()->json(['error' => 'User registration failed.'], 500);
            }

            Log::channel('auth')->info("Register info: User id:{$user->id} created.");
            DB::commit();

            return response()->json([
                'user' => $user,
                'token' => $response->json()
            ], 201);

        } catch (\Exception $e) {

            Log::channel('auth')->error("Register error: {$e->getMessage()}");
            DB::rollBack();

            return response()->json(['error' => 'An error occurred during registration.'], 500);
        }

        //try {
//            DB::beginTransaction();
//
//            $user = User::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'password' => bcrypt($request->password),
//            ]);
//
//            $token = $user->createToken('Personal Access Token')->accessToken;
//
//            Log::channel('auth')->info("Register info: User id:{$user->id} created");
//            DB::commit();
//
//            return response()->json(['token' => $token], 200);
//
//        } catch (\Exception $e) {
//            Log::channel('auth')->error("Register error: {$e}");
//            DB::rollBack();
//
//            return response()->json(['error' => 'User not registered'], 500);
//
//        }
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $response = $this->sendOAuthRequest('password', [
                    'username' => $user->email,
                    'password' => $request->password,
                ]);

                Log::channel('auth')->info("Login info: User id:{$user->id} logged in");

                return $response->json();
            } else {
                Log::channel('auth')->warning("Login warning: Failed login attempt for email:{$request->email}");

                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            Log::channel('auth')->error("Login error: {$e}");

            return response()->json(['error' => 'An error occurred while trying to log in'], 500);
        }
    }

    /**
     * User personal info
     *
     */
    public function personalInfo()
    {
        try {
            $user = Auth::user();

            Log::channel('auth')->info("PersonalInfo info: User id:{$user->id}");

            return $user;
        } catch (\Exception $e) {
            Log::channel('auth')->error("PersonalInfo error: {$e}");

            return response()->json(['error' => 'An error occurred while trying to PersonalInfo'], 500);
        }
    }

    public function changePassword(PasswordRequest $request)
    {
        dd($request);
        $user = Auth::user();
        $user->update($request->all());
        return $user->refresh();
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();

            if ($user && $user->token()) {

                $user->token()->revoke();

                Log::channel('auth')->info("Logout info: User id:{$user->id} logged out");

                return response()->json(['message' => 'Successfully logged out'], 200);
            } else {
                Log::channel('auth')->warning("Logout warning: Attempt to logout without valid token");

                return response()->json(['error' => 'No valid token found'], 400);
            }
        } catch (\Exception $e) {
            Log::channel('auth')->error("Logout error: {$e}");

            return response()->json(['error' => 'An error occurred while trying to log out'], 500);
        }
    }
}
