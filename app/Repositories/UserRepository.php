<?php

namespace App\Repositories;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PharIo\Version\Exception;
use Symfony\Component\HttpFoundation\Response;

class UserRepository
{
    /**
     * Create user
     *
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return $user;
    }

    /**
     * Change user password
     *
     * @param $user_id
     * @param $new_password
     * @return mixed
     */
    public function changePassword($user_id, $new_password)
    {
        $user = User::findOrFail($user_id);
        $user->password = $new_password;
        $user->save();

        return $user;
    }
}
