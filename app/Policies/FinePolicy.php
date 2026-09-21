<?php

namespace App\Policies;

use App\Models\Fine;
use App\Models\User;

class FinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['president', 'secretary', 'treasurer']);
    }

    public function view(User $user, Fine $fine): bool
    {
        if ($user->hasRole(['president', 'secretary', 'treasurer'])) {
            return true;
        }

        return $user->member && $user->member->id === $fine->member_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['president', 'treasurer', 'secretary']);
    }

    public function double(User $user, Fine $fine): bool
    {
        return $user->hasRole(['president', 'treasurer']) && $fine->fine_type === 'spot';
    }
}
