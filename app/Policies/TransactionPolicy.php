<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Checking if a user can view a specific transaction.
     *
     * @param User $user
     * @param Transaction $transaction
     * @return bool
     */
    public function view(User $user, Transaction $transaction)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $transaction->user_id === $user->id;
    }

    /**
     * Checking if the user can view all transactions.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }
}
