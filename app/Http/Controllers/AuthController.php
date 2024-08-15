<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Traits\HandlesOAuthRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use HandlesOAuthRequests;

    public $authService;
    public $userRepository;

    public function __construct(AuthService $authService, UserRepository $userRepository)
    {
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    /**
     * Register user
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->create($request);

            $response = $this->sendOAuthRequest('password', [
                'username' => $user->email,
                'password' => $request->password,
            ]);

            if (!$response->successful()) {
                Log::channel('auth')->error("Register error: {$response->json()}");
                DB::rollBack();

                return response()->json(['error' => 'User registration failed.'], 500);
            }

            Mail::raw("Your verification code is: {$user->verification_code}", function($message) use ($user) {
                $message->to($user->email)
                    ->subject('Email Verification');
            });

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
    }

    /**
     * Login user
     *
     * @param Request $request
     */
    public function login(Request $request)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function personalInfo(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();

            Log::channel('auth')->info("PersonalInfo info: User id:{$user->id}");

            return response()->json($user);
        } catch (\Exception $e) {
            Log::channel('auth')->error("PersonalInfo error: {$e}");

            return response()->json(['error' => 'An error occurred while trying to PersonalInfo'], 500);
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $request->email)->where('verification_code', $request->verification_code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code or email.'], 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

    /**
     * Change user password
     *
     * @param PasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(PasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->authService->changePassword($request->all());

        return response()->json(['message' => 'Password successfully changed'], 200);
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
