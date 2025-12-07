<?php

namespace App\Policies;

use App\Models\Ascent;
use App\Models\User;

class AscentPolicy
{
    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Ascent $ascent): bool
    {
        return $user->id === $ascent->user_id;
    }

    public function delete(User $user, Ascent $ascent): bool
    {
        return $user->id === $ascent->user_id;
    }
}
