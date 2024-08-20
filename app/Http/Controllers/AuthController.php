<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Jobs\SendVerificationCodeJob;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Traits\HandlesOAuthRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MailService;

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

        try {
            $user = $this->userRepository->create($request);

            if (!$user) {
                Log::channel('auth')->error('Register: User creation failed.');
                return response()->json(['error' => 'User registration failed.'], 500);
            }

            DB::beginTransaction();

            $response = $this->sendOAuthRequest('password', [
                'username' => $user->email,
                'password' => $request->password,
            ]);

            if (!$response->successful()) {
                Log::channel('auth')->error("Register: {$response->json()}");
                DB::rollBack();

                return response()->json(['error' => 'User registration failed.'], 500);
            }

            SendVerificationCodeJob::dispatch('tarasiuk.viktor.m@gmail.com', $user->verification_code)->onQueue('mail');

            Log::channel('auth')->info("Register: User id:{$user->id} created.");
            DB::commit();

            return response()->json([
                'user' => $user,
                'token' => $response->json()
            ], 201);

        } catch (\Exception $e) {
            Log::channel('auth')->error("Register: {$e->getMessage()}");

            return response()->json(['error' => 'An error occurred during registration.'], 500);
        }
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed
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

                Log::channel('auth')->info("Login: User id:{$user->id} logged in");

                return $response->json();
            } else {
                Log::channel('auth')->warning("Login: Failed login attempt for email:{$request->email}");

                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            Log::channel('auth')->error("Login: {$e}");

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

            Log::channel('auth')->info("PersonalInfo: User id:{$user->id}");

            return response()->json($user);
        } catch (\Exception $e) {
            Log::channel('auth')->error("PersonalInfo: {$e}");

            return response()->json(['error' => 'An error occurred while trying to PersonalInfo'], 500);
        }
    }

    public function verify(Request $request)
    {
        try {
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

            Log::channel('auth')->info("Verify: User id:{$user->id} verified");

            return response()->json(['message' => 'Email verified successfully.'], 200);
        } catch (\Exception $e) {
            Log::channel('auth')->error("Verify: {$e->getMessage()}");

            return response()->json(['error' => 'An error occurred during verification.'], 500);
        }
    }

    /**
     * Change user password
     *
     * @param PasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(PasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authService->changePassword($request->all());

            Log::channel('auth')->info("ChangePassword: User id:" . Auth::id() . " password changed");

            return response()->json(['message' => 'Password successfully changed'], 200);
        } catch (\Exception $e) {
            Log::channel('auth')->error("ChangePassword: {$e->getMessage()}");

            return response()->json(['error' => 'An error occurred while changing the password.'], 500);
        }
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

                Log::channel('auth')->info("Logout: User id:{$user->id} logged out");

                return response()->json(['message' => 'Successfully logged out'], 200);

            } else {
                Log::channel('auth')->warning("Logout: Attempt to logout without valid token");

                return response()->json(['error' => 'No valid token found'], 400);
            }
        } catch (\Exception $e) {
            Log::channel('auth')->error("Logout: {$e}");

            return response()->json(['error' => 'An error occurred while trying to log out'], 500);
        }
    }
}
