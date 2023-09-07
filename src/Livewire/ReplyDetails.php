<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconSize;
use IchBin\FilamentForum\Core\NotificationConstants;
use IchBin\FilamentForum\Core\PointsConstants;
use IchBin\FilamentForum\Jobs\CalculateUserPointsJob;
use IchBin\FilamentForum\Jobs\DispatchNotificationsJob;
use IchBin\FilamentForum\Models\Comment;
use IchBin\FilamentForum\Models\Like;
use IchBin\FilamentForum\Models\Reply;
use Livewire\Component;

class ReplyDetails extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Reply $reply;

    public int $likes = 0;

    public int $comments = 0;

    public bool $edit = false;

    public bool $showComments = false;

    public ?Comment $comment = null;

    public $selectedComment = null;

    public ?array $data = [];

    protected $listeners = [
        'doDel',
        'doDelete',
        'doDeleteReplyComment',
        'replyCommentSaved',
    ];

    public function mount(): void
    {
        $this->initDetails();
    }

    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Foundation\Application | \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\Foundation\Application
    {
        return view('filament-forum::livewire.reply-details');
    }

    private function initDetails(): void
    {
        $this->likes = $this->reply->likes()->count();
        $this->comments = $this->reply->comments()->count();
    }

    public function toggleLike(): void
    {
        $like = Like::where('user_id', auth()->user()->id)->where('source_id', $this->reply->id)->where('source_type', Reply::class)->first();
        if ($like) {
            $pointsType = PointsConstants::REPLY_DISLIKED->value;
            $source = $like;

            $like->delete();
        } else {
            $pointsType = PointsConstants::REPLY_LIKED->value;
            $source = Like::create([
                'user_id' => auth()->user()->id,
                'source_id' => $this->reply->id,
                'source_type' => Reply::class,
            ]);
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::MY_POSTS_LIKED->value, $source));
        }
        $this->reply->refresh();
        $this->initDetails();
        $this->dispatch('likesUpdated', $source->source_id);

        dispatch(new CalculateUserPointsJob(user: $source->source->user, source: $source, type: $pointsType));
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->link()
            ->size(ActionSize::Small)
            ->icon('fas-pen')
            ->iconSize(IconSize::Small)
            ->color('primary')
            ->extraAttributes([
                'class' => 'gap-2 font-normal text-xs text-blue-500 hover:underline hover-action',
            ])
            ->action(fn () => $this->edit = true);
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->link()
            ->color('danger')
            ->size(ActionSize::Small)
            ->icon('far-trash-can')
            ->iconSize(IconSize::Small)
            ->extraAttributes([
                'class' => 'gap-2 font-normal text-xs text-red-500 hover:underline hover-action',
            ])
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $reply = Reply::find($arguments['reply']);
                $reply?->delete();
                $this->dispatch('replyDeleted');
                dispatch(new CalculateUserPointsJob(user: $reply->user, source: $reply, type: PointsConstants::REPLY_DELETED->value));
                Notification::make()->title(
                    'Reply deleted!'
                )->body('The reply has been deleted successfully.')
                    ->success()->send();
            });
    }

    //    public function delete(int $id): Action
    //    {
    //        return Action::make('delete')
    //            ->requiresConfirmation()
    //            ->dispatch('doDelete',$id);
    ////        Notification::make()
    ////            ->warning()
    ////            ->title('Delete confirmation')
    ////            ->body('Are you sure you want to delete this reply?')
    ////            ->actions([
    ////                Action::make('confirm')
    ////                    ->label('Confirm')
    ////                    ->color('danger')
    ////                    ->button()
    ////                    ->dispatch('doDelete', function ($id){
    ////                        $this->dispatch('doDelete', id: $id);
    ////                    })
    //////                    //->action(fn () => $this->dispatch('doDelete', id: $this->reply->id))
    //////                    //->action(null)
    ////////                    ->action(function() {
    ////////                        dd('doDelete');
    ////////                    })
    //////                    ->dispatch('doDel', function (){
    //////                        dd($this->reply->id);
    //////                        $this->dispatch('doDelete',id: $this->reply->id);
    //////                    })
    ////                    ->close(),
    ////
    ////                Action::make('cancel')
    ////                    ->label('Cancel')
    ////                    ->close()
    ////            ])
    ////            ->persistent()
    ////            ->send();
    //    }

    public function doDelete($reply = null): void
    {
        dd($reply);
        //        $source = Reply::query()->where('id', $reply)->first();
        //        if ($source) {
        //            $source->delete();
        //            $this->dispatch('replyDeleted');
        //            dispatch(new CalculateUserPointsJob(user: $source->user, source: $source, type: PointsConstants::REPLY_DELETED->value));
        //        }
    }

    public function editReply(): void
    {
        $this->edit = true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('content')
                    ->label('Comment content')
                    ->required()
                    ->rows(2)
                    ->placeholder('Type your comment here...')
                    ->helperText('You can write a comment containing up to 300 characters.')
                    ->maxLength(300),
            ])->statePath('data');
    }

    public function addComment(): void
    {
        $this->comment = new Comment();
        $this->form->fill();
    }

    public function editComment(Comment $comment): void
    {
        $this->comment = $comment;
        $this->form->fill([
            'content' => $comment->content,
        ]);
    }

    public function cancelComment(): void
    {
        $this->comment = null;
        $this->form->fill();
    }

    public function deleteComment(int $comment): void
    {
        Notification::make()
            ->warning()
            ->title('Delete confirmation')
            ->body('Are you sure you want to delete this comment?')
            ->actions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->color('danger')
                    ->button()
                    ->close()
                    ->dispatch('doDeleteReplyComment', ['comment' => $comment]),

                Action::make('cancel')
                    ->label('Cancel')
                    ->close(),
            ])
            ->persistent()
            ->send();
    }

    public function doDeleteReplyComment(int $comment): void
    {
        $source = Comment::where('id', $comment)->first();
        if ($source) {
            $source->delete();
            $this->dispatch('replyCommentSaved');
            dispatch(new CalculateUserPointsJob(user: $source->user, source: $source, type: PointsConstants::COMMENT_DELETED->value));
        }
    }

    public function saveComment(): void
    {
        $data = $this->form->getState();
        $this->comment->content = $data['content'];
        $isCreation = false;

        if (! $this->comment->id) {
            $this->comment->user_id = auth()->user()->id;
            $this->comment->source_id = $this->reply->id;
            $this->comment->source_type = Reply::class;

            $isCreation = true;
        }
        $this->comment->save();
        $this->dispatch('replyCommentSaved', $this->reply->id);
        Notification::make()->success()->title(
            'Comment successfully saved.'
        )->send();

        if ($isCreation) {
            dispatch(new CalculateUserPointsJob(user: auth()->user(), source: $this->comment, type: PointsConstants::NEW_COMMENT->value));
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::MY_POSTS_COMMENTED->value, $this->comment));
        }
    }

    public function commentSaved(): void
    {
        $this->cancelComment();
        $this->reply->refresh();
        $this->initDetails();
    }

    public function toggleCommentLike(int $comment): void
    {
        $like = Like::where('user_id', auth()->user()->id)->where('source_id', $comment)->where('source_type', Comment::class)->first();
        if ($like) {
            $pointsType = PointsConstants::COMMENT_DISLIKED->value;
            $source = $like;

            $like->delete();
        } else {
            $pointsType = PointsConstants::COMMENT_LIKED->value;
            $source = Like::create([
                'user_id' => auth()->user()->id,
                'source_id' => $comment,
                'source_type' => Comment::class,
            ]);
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::MY_POSTS_LIKED->value, $source));
        }
        $this->reply->refresh();
        $this->dispatch('likesUpdated', $source->source_id);

        dispatch(new CalculateUserPointsJob(user: $source->source->user, source: $source, type: $pointsType));
    }

    public function toggleComments(): void
    {
        $this->showComments = ! $this->showComments;
        if ($this->showComments) {
            $this->dispatch('replyCommentsLoaded');
        }
    }

    public function replyCommentSaved(): void
    {
        $this->reply->refresh();
    }

    public function toggleBestFlag(): void
    {
        $this->reply->is_best = ! $this->reply->is_best;
        $this->reply->save();
        $this->reply->refresh();

        $pointsType = $this->reply->is_best ? PointsConstants::BEST_REPLY : PointsConstants::BEST_REPLY_REMOVED;
        dispatch(new CalculateUserPointsJob(user: $this->reply->user, source: $this->reply, type: $pointsType));

        if ($this->reply->is_best) {
            dispatch(new DispatchNotificationsJob(auth()->user(), NotificationConstants::MY_REPLY_BEST_ANSWER->value, $this->reply));
        }
    }

    public function selectComment(int $comment): void
    {
        $this->selectedComment = $this->reply->comments()->where('id', $comment)->first();
        $this->dispatch('replyCommentSelected', [
            'id' => $comment,
        ]);
    }
}
