<?php

namespace App\Filament\Factory\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class RegisterFactory extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration($data);

        // Assign the factory_owner role automatically
        $user->assignRole('factory_owner');

        return $user;
    }
}
