<?php

namespace App\Filament\Factory\Pages\Auth;

use App\Mail\WelcomeUserMail;
use App\Models\Factory;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterFactory extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getBusinessTypeFormComponent(),
                $this->getNameFormComponent()
                    ->label(__('Company / Contact Name')),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getBusinessTypeFormComponent(): Component
    {
        return Select::make('business_type')
            ->label(__('Account / Business Type'))
            ->options(Factory::businessTypeOptions())
            ->default('Factory')
            ->required()
            ->native(false);
    }

    protected function handleRegistration(array $data): Model
    {
        $businessType = $data['business_type'] ?? 'Factory';
        unset($data['business_type']);

        $user = parent::handleRegistration($data);

        // Assign the factory_owner role automatically
        $user->assignRole('factory_owner');

        session(['registered_business_type' => $businessType]);

        // Create initial supplier profile with selected business_type (Factory, Brand, or Wholesaler)
        try {
            $locale = app()->getLocale() ?: 'en';
            $factory = new Factory([
                'user_id' => $user->id,
                'business_type' => $businessType,
                'email' => $user->email,
                'language' => $locale,
                'member_since' => date('Y'),
                'status' => 'pending',
                'subscription_plan' => 'free',
                'product_limit' => 10,
                'max_images_per_product' => 10,
            ]);
            foreach (array_unique([$locale, 'en']) as $lang) {
                $factory->setTranslation('official_name', $lang, $user->name);
            }
            $factory->save();
        } catch (\Throwable $e) {
            Log::error('Failed to auto-create initial supplier profile: ' . $e->getMessage());
        }

        \App\Services\EmailService::sendWelcome($user, 'factory', $locale);

        return $user;
    }
}
