<?php

namespace App\Filament\Factory\Pages;

use App\Models\Category;
use App\Models\Country;
use App\Models\Factory;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class MyFactory extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static ?string $navigationLabel = 'My Factory';
    protected static ?string $title = 'My Factory Profile';
    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.factory.pages.my-factory';

    public ?array $data = [];

    public function mount(): void
    {
        $factory = auth()->user()->factory;

        if ($factory) {
            $this->form->fill([
                'official_name' => $factory->official_name,
                'contact_person_first_name' => $factory->contact_person_first_name,
                'contact_person_last_name' => $factory->contact_person_last_name,
                'phone' => $factory->phone,
                'email' => $factory->email,
                'tax_number' => $factory->tax_number,
                'country_id' => $factory->country_id,
                'category_id' => $factory->category_id,
                'description' => $factory->description,
                'logo' => $factory->logo,
                'banner' => $factory->banner,
            ]);
        } else {
            $this->form->fill([
                'email' => auth()->user()->email,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                \Filament\Schemas\Components\Section::make('Company Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('official_name')
                            ->label('Factory / Company Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tax_number')
                            ->label('Tax / Registration Number')
                            ->maxLength(255),
                        Select::make('category_id')
                            ->label('Main Product Category')
                            ->options(Category::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('country_id')
                            ->label('Country')
                            ->options(Country::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ]),

                \Filament\Schemas\Components\Section::make('Contact Person')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_person_first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('contact_person_last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Business Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),

                \Filament\Schemas\Components\Section::make('Branding & Description')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->directory('factory-logos')
                            ->label('Company Logo'),
                        FileUpload::make('banner')
                            ->image()
                            ->directory('factory-banners')
                            ->label('Cover Banner'),
                        Textarea::make('description')
                            ->label('About Your Factory')
                            ->helperText('Describe what your factory produces, your specialties, certifications, and capacity.')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $factory = auth()->user()->factory;

        if ($factory) {
            $factory->update($data);
            // Re-generate slug if name changed
            if (!empty($data['official_name'])) {
                $factory->update(['slug' => Str::slug($data['official_name'])]);
            }
        } else {
            $data['user_id'] = auth()->id();
            $data['slug'] = Str::slug($data['official_name']);
            $data['status'] = 'pending';
            Factory::create($data);
        }

        Notification::make()
            ->title('Factory profile saved successfully!')
            ->success()
            ->send();
    }
}
