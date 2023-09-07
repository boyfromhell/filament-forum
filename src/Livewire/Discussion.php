<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use IchBin\FilamentForum\Core\ConfigurationConstants;
use IchBin\FilamentForum\Core\NotificationConstants;
use IchBin\FilamentForum\Core\PointsConstants;
use IchBin\FilamentForum\Jobs\CalculateUserPointsJob;
use IchBin\FilamentForum\Jobs\DispatchNotificationsJob;
use IchBin\FilamentForum\Models\Discussion as DiscussionModel;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use IchBin\FilamentForum\Models\DiscussionTag;
use IchBin\FilamentForum\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Component;

class Discussion extends Component implements HasForms
{
    use InteractsWithForms;

    public DiscussionModel|null $discussion = null;
    public ?array $data = [];

    public function mount(): void
    {
        $data = [];
        if ($this->discussion) {
            $data['name'] = $this->discussion->name;
            $data['content'] = $this->discussion->content;
            $data['tags'] = $this->discussion->tags->pluck('id')->toArray();
            $data['is_public'] = $this->discussion->is_public;
        }
        $this->form->fill($data);
    }

    public function render()
    {
        return view('filament-forum::livewire.discussion');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Toggle::make('is_public')
                ->label('Is this discussion public?')
                ->visible(fn() => ConfigurationConstants::case('Enable public discussions')),

            Grid::make()
                ->columns(5)
                ->schema([

                    TextInput::make('name')
                        ->label('Discussion title')
                        ->required()
                        ->columnSpan(3)
                        ->maxLength(255),

                    Select::make('tags')
                        ->label('Tags')
                        ->required()
                        ->multiple()
                        ->columnSpan(2)
                        ->maxItems(3)
                        ->options(Tag::query()->whereNotNull('parent_id')->pluck('name', 'id')),

                ]),

            RichEditor::make('content')
                ->label('Discussion content')
                ->required(),
        ])->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $update = false;
        if ($this->discussion) {
            $this->discussion->name = $data['name'];
            $this->discussion->content = $data['content'];
            $this->discussion->is_public = $data['is_public'] ?? false;
            $this->discussion->save();
            DiscussionTag::where('discussion_id', $this->discussion->id)->delete();
            $update = true;
            $discussion = $this->discussion;
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::MY_DISCUSSION_EDITED->value, $this->discussion));
        } else {
            $discussion = DiscussionModel::create([
                'name' => $data['name'],
                'user_id' => auth()->user()->id,
                'content' => $data['content'],
                'is_public' => $data['is_public'] ?? false
            ]);
            dispatch(new CalculateUserPointsJob(user: auth()->user(), source: $discussion, type: PointsConstants::START_DISCUSSION->value));
        }
        foreach ($data['tags'] as $tag) {
            DiscussionTag::create([
                'discussion_id' => $discussion->id,
                'tag_id' => $tag
            ]);
        }
        Notification::make()->success()->title(
            ($update ? 'Discussion updated successfully' : 'Discussion created successfully')
        )->send();
        if ($update) {
            $this->dispatch('discussionEdited');
        } else {
            $this->redirect(route('forum.discussion', [
                'discussion' => $discussion,
                'slug' => Str::slug($discussion->name)
            ]));
        }
    }

    public function cancel(): void
    {
        $this->dispatch('updateDiscussionCanceled');
    }
}
