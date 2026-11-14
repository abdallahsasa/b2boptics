<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EmailLogs\EmailLogResource;
use App\Models\EmailLog;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Widget;

class EmailCenterWidget extends Widget implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected string $view = 'filament.widgets.email-center-widget';

    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    public function getStats(): array
    {
        EmailLog::ensureTableExists();

        return [
            'sent' => EmailLog::where('status', 'sent')->count(),
            'today' => EmailLog::where('status', 'sent')->whereDate('created_at', today())->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
        ];
    }

    public function getLogsUrl(): string
    {
        return EmailLogResource::getUrl('index');
    }

    public function sendEmailAction(): Action
    {
        return Action::make('sendEmail')
            ->label('Send Email')
            ->icon('heroicon-o-paper-airplane')
            ->color('primary')
            ->modalHeading('Send Email to Users or Suppliers')
            ->modalSubmitActionLabel('Send Now')
            ->modalWidth('2xl')
            ->form(EmailLogResource::sendEmailFormSchema())
            ->action(fn (array $data) => EmailLogResource::handleSendEmail($data));
    }
}
