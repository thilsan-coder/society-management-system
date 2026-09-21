<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated members can view meeting list
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['president', 'secretary']);
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $user->hasRole(['president', 'secretary']);
    }

    public function approve(User $user, Meeting $meeting): bool
    {
        return $user->isPresident();
    }
}
