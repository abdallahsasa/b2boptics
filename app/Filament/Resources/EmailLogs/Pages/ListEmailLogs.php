<?php

namespace App\Filament\Resources\EmailLogs\Pages;

use App\Filament\Resources\EmailLogs\EmailLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListEmailLogs extends ListRecords
{
    protected static string $resource = EmailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send_email')
                ->label('Send Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->modalHeading('Send Email to Users or Suppliers')
                ->modalSubmitActionLabel('Send Now')
                ->modalWidth('2xl')
                ->form(EmailLogResource::sendEmailFormSchema())
                ->action(fn (array $data) => EmailLogResource::handleSendEmail($data)),
        ];
    }
}
