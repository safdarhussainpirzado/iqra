<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Board;

class BoardPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can list boards
    }

    public function view(User $user, Board $board): bool
    {
        return true; // All authenticated users can view a board
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-boards');
    }

    public function update(User $user, Board $board): bool
    {
        return $user->hasPermission('manage-boards');
    }

    public function delete(User $user, Board $board): bool
    {
        return $user->hasPermission('manage-boards');
    }
}
