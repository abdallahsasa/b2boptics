<?php

namespace App\Filament\Factory\Resources\ConversationResource\Pages;

use App\Filament\Factory\Resources\ConversationResource;
use Filament\Resources\Pages\ListRecords;

class ListConversations extends ListRecords
{
    protected static string $resource = ConversationResource::class;
}

