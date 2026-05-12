<?php

namespace App\Policies;

use App\Models\FactoryBankDetail;
use App\Models\User;

class FactoryBankDetailPolicy
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

    public function view(User $user, FactoryBankDetail $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FactoryBankDetail $model): bool
    {
        return true;
    }

    public function delete(User $user, FactoryBankDetail $model): bool
    {
        return true;
    }

    public function restore(User $user, FactoryBankDetail $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, FactoryBankDetail $model): bool
    {
        return true;
    }
}
