<?php

namespace IchBin\FilamentForum\Livewire;

use IchBin\FilamentForum\Models\Tag;
use Livewire\Component;

class Tags extends Component
{
    public $tags;

    public function mount(): void
    {
        $this->tags = Tag::query()->whereNotNull('parent_id')->get();
    }

    public function render()
    {
        return view('filament-forum::livewire.tags');
    }
}
