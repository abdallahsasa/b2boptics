<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Factory\Resources\TenderResource\Pages;
use App\Models\BuyerRequest;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;

class TenderResource extends Resource
{
    protected static ?string $model = BuyerRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Opportunities');
    }

    public static function getNavigationLabel(): string
    {
        return __('Open Tenders');
    }

    public static function getModelLabel(): string
    {
        return __('Tender');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Open Tenders');
    }

    /**
     * Only show approved/active tenders to factory owners.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'approved');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),
                TextColumn::make('target_price')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->label(__('Target Price'))
                    ->sortable(),
                TextColumn::make('country.name')
                    ->label(__('Buyer Country'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Posted')),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->label(__('Category')),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenders::route('/'),
            'view' => Pages\ViewTender::route('/{record}'),
        ];
    }
}
