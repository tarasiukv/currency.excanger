<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register user
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
//        $user = User::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'password' => bcrypt($request->password),
//            ]);

        try {
            $response = Http::asForm()->post('http://127.0.0.1:8000/oauth/token', [
                'grant_type' => 'password',
                'client_id' => config('passport.grant_password_client.id'),
                'client_secret' => config('passport.grant_password_client.secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ]);
        } catch (ConnectionException $e) {
            dd('ERROR', $e);
        }

        return $response->json();


//        try {
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
     * Update user
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     */
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

            return response()->json(['message' => 'User updated successfully'], 200);

        } catch (\Exception $e) {
            Log::channel('auth')->error("Update error: {$e}");
            DB::rollBack();

            return response()->json(['error' => 'User not updated'], 500);

        }
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

    /**
     * Logout user
     *
     */
    public function logout()
    {
        $user = Auth::user();
        dd(1111111111);
        Log::channel('auth')->info("Logout info: User id:{$user->id} logged out");

        return response()->json($user->token()->revoke(), 200);
    }
}
