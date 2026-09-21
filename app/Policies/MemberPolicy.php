<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['president', 'secretary', 'treasurer', 'media']);
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->hasRole(['president', 'secretary', 'treasurer', 'media'])) {
            return true;
        }

        // Normal member can ONLY view their own profile
        return $user->member && $user->member->id === $member->id;
    }

    public function create(User $user): bool
    {
        return $user->isPresident();
    }

    public function update(User $user, Member $member): bool
    {
        return $user->isPresident();
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->isPresident();
    }
}
