<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;

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
            'verification_code' => Str::random(6),
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
