<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use IchBin\FilamentForum\Models\Discussion;
use Livewire\Component;
use Livewire\WithPagination;

class Discussions extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    public $limitPerPage = 10;

    public $disableLoadMore = false;

    public $tag;

    public $selectedSort;

    public $q;

    public $totalCount = 0;

    public ?array $data = [];

    public function mount(): void
    {
        $this->q = request('q');
        $this->form->fill([
            'sort' => 'latest',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Foundation\Application | \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\Foundation\Application
    {
        $discussions = $this->loadData();

        return view('filament-forum::livewire.discussions', compact('discussions'));
    }

    public function loadMore(): void
    {
        $this->limitPerPage = $this->limitPerPage + 10;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(12)
                    ->schema([
                        Select::make('sort')
                            ->hiddenLabel()
                            ->selectablePlaceholder()
                            ->options([
                                'latest' => 'Latest',
                                'oldest' => 'Oldest',
                                'trending' => 'Trending',
                                'most-liked' => 'Most liked',
                            ])
                            ->columnSpan([
                                12,
                                'lg' => 3,
                            ])
                            ->reactive()
                            ->afterStateUpdated(function () {
                                $this->loadData();
                            })
                            ->extraAttributes([
                                'class' => 'disabled:bg-slate-100',
                            ]),
                    ]),
            ])->statePath('data');
    }

    public function loadData()
    {
        $data = $this->form->getState();
        $sort = $data['sort'] ?? 'latest';

        $query = Discussion::query();

        if (! auth()->user() || ! auth()->user()->hasVerifiedEmail()) {
            $query->where('is_public', true);
        }

        if ($this->tag) {
            $query->whereHas('tags', function ($query) {
                return $query->where('tag_id', $this->tag);
            });
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                $this->selectedSort = 'Oldest discussions';

                break;
            case 'trending':
                $query->orderBy('unique_visits', 'desc')
                    ->orderBy('created_at', 'desc');
                $this->selectedSort = 'Trending discussions';

                break;
            case 'most-liked':
                $query->withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('created_at', 'desc');
                $this->selectedSort = 'Most liked discussions';

                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                $this->selectedSort = 'Latest discussions';

                break;
        }

        if ($this->q) {
            $query->where(
                fn ($query) => $query
                    ->where('name', 'like', '%' . $this->q . '%')
                    ->orWhere('content', 'like', '%' . $this->q . '%')
                    ->orWhereHas('tags', fn ($query) => $query->where('name', 'like', '%' . $this->q . '%'))
            );
        }

        $data = $query->paginate($this->limitPerPage);
        if ($data->hasMorePages()) {
            $this->disableLoadMore = false;
        } else {
            $this->disableLoadMore = true;
        }

        $this->totalCount = $data->total();

        return $data;
    }
}
