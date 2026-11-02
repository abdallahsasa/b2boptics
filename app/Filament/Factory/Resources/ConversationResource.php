<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Factory\Resources\ConversationResource\Pages;
use App\Models\Conversation;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return __('Opportunities');
    }

    public static function getNavigationLabel(): string
    {
        return __('Customer Messages');
    }

    public static function getModelLabel(): string
    {
        return __('Customer Message');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Customer Messages');
    }

    public static function getNavigationBadge(): ?string
    {
        $factory = auth()->user()?->factory;
        if (!$factory) {
            return null;
        }

        $count = static::getModel()::where('factory_id', $factory->id)
            ->whereIn('status', ['human_required', 'open', 'ai_active'])
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        $factory = auth()->user()?->factory;

        return parent::getEloquentQuery()
            ->where('factory_id', $factory?->id ?? 0)
            ->with(['buyer', 'product', 'messages' => fn ($q) => $q->latest()])
            ->latest('updated_at');
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
                TextColumn::make('subject')
                    ->label(__('Subject / Topic'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(45),
                TextColumn::make('buyer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->default(__('Verified Buyer')),
                TextColumn::make('buyer.email')
                    ->label(__('Customer Email'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->placeholder(__('Direct Factory Inquiry'))
                    ->limit(35),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'human_required', 'open', 'ai_active' => __('New / Needs Reply'),
                        'resolved' => __('Replied'),
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'human_required', 'open', 'ai_active' => 'warning',
                        'resolved' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->label(__('Last Activity'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'human_required' => __('Needs Reply'),
                        'resolved' => __('Replied'),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('Open & Reply')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversations::route('/'),
            'edit' => Pages\EditConversation::route('/{record}/edit'),
        ];
    }
}

