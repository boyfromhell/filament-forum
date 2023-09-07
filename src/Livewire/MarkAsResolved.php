<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Notifications\Notification;
use IchBin\FilamentForum\Models\Discussion;
use Livewire\Component;

class MarkAsResolved extends Component
{
    public Discussion $discussion;

    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Foundation\Application | \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\Foundation\Application
    {
        return view('filament-forum::livewire.mark-as-resolved');
    }

    public function toggleResolvedFlag(): void
    {
        $this->discussion->is_resolved = ! $this->discussion->is_resolved;
        $this->discussion->save();
        Notification::make()
            ->success()->title(
                match ($this->discussion->is_resolved) {
                    true => 'Discussion marked as resolved.',
                    false => 'Discussion reopened.'
                }
            )->send();
        $this->dispatch('resolvedFlagUpdated');
    }
}
