<?php

namespace App\Filament\Factory\Pages;

use App\Models\Category;
use App\Models\Country;
use App\Models\Factory;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
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
    public static function getNavigationLabel(): string
    {
        return __('My Factory');
    }

    public function getTitle(): string
    {
        return __('My Factory Profile');
    }

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.factory.pages.my-factory';

    public ?array $data = [];

    public function mount(): void
    {
        $factory = auth()->user()->factory;

        if ($factory) {
            $officialName = $factory->getTranslation('official_name', app()->getLocale(), false);
            if (is_array($officialName)) {
                $officialName = $officialName[app()->getLocale()] ?? reset($officialName) ?? '';
            }

            $description = $factory->getTranslation('description', app()->getLocale(), false);
            if (is_array($description)) {
                $description = $description[app()->getLocale()] ?? reset($description) ?? '';
            }

            $this->form->fill([
                'official_name' => $officialName ?: ($factory->official_name ?? ''),
                'contact_person_first_name' => $factory->contact_person_first_name,
                'contact_person_last_name' => $factory->contact_person_last_name,
                'phone' => $factory->phone,
                'email' => $factory->email,
                'tax_number' => $factory->tax_number,
                'country_id' => $factory->country_id,
                'category_id' => $factory->category_id,
                'address' => $factory->address,
                'language' => $factory->language ?? 'en',
                'business_type' => $factory->business_type ?? 'Manufacturer / Factory & Trading Company',
                'member_since' => $factory->member_since ?? '2017',
                'video_url' => $factory->video_url,
                'vr_url' => $factory->vr_url,
                'main_products' => $factory->main_products ?? [],
                'solutions' => $factory->solutions ?? [],
                'description' => $description ?: ($factory->description ?? ''),
                'logo' => $factory->logo,
                'banner' => $factory->banner,
            ]);
        } else {
            $this->form->fill([
                'email' => auth()->user()->email,
                'business_type' => 'Manufacturer / Factory & Trading Company',
                'member_since' => date('Y'),
                'language' => app()->getLocale(),
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                \Filament\Schemas\Components\Section::make(__('Company Information'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('official_name')
                            ->label(__('Factory / Company Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tax_number')
                            ->label(__('Tax / Registration Number'))
                            ->maxLength(255),
                        Select::make('category_id')
                            ->label(__('Main Product Category'))
                            ->options(Category::all()->mapWithKeys(fn ($c) => [$c->id => $c->name])->toArray())
                            ->required()
                            ->searchable(),
                        Select::make('country_id')
                            ->label(__('Country'))
                            ->options(Country::all()->mapWithKeys(fn ($c) => [$c->id => $c->name])->toArray())
                            ->required()
                            ->searchable(),
                        TextInput::make('address')
                            ->label(__('Factory Address / Industrial Zone'))
                            ->placeholder(__('e.g. Situ Glasses Industrial Zone, Danyang, Zhenjiang, Jiangsu, China'))
                            ->maxLength(255),
                        Select::make('language')
                            ->label(__('Primary Communication Language'))
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic (العربية)',
                                'tr' => 'Turkish (Türkçe)',
                                'zh' => 'Chinese (中文)',
                                'ru' => 'Russian (Русский)',
                                'de' => 'German (Deutsch)',
                                'es' => 'Spanish (Español)',
                                'fr' => 'French (Français)',
                                'it' => 'Italian (Italiano)',
                            ])
                            ->default('en')
                            ->searchable(),
                    ]),

                \Filament\Schemas\Components\Section::make(__('Showroom & Capabilities'))
                    ->description(__('Showroom details displayed on your public factory showroom page'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('business_type')
                            ->label(__('Business Type'))
                            ->placeholder(__('e.g. Manufacturer / Factory & Trading Company'))
                            ->datalist([
                                'Manufacturer / Factory & Trading Company',
                                'Manufacturer / Factory',
                                'Custom OEM/ODM Lab',
                                'Trading Company',
                                'Wholesaler / Distributor',
                            ])
                            ->maxLength(255),
                        TextInput::make('member_since')
                            ->label(__('Year Established / Member Since'))
                            ->placeholder('2017')
                            ->maxLength(255),
                        TextInput::make('video_url')
                            ->label(__('Factory Workshop Video URL (MP4)'))
                            ->placeholder('https://domain.com/video.mp4')
                            ->helperText(__('Direct MP4 video URL showcased in the factory hero banner video player.'))
                            ->maxLength(500),
                        TextInput::make('vr_url')
                            ->label(__('360 VR Virtual Tour URL'))
                            ->placeholder('https://world-port.../viewVR?...')
                            ->helperText(__('Interactive 3D or VR tour link for buyers to explore your factory.'))
                            ->maxLength(500),
                        TagsInput::make('main_products')
                            ->label(__('Main Product Lines'))
                            ->placeholder(__('Add product (e.g. Optical Lens, Cr39 Lens, 1.56 Lens) and press enter'))
                            ->helperText(__('Keywords for products your factory produces.'))
                            ->columnSpanFull(),
                        TagsInput::make('solutions')
                            ->label(__('Services & Capabilities'))
                            ->placeholder(__('Add capability (e.g. OEM/ODM Service, Sample Available, Factory Tour) and press enter'))
                            ->helperText(__('Value-added service tags highlighted in your factory showroom.'))
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make(__('Contact Person'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_person_first_name')
                            ->label(__('First Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('contact_person_last_name')
                            ->label(__('Last Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label(__('Phone Number'))
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('Business Email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),

                \Filament\Schemas\Components\Section::make(__('Branding & Description'))
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('factories')
                            ->label(__('Company Logo'))
                            ->helperText(__('Recommended: 512x512 pixels (1:1 ratio).')),
                        FileUpload::make('banner')
                            ->image()
                            ->disk('public')
                            ->directory('factories')
                            ->label(__('Cover Banner'))
                            ->helperText(__('Recommended: 1200x400 pixels or wider (3:1 ratio).')),
                        Textarea::make('description')
                            ->label(__('About Your Factory'))
                            ->helperText(__('Describe what your factory produces, your specialties, certifications, and capacity.'))
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $factory = auth()->user()->factory;

        $officialName = $data['official_name'] ?? '';
        $description = $data['description'] ?? '';
        unset($data['official_name'], $data['description']);

        if ($factory) {
            $factory->update($data);
            if (!empty($officialName)) {
                $factory->setTranslation('official_name', app()->getLocale(), $officialName);
                if (empty($factory->slug)) {
                    $factory->slug = Str::slug($officialName);
                }
            }
            if ($description !== null) {
                $factory->setTranslation('description', app()->getLocale(), $description);
            }
            $factory->save();
        } else {
            $data['user_id'] = auth()->id();
            $data['slug'] = Str::slug($officialName) ?: 'factory-' . time();
            $data['status'] = 'pending';
            $factory = Factory::create($data);
            $factory->setTranslation('official_name', app()->getLocale(), $officialName);
            $factory->setTranslation('description', app()->getLocale(), $description);
            $factory->save();
        }

        Notification::make()
            ->title(__('Factory profile saved successfully!'))
            ->success()
            ->send();
    }
}
