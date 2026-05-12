<?php

namespace App\Policies;

use App\Models\Language;
use App\Models\User;

class LanguagePolicy
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

    public function view(User $user, Language $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Language $model): bool
    {
        return true;
    }

    public function delete(User $user, Language $model): bool
    {
        return true;
    }

    public function restore(User $user, Language $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, Language $model): bool
    {
        return true;
    }
}
