<?php

namespace IchBin\FilamentForum\Livewire;

use IchBin\FilamentForum\Models\Discussion;
use Livewire\Component;

class ReplyBtn extends Component
{
    public Discussion $discussion;

    protected $listeners = [
        'discussionEdited'
    ];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('filament-forum::livewire.reply-btn');
    }

    public function discussionEdited(): void
    {
        $this->discussion->refresh();
    }
}
