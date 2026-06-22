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
    protected static string|UnitEnum|null $navigationGroup = "Opportunities";
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Open Tenders';
    protected static ?string $modelLabel = 'Tender';
    protected static ?string $pluralModelLabel = 'Open Tenders';

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
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('target_price')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->label('Target Price')
                    ->sortable(),
                TextColumn::make('country.name')
                    ->label('Buyer Country')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Posted'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
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
