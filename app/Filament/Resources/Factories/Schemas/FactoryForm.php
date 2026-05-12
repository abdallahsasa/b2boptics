<?php

namespace App\Filament\Resources\Factories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class FactoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('owner', 'name')
                            ->required()
                            ->label('Owner'),
                        TextInput::make('official_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tax_number')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Select::make('country_id')
                            ->relationship('country', 'name')
                            ->searchable(),
                        Select::make('language')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                                'tr' => 'Turkish',
                                'zh' => 'Chinese',
                                'ru' => 'Russian',
                                'de' => 'German',
                                'es' => 'Spanish',
                                'fr' => 'French',
                                'it' => 'Italian',
                            ])
                            ->default('en'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Main Category'),
                    ]),
                
                Section::make('Branding & Description')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('factory_logos')
                            ->image(),
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->collection('factory_banners')
                            ->image(),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Verification')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'suspended' => 'Suspended',
                            ])
                            ->default('pending')
                            ->required(),
                        Toggle::make('is_verified')
                            ->label('Verified Factory'),
                    ]),
            ]);
    }
}
