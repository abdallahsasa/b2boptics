<?php

namespace App\Filament\Factory\Resources\ConversationResource\Pages;

use App\Filament\Factory\Resources\ConversationResource;
use Filament\Resources\Pages\EditRecord;

class EditConversation extends EditRecord
{
    protected static string $resource = ConversationResource::class;

    protected string $view = 'filament.resources.conversations.pages.edit-conversation';

    protected function getHeaderActions(): array
    {
        return [];
    }
}

