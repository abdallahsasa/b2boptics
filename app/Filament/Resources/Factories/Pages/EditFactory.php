<?php

namespace App\Filament\Resources\Factories\Pages;

use App\Filament\Resources\Factories\FactoryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

class EditFactory extends EditRecord
{
    use Translatable;

    protected static string $resource = FactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            Action::make('approve')
                ->label('Approve & Publish')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'approved')
                ->requiresConfirmation()
                ->modalHeading('Approve & Publish Supplier')
                ->modalDescription(fn () => "Are you sure you want to approve \"{$this->record->official_name}\"? This will publish their profile to the live directory and send them a publication confirmation email.")
                ->modalSubmitActionLabel('Yes, Approve & Publish')
                ->action(function () {
                    $this->record->status = 'approved';
                    $this->record->approved_at = now();
                    if (auth()->check()) {
                        $this->record->approved_by = auth()->id();
                    }
                    $this->record->save();

                    Notification::make()
                        ->title('Supplier approved and published!')
                        ->body('Publication confirmation email has been sent.')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'approved_at', 'approved_by']);
                }),
            DeleteAction::make(),
        ];
    }
}
