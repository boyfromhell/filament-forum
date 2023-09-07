<?php

namespace IchBin\FilamentForum\Livewire;

use App\Models\User;
use Filament\Forms\Form;
use IchBin\FilamentForum\Forms\MentionsRichEditor;
use IchBin\FilamentForum\Models\Discussion;
use Filament\Notifications\Notification;
use IchBin\FilamentForum\Core\NotificationConstants;
use IchBin\FilamentForum\Core\PointsConstants;
use IchBin\FilamentForum\Jobs\CalculateUserPointsJob;
use IchBin\FilamentForum\Jobs\DispatchNotificationsJob;
use IchBin\FilamentForum\Models\Reply as ReplyModel;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Reply extends Component implements HasForms
{
    use InteractsWithForms;

    public Discussion $discussion;
    public ReplyModel|null $reply = null;
    public ?array $data = [];

    public function mount(): void
    {
        $data = [];
        if ($this->reply) {
            $data['content'] = $this->reply->content;
        }
        $this->form->fill($data);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('filament-forum::livewire.reply');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            MentionsRichEditor::make('content')
                ->label('Reply content')
                ->mentionsItems(
                    User::all()
                        ->map(
                            fn (User $user) => [
                                'key' => $user->username,
                                'link' => route('profile.show', [
                                    'username' => $user->username,
                                ])
                            ])
                        ->toArray()
                )
                ->required()
        ])->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        if ($this->reply) {
            $this->reply->content = $data['content'];
            $this->reply->save();
            $message = 'Reply updated successfully';
        } else {
            ReplyModel::create([
                'user_id' => auth()->user()->id,
                'discussion_id' => $this->discussion->id,
                'content' => $data['content']
            ]);
            $message = 'Reply added successfully';
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::POST_IN_DISCUSSION->value, $this->discussion));
        }
        Notification::make()->success()->title($message)->send();
        $this->dispatch('close-modal',id: 'add-reply-modal');
        $this->dispatch('replyAdded');
        $this->dispatch('replyUpdated');
        if ($this->reply) {
            $this->dispatch('replyUpdated');
        } else {
            $this->dispatch('replyAdded');

            dispatch(new CalculateUserPointsJob(user: auth()->user(), source: $this->reply, type: PointsConstants::NEW_REPLY->value));
        }
        if ($this->reply) {
            $data['content'] = $this->reply->content;
        }
        $this->form->fill();


    }

    public function cancel(): void
    {
        $this->dispatch('replyUpdated');
    }
}
