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
                Section::make('Tender Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->weight('bold')
                            ->columnSpanFull(),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('quantity')
                            ->label('Quantity Required'),
                        TextEntry::make('target_price')
                            ->money(fn ($record) => $record->currency ?? 'USD')
                            ->label('Target Price'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Posted On'),
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->label('Detailed Requirements'),
                    ]),
            ]);
    }
}
