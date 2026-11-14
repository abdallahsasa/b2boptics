<?php

namespace App\Filament\Resources\Factories\Tables;

use App\Models\Factory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FactoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('official_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business_type')
                    ->label('Type')
                    ->state(fn ($record) => $record->businessTypeKey())
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Brand' => 'info',
                        'Wholesaler' => 'warning',
                        default => 'primary',
                    }),
                TextColumn::make('tax_number')
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('country.name')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('business_type')
                    ->label('Business Type')
                    ->options(\App\Models\Factory::businessTypeOptions()),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ]),
                SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->label('Country'),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve & Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Factory $record) => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->modalHeading('Approve & Publish Supplier')
                    ->modalDescription(fn (Factory $record) => "Are you sure you want to approve \"{$record->official_name}\"? This will publish their showroom to the live website and immediately send a publication email to " . ($record->email ?: $record->owner?->email) . '.')
                    ->modalSubmitActionLabel('Yes, Approve & Publish')
                    ->action(function (Factory $record) {
                        $record->status = 'approved';
                        $record->approved_at = now();
                        if (auth()->check()) {
                            $record->approved_by = auth()->id();
                        }
                        $record->save();

                        Notification::make()
                            ->title('Supplier approved and published!')
                            ->body('Publication confirmation email has been sent.')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
