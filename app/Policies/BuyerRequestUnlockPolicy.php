<?php

namespace App\Policies;

use App\Models\BuyerRequestUnlock;
use App\Models\User;

class BuyerRequestUnlockPolicy
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

    public function view(User $user, BuyerRequestUnlock $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BuyerRequestUnlock $model): bool
    {
        return true;
    }

    public function delete(User $user, BuyerRequestUnlock $model): bool
    {
        return true;
    }

    public function restore(User $user, BuyerRequestUnlock $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, BuyerRequestUnlock $model): bool
    {
        return true;
    }
}
