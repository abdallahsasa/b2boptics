<?php

namespace App\Filament\Factory\Widgets;

use Filament\Widgets\Widget;

class FactorySubscriptionWidget extends Widget
{
    protected static ?int $sort = -2;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.factory.widgets.factory-subscription-widget';
}

