<?php

namespace IchBin\FilamentForum\Forms;

use Filament\Forms\Components\RichEditor;

class MentionsRichEditor extends RichEditor
{
    protected string $view = 'filament-forum::components.mentions-rich-editor';

    protected array $mentionsItems = [];

    public function mentionsItems(array $mentionsItems): static
    {
        $this->mentionsItems = $mentionsItems;

        return $this;
    }

    public function getMentionsItems(): array
    {
        return $this->mentionsItems;
    }
}
