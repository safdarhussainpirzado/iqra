<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chapter;

class ChapterPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Chapter $chapter): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-chapters') || $user->hasRole('teacher');
    }

    public function update(User $user, Chapter $chapter): bool
    {
        return $user->hasPermission('manage-chapters') || $user->hasRole('teacher');
    }

    public function delete(User $user, Chapter $chapter): bool
    {
        return $user->hasPermission('manage-chapters');
    }
}
