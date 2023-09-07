<?php

namespace IchBin\FilamentForum\Livewire;

use IchBin\FilamentForum\Models\Comment;
use IchBin\FilamentForum\Models\Discussion;
use IchBin\FilamentForum\Models\Reply;
use Livewire\Component;

class Likes extends Component
{
    public Discussion | Reply | Comment $model;

    public $likes;

    protected $listeners = [
        'likesUpdated',
    ];

    public function mount()
    {
        //dd($this->likes);
        $this->initData();
    }

    public function render()
    {
        return view('filament-forum::livewire.likes');
    }

    public function likesUpdated(int $id)
    {
        if ($this->model->id == $id) {
            $this->model->refresh();
            $this->initData();
        }
    }

    private function initData()
    {
        $this->likes = $this->model->likes->sortByDesc('created_at');
    }
}
