<?php

namespace App\Filament\Resources\BannerAds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BannerAdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Image'),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(45),
                TextColumn::make('page')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'products' => 'Products Page',
                        'factories' => 'Factories Page',
                        'both' => 'Both Pages',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'products' => 'info',
                        'factories' => 'success',
                        'both' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('button_url')
                    ->label('Target URL')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('page')
                    ->options([
                        'products' => 'Products Listing Page',
                        'factories' => 'Factory Directory Page',
                        'both' => 'Both Pages',
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

