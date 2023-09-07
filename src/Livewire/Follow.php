<?php

namespace IchBin\FilamentForum\Livewire;

use App\Models\User;
use Filament\Notifications\Notification;
use IchBin\FilamentForum\Core\FollowerConstants;
use IchBin\FilamentForum\Models\Discussion;
use IchBin\FilamentForum\Models\Follower;
use Livewire\Component;

class Follow extends Component
{
    public Discussion $discussion;

    public ?string $type = null;

    public ?User $follower = null;

    public string $bgClass = 'bg-slate-500 hover:bg-slate-600';

    public function mount(): void
    {
        $this->initFollower();
    }

    public function render()
    {
        return view('filament-forum::livewire.follow');
    }

    public function toggle(string $type): void
    {
        $follower = Follower::where('user_id', auth()->user()->id)->where('discussion_id', $this->discussion->id)->first();
        if (! $follower) {
            $follower = new Follower();
            $follower->user_id = auth()->user()->id;
            $follower->discussion_id = $this->discussion->id;
        }
        $follower->type = $type;
        $follower->save();
        $this->initFollower();
        Notification::make()->success()->title(
            'Follow status successfully upated.'
        )->send();
    }

    private function initFollower(): void
    {
        if (auth()->check()) {
            $this->follower = $this->discussion->followers()->where('user_id', auth()->user()->id)->first();
        } else {
            $this->follower = null;
        }
        $this->type = $this->follower?->pivot?->type ?? FollowerConstants::NONE->value;
        $this->bgClass = match ($this->type) {
            FollowerConstants::FOLLOWING->value => 'bg-green-500 hover:bg-green-600',
            FollowerConstants::NOT_FOLLOWING->value => 'bg-orange-500 hover:bg-orange-600',
            FollowerConstants::IGNORING->value => 'bg-red-500 hover:bg-g-red-600',
            default => 'bg-slate-500 hover:bg-slate-600',
        };
    }
}
