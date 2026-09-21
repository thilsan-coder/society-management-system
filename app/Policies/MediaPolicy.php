<?php

namespace App\Policies;

use App\Models\PosterAndMedia;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PosterAndMedia $media): bool
    {
        if ($user->hasRole(['president', 'media'])) {
            return true;
        }

        return in_array($media->status, ['approved', 'published']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['president', 'media']);
    }

    public function update(User $user, PosterAndMedia $media): bool
    {
        return $user->hasRole(['president', 'media']) && in_array($media->status, ['draft', 'rejected']);
    }

    public function approve(User $user, PosterAndMedia $media): bool
    {
        return $user->isPresident();
    }
}
