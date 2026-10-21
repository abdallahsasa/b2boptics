<?php

namespace App\Filament\Factory\Resources\TenderResource\Pages;

use App\Filament\Factory\Resources\TenderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;

class ViewTender extends ViewRecord
{
    protected static string $resource = TenderResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('Tender Details'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('Title'))
                            ->weight('bold')
                            ->columnSpanFull(),
                        TextEntry::make('category.name')
                            ->label(__('Category')),
                        TextEntry::make('quantity')
                            ->label(__('Quantity Required')),
                        TextEntry::make('target_price')
                            ->money(fn ($record) => $record->currency ?? 'USD')
                            ->label(__('Target Price')),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label(__('Posted On')),
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->label(__('Detailed Requirements')),
                    ]),
            ]);
    }
}
