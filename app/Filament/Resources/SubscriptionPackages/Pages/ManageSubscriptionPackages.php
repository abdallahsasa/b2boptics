<?php

namespace App\Filament\Resources\SubscriptionPackages\Pages;

use App\Filament\Resources\SubscriptionPackages\SubscriptionPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSubscriptionPackages extends ManageRecords
{
    protected static string $resource = SubscriptionPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Subscription Package'),
        ];
    }
}

