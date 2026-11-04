<?php

namespace App\Filament\Factory\Pages\Auth;

use App\Mail\WelcomeUserMail;
use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterFactory extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration($data);

        // Assign the factory_owner role automatically
        $user->assignRole('factory_owner');

        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user, 'factory'));
        } catch (\Throwable $e) {
            Log::error('Failed to send factory welcome email: ' . $e->getMessage());
        }

        return $user;
    }
}
