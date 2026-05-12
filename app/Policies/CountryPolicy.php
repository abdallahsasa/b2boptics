<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;

class CountryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Country $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Country $model): bool
    {
        return true;
    }

    public function delete(User $user, Country $model): bool
    {
        return true;
    }

    public function restore(User $user, Country $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, Country $model): bool
    {
        return true;
    }
}
