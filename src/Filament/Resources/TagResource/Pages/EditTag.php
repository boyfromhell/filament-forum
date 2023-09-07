<?php

namespace IchBin\FilamentForum\Filament\Resources\TagResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use IchBin\FilamentForum\Filament\Resources\TagResource;
use Filament\Resources\Pages\EditRecord;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
