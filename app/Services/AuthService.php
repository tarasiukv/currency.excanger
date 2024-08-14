<?php

namespace App\Services;

use App\Http\Requests\ExchangeRateRequest;
use App\Http\Requests\PasswordRequest;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\Currency;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function changePassword(array $data)
    {
        $user = auth()->user();
        if (Hash::check($data['password'], $user->password)) {
            Log::channel('auth')->error("Change password warning: Old password entered");

            return response()->json(['message' => 'Old password entered.'], 500);
        }

        $new_password = Hash::make($data['password']);

        return $this->userRepository->changePassword($user->id, $new_password);
    }
}
