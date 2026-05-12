<?php

namespace App\Filament\Resources\StockOffers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockOffersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('factory.official_name')
                    ->label('Factory')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('quantity'),
                TextColumn::make('price')
                    ->money(fn ($record) => $record->currency),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'sold_out' => 'danger',
                        default => 'gray',
                    }),
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
