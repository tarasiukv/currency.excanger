<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
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
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'verification_code' => Str::random(6),
            ]);

            $role = Role::find($request->user_role_id);
            if ($role) {
                $user->roles()->attach($role);
            } else {
                throw new \Exception('Role not found.');
            }

            Log::channel('auth')->info('Create: User created: ' . $user->email);

            return $user;
        } catch (Exception $e) {
            Log::channel('auth')->error('Create: ' . $e->getMessage());

            throw new Exception('An unexpected error occurred while creating the user.');
        }
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
        try {
            $user = User::findOrFail($user_id);
            $user->password = bcrypt($new_password);
            $user->save();

            return $user;
        } catch (Exception $e) {
            Log::error('Exception: ' . $e->getMessage());

            throw new Exception('An unexpected error occurred while changing the password.');
        }
    }
}
