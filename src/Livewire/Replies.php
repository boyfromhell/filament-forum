<?php

namespace IchBin\FilamentForum\Livewire;

use IchBin\FilamentForum\Models\Discussion;
use IchBin\FilamentForum\Models\Reply;
use Livewire\Component;
use Livewire\WithPagination;

class Replies extends Component
{
    use WithPagination;

    public Discussion $discussion;

    public ?Reply $selectedReply = null;

    public $limitPerPage = 10;

    public $disableLoadMore = false;

    public $onlyBest = false;

    public $onlyBestEnabled = false;

    protected $listeners = [
        'replyAdded' => 'updateReplies',
        'replyUpdated' => 'updateReplies',
        'replyDeleted' => 'updateReplies',
        'discussionEdited' => 'updateReplies',
    ];

    public function mount(): void
    {
        $this->onlyBestEnabled = $this->discussion->replies()->where('is_best', true)->count() > 0;
    }

    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Foundation\Application | \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\Foundation\Application
    {
        $replies = $this->loadData();

        return view('filament-forum::livewire.replies', compact('replies'));
    }

    public function updateReplies(): void
    {
        $this->replies = $this->discussion->replies()->get();
        $this->selectedReply = null;
    }

    public function loadMore(): void
    {
        $this->limitPerPage = $this->limitPerPage + 6;
    }

    public function loadData(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Reply::query();
        $query->where('discussion_id', $this->discussion->id);

        if ($this->onlyBest) {
            $query->where('is_best', true);
        }

        $data = $query->paginate($this->limitPerPage);
        if ($data->hasMorePages()) {
            $this->disableLoadMore = false;
        } else {
            $this->disableLoadMore = true;
        }

        return $data;
    }

    public function toggleOnlyBest(): void
    {
        $this->onlyBest = ! $this->onlyBest;
    }
}
