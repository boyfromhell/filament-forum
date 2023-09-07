<?php

namespace IchBin\FilamentForum\View\Components;

use IchBin\FilamentForum\Models\Tag;
use Illuminate\View\Component;

class Tags extends Component
{
    public $tags;

    public $tag = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tag = null)
    {
        $this->tag = $tag;
        $this->tags = Tag::query()->scopes('parents')->orderBy('order', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View | string | \Closure
    {
        return view('filament-forum::components.tags');
    }
}
