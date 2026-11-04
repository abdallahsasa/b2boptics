<?php

namespace App\Filament\Resources\SubscriptionPackages;

use App\Filament\Resources\SubscriptionPackages\Pages\ManageSubscriptionPackages;
use App\Models\SubscriptionPackage;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionPackageResource extends Resource
{
    protected static ?string $model = SubscriptionPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return 'Subscription Packages';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Package Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Free Starter Package'),
                TextInput::make('slug')
                    ->label('Slug / Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->placeholder('e.g. free'),
                TextInput::make('price')
                    ->label('Price (0 = Free)')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->prefix('$'),
                Select::make('billing_cycle')
                    ->label('Billing Cycle')
                    ->options([
                        'forever' => 'Free / Forever',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->default('forever')
                    ->required(),
                TextInput::make('product_limit')
                    ->label('Max Product Listings')
                    ->numeric()
                    ->minValue(1)
                    ->default(10)
                    ->required(),
                TextInput::make('max_images_per_product')
                    ->label('Max Images per Product')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(30)
                    ->default(10)
                    ->required(),
                Textarea::make('description')
                    ->label('Short Description')
                    ->rows(2)
                    ->columnSpanFull(),
                TagsInput::make('features')
                    ->label('Included Package Features')
                    ->placeholder('Type a feature and press Enter')
                    ->columnSpanFull(),
                Toggle::make('is_default')
                    ->label('Default Package for New Factories')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Active / Visible')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Package')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($record) => $record->formattedPrice())
                    ->badge()
                    ->color(fn ($record) => $record->isFree() ? 'success' : 'primary'),
                TextColumn::make('product_limit')
                    ->label('Products Limit')
                    ->suffix(' products')
                    ->sortable(),
                TextColumn::make('max_images_per_product')
                    ->label('Images / Product')
                    ->suffix(' images')
                    ->sortable(),
                TextColumn::make('factories_count')
                    ->counts('factories')
                    ->label('Subscribed Factories')
                    ->badge(),
                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscriptionPackages::route('/'),
        ];
    }
}

