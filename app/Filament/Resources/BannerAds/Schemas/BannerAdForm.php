<?php

namespace App\Filament\Resources\BannerAds\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerAdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Banner Content')
                    ->columns(2)
                    ->schema([
                        Select::make('page')
                            ->label('Placement Page')
                            ->options([
                                'products' => 'Products Listing Page',
                                'factories' => 'Factory Directory Page',
                                'both' => 'Both (Products & Factories)',
                            ])
                            ->default('products')
                            ->required(),
                        TextInput::make('badge')
                            ->label('Sponsored Badge / Eyebrow')
                            ->placeholder('e.g. Sponsored Showcase'),
                        TextInput::make('title')
                            ->label('Headline')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('subtitle')
                            ->label('Subtitle / Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Call to Action')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Banner Background Image')
                            ->image()
                            ->disk('public')
                            ->directory('banner-ads')
                            ->columnSpanFull(),
                        TextInput::make('button_text')
                            ->label('CTA Button Text')
                            ->placeholder('e.g. Explore Collection'),
                        TextInput::make('button_url')
                            ->label('CTA Button URL')
                            ->placeholder('e.g. /find-deal or https://example.com'),
                    ]),

                Section::make('Display & Schedule')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                        DateTimePicker::make('starts_at')
                            ->label('Starts At (Optional)'),
                        DateTimePicker::make('ends_at')
                            ->label('Ends At (Optional)'),
                    ]),
            ]);
    }
}

