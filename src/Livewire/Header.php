<?php

namespace IchBin\FilamentForum\Livewire;

use IchBin\FilamentForum\Models\Discussion;
use Livewire\Component;

class Header extends Component
{
    public Discussion $discussion;
    public int $replies = 0;
    public int $comments = 0;
    public bool $isResolved = false;
    public bool $isPublic = false;
    public bool $isLocked = false;
    public bool $isSticky = false;

    protected $listeners = [
        'replyAdded' => 'initData',
        'replyUpdated' => 'initData',
        'replyDeleted' => 'initData',
        'discussionEdited' => 'initData',
        'resolvedFlagUpdated' => 'resolvedFlagUpdated'
    ];

    public function mount(): void
    {
        $this->initData();
    }

    public function render()
    {
        return view('filament-forum::livewire.header');
    }

    public function initData(): void
    {
        $this->replies = $this->discussion->replies()->count();
        $this->comments = $this->discussion->comments()->count();
        $this->isResolved = $this->discussion->is_resolved;
        $this->isPublic = $this->discussion->is_public;
        $this->isLocked = $this->discussion->is_locked;
        $this->isSticky = $this->discussion->is_sticky;
    }

    public function resolvedFlagUpdated(): void
    {
        $this->discussion->refresh();
        $this->isResolved = $this->discussion->is_resolved;
    }
}
