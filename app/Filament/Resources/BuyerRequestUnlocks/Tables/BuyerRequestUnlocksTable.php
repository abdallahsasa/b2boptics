<?php

namespace App\Filament\Resources\BuyerRequestUnlocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BuyerRequestUnlocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('buyerRequest.title')
                    ->label('Request')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('factory.official_name')
                    ->label('Factory')
                    ->sortable(),
                TextColumn::make('unlockedBy.name')
                    ->label('Unlocked By')
                    ->sortable(),
                TextColumn::make('unlock_method')
                    ->badge(),
                TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unlocked' => 'success',
                        'pending' => 'warning',
                        'refunded' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'unlocked' => 'Unlocked',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('unlock_method')
                    ->options([
                        'manual' => 'Manual',
                        'credit' => 'Credit',
                        'payment' => 'Payment',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
