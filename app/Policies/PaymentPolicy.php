<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['president', 'treasurer']);
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->hasRole(['president', 'treasurer'])) {
            return true;
        }

        return $user->member && $user->member->id === $payment->member_id;
    }

    public function create(User $user): bool
    {
        return $user->isTreasurer();
    }

    public function update(User $user, Payment $payment): bool
    {
        return false; // Payments are immutable financial records
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false; // Payments cannot be arbitrarily deleted
    }
}
