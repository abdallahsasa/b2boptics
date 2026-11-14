<?php

namespace App\Filament\Resources\EmailLogs;

use App\Filament\Resources\EmailLogs\Pages\ListEmailLogs;
use App\Models\EmailLog;
use App\Models\Factory;
use App\Models\User;
use App\Services\EmailService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class EmailLogResource extends Resource
{
    protected static ?string $model = EmailLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static string|UnitEnum|null $navigationGroup = 'Communications';
    protected static ?string $navigationLabel = 'Email Center & Logs';
    protected static ?string $pluralModelLabel = 'Email Logs';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getLocaleOptions(): array
    {
        return [
            'auto' => 'Auto (Recipient Preferred Language)',
            'en' => 'English (en)',
            'ar' => 'Arabic (ar)',
            'tr' => 'Turkish (tr)',
            'zh' => 'Chinese (zh)',
            'ru' => 'Russian (ru)',
            'de' => 'German (de)',
            'es' => 'Spanish (es)',
            'fr' => 'French (fr)',
            'it' => 'Italian (it)',
        ];
    }

    public static function sendEmailFormSchema(?string $defaultAudience = null, ?array $defaultUserIds = null, ?array $defaultFactoryIds = null): array
    {
        $hideAudience = $defaultAudience !== null && (!empty($defaultUserIds) || !empty($defaultFactoryIds));

        return [
            Select::make('email_type')
                ->label('Email Type')
                ->options([
                    'welcome' => 'Welcome Email',
                    'factory_approved' => 'Supplier Approved & Published Email',
                    'reminder' => 'Reminder Email',
                    'custom' => 'Custom Email',
                ])
                ->default('reminder')
                ->required()
                ->live(),

            Select::make('reminder_template')
                ->label('Reminder Template')
                ->options([
                    'complete_profile' => 'Complete Supplier Profile',
                    'add_products' => 'Add Your Products to Catalog',
                    'check_messages' => 'Check Buyer Messages & Inquiries',
                ])
                ->default('complete_profile')
                ->visible(fn (Get $get): bool => $get('email_type') === 'reminder')
                ->required(fn (Get $get): bool => $get('email_type') === 'reminder'),

            Select::make('audience')
                ->label('Recipients / Audience')
                ->options([
                    'selected_users' => 'Specific Users',
                    'selected_factories' => 'Specific Suppliers (Factories / Brands / Wholesalers)',
                    'pending_factories' => 'All Pending Suppliers',
                    'approved_factories_no_products' => 'Approved Suppliers with 0 Products',
                    'all_factories' => 'All Suppliers',
                    'all_buyers' => 'All Buyers',
                ])
                ->default($defaultAudience ?? 'selected_users')
                ->hidden($hideAudience)
                ->dehydrated()
                ->required()
                ->live(),

            Select::make('user_ids')
                ->label('Select Users')
                ->multiple()
                ->searchable()
                ->options(fn () => User::query()->orderBy('name')->limit(200)->get()->mapWithKeys(fn ($u) => [$u->id => "{$u->name} ({$u->email})"])->all())
                ->getSearchResultsUsing(fn (string $search) => User::query()
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->limit(50)
                    ->get()
                    ->mapWithKeys(fn ($u) => [$u->id => "{$u->name} ({$u->email})"])
                    ->all())
                ->default($defaultUserIds)
                ->hidden($hideAudience)
                ->dehydrated()
                ->visible(fn (Get $get): bool => $get('audience') === 'selected_users')
                ->required(fn (Get $get): bool => !$hideAudience && $get('audience') === 'selected_users'),

            Select::make('factory_ids')
                ->label('Select Suppliers')
                ->multiple()
                ->searchable()
                ->options(fn () => Factory::query()->orderByDesc('id')->limit(200)->get()->mapWithKeys(function ($f) {
                    $name = $f->getTranslation('official_name', 'en') ?: $f->official_name;
                    return [$f->id => "{$name} ({$f->email})"];
                })->all())
                ->getSearchResultsUsing(fn (string $search) => Factory::query()
                    ->where('official_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->limit(50)
                    ->get()
                    ->mapWithKeys(function ($f) {
                        $name = $f->getTranslation('official_name', 'en') ?: $f->official_name;
                        return [$f->id => "{$name} ({$f->email})"];
                    })
                    ->all())
                ->default($defaultFactoryIds)
                ->hidden($hideAudience)
                ->dehydrated()
                ->visible(fn (Get $get): bool => $get('audience') === 'selected_factories')
                ->required(fn (Get $get): bool => !$hideAudience && $get('audience') === 'selected_factories'),

            Select::make('locale')
                ->label('Email Language')
                ->options(self::getLocaleOptions())
                ->default('auto')
                ->required(),

            TextInput::make('custom_subject')
                ->label('Email Subject')
                ->maxLength(255)
                ->visible(fn (Get $get): bool => $get('email_type') === 'custom')
                ->required(fn (Get $get): bool => $get('email_type') === 'custom'),

            Textarea::make('custom_body')
                ->label('Email Message')
                ->rows(5)
                ->visible(fn (Get $get): bool => $get('email_type') === 'custom')
                ->required(fn (Get $get): bool => $get('email_type') === 'custom'),
        ];
    }

    public static function handleSendEmail(array $data): void
    {
        $result = EmailService::sendFromAdminForm($data);
        $sent = $result['sent'] ?? 0;
        $failed = $result['failed'] ?? 0;

        if ($sent > 0 && $failed === 0) {
            Notification::make()
                ->title("Successfully sent {$sent} email(s)")
                ->success()
                ->send();
        } elseif ($sent > 0 && $failed > 0) {
            Notification::make()
                ->title("Sent {$sent} email(s), {$failed} failed")
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('No emails were sent')
                ->body($failed > 0 ? "{$failed} email(s) failed to send. Check Email Logs for details." : 'No valid recipients matched your selection.')
                ->danger()
                ->send();
        }
    }

    public static function table(Table $table): Table
    {
        EmailLog::ensureTableExists();

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('recipient_name')
                    ->label('Recipient')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('recipient_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('email_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => EmailLog::typeOptions()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'welcome' => 'info',
                        'factory_approved' => 'success',
                        'reminder' => 'warning',
                        'custom' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('locale')
                    ->label('Lang')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => strtoupper($state ?: 'en'))
                    ->color('gray'),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn (EmailLog $record): ?string => $record->subject),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'sent' ? 'success' : 'danger'),
                TextColumn::make('sender.name')
                    ->label('Sent By')
                    ->default('System')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('email_type')
                    ->label('Type')
                    ->options(EmailLog::typeOptions()),
                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
                SelectFilter::make('locale')
                    ->label('Language')
                    ->options(collect(self::getLocaleOptions())->except('auto')->all()),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Email Log Details')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (EmailLog $record) => view('filament.resources.email-logs.details-modal', ['record' => $record])),

                Action::make('resend')
                    ->label('Resend')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn (EmailLog $record): bool => in_array($record->email_type, ['welcome', 'factory_approved', 'reminder'], true))
                    ->action(function (EmailLog $record): void {
                        $ok = match ($record->email_type) {
                            'welcome' => $record->user ? EmailService::sendWelcome($record->user, $record->factory ? 'factory' : null, $record->locale) : false,
                            'factory_approved' => $record->factory ? EmailService::sendFactoryApproved($record->factory, $record->locale) : false,
                            'reminder' => EmailService::sendReminder($record->user, $record->factory, 'complete_profile', $record->locale),
                            default => false,
                        };

                        if ($ok) {
                            Notification::make()->title('Email resent successfully')->success()->send();
                        } else {
                            Notification::make()->title('Failed to resend email')->danger()->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailLogs::route('/'),
        ];
    }
}
