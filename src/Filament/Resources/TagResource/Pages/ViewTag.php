<?php

namespace IchBin\FilamentForum\Filament\Resources\TagResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use IchBin\FilamentForum\Filament\Resources\TagResource;

class ViewTag extends ViewRecord
{
    protected static string $resource = TagResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
