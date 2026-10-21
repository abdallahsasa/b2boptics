<?php

namespace App\Filament\Resources\Factories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                            ->searchable()
                            ->preload()
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
                            ->searchable()
                            ->preload(),
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
                            ->searchable()
                            ->default('en'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Main Category'),
                        TextInput::make('address')
                            ->label('Factory Address / Industrial Zone')
                            ->placeholder('e.g. Situ Glasses Industrial Zone, Danyang, Jiangsu, China')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Showroom & Capabilities')
                    ->description('Showroom presentation and manufacturing capabilities')
                    ->columns(2)
                    ->schema([
                        TextInput::make('business_type')
                            ->label('Business Type')
                            ->placeholder('e.g. Manufacturer / Factory & Trading Company')
                            ->datalist([
                                'Manufacturer / Factory & Trading Company',
                                'Manufacturer / Factory',
                                'Custom OEM/ODM Lab',
                                'Trading Company',
                                'Wholesaler / Distributor',
                            ])
                            ->maxLength(255),
                        TextInput::make('member_since')
                            ->label('Year Established / Member Since')
                            ->placeholder('2017')
                            ->maxLength(255),
                        TextInput::make('video_url')
                            ->label('Factory Workshop Video URL (MP4)')
                            ->placeholder('https://domain.com/video.mp4')
                            ->helperText('Direct MP4 video URL showcased in the factory hero banner video player.')
                            ->maxLength(500),
                        TextInput::make('vr_url')
                            ->label('360 VR Virtual Tour URL')
                            ->placeholder('https://world-port.../viewVR?...')
                            ->helperText('Interactive 3D or VR tour link for buyers.')
                            ->maxLength(500),
                        TagsInput::make('main_products')
                            ->label('Main Product Lines')
                            ->placeholder('Add product (e.g. Optical Lens, Cr39 Lens, 1.56 Lens)')
                            ->helperText('Main products manufactured by this factory.')
                            ->columnSpanFull(),
                        TagsInput::make('solutions')
                            ->label('Services & Capabilities')
                            ->placeholder('Add service (e.g. OEM/ODM Service, Sample Available, Factory Tour)')
                            ->helperText('Service advantages highlighted on showroom.')
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Branding & Description')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('factories')
                            ->helperText('Recommended: 512x512 pixels (1:1 ratio).'),
                        FileUpload::make('banner')
                            ->image()
                            ->disk('public')
                            ->directory('factories')
                            ->helperText('Recommended: 1200x400 pixels or wider (3:1 ratio).'),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Verification')
                    ->columns(3)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'suspended' => 'Suspended',
                            ])
                            ->default('pending')
                            ->required()
                            ->searchable(),
                        Toggle::make('is_verified')
                            ->label('Verified Factory'),
                        TextInput::make('rating_score')
                            ->label('Showroom Rating Score')
                            ->numeric()
                            ->default(5.0),
                    ]),
            ]);
    }
}
