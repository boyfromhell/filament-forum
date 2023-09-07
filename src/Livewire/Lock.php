<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use IchBin\FilamentForum\Core\NotificationConstants;
use IchBin\FilamentForum\Jobs\DispatchNotificationsJob;
use IchBin\FilamentForum\Models\Discussion;
use Illuminate\Support\Str;
use Livewire\Component;

class Lock extends Component
{
    public Discussion $discussion;

    protected $listeners = [
        'doToggleLockedFlag',
    ];

    public function render()
    {
        return view('filament-forum::livewire.lock');
    }

    public function toggleLockedFlag(): void
    {
        Notification::make()
            ->warning()
            ->title(fn () => $this->discussion->is_locked ? 'Unlock confirmation' : 'Lock confirmation')
            ->body('Are you sure you want to change the locked flag for this discussion?')
            ->actions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->color('danger')
                    ->button()
                    ->close()
                    ->dispatch('doToggleLockedFlag'),

                Action::make('cancel')
                    ->label('Cancel')
                    ->close(),
            ])
            ->persistent()
            ->send();
    }

    public function doToggleLockedFlag(): void
    {
        $this->discussion->is_locked = ! $this->discussion->is_locked;
        $this->discussion->save();
        Notification::make()->success()->title(
            'The discussion is now ' . ($this->discussion->is_locked ? 'locked' : 'unlocked')
        )->send();
        if ($this->discussion->is_locked) {
            $type = NotificationConstants::DISCUSSION_LOCKED->value;
        } else {
            $type = NotificationConstants::DISCUSSION_UNLOCKED->value;
        }
        dispatch(new DispatchNotificationsJob(auth()->user(), $type, $this->discussion));
        $this->redirect(route('forum.discussion', ['discussion' => $this->discussion, 'slug' => Str::slug($this->discussion->name)]));
    }
}
