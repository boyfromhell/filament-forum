<div class="flex flex-col gap-8 w-full bg-white rounded-md shadow py-6">
    <div class="flex justify-between w-full px-4">
        <form class="">
            {{ $this->form }}
        </form>
        <form action="{{ route('forum.search') }}" method="GET" class="">
            <input type="search" id="search-navbar" name="q" value="{{ request('q') }}" minlength="3" required
                   class="block w-full p-2 pl-2 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search...">
        </form>
    </div>

    <div class="w-full flex flex-col gap-1 pl-4">
        @if($selectedSort)
            <div class="text-slate-700 font-medium text-lg flex items-center gap-2">
                {{ $selectedSort }}
                <div wire:loading>
                    <x-fas-spinner id="spin"/>
                </div>
            </div>
        @endif
        @if($q)
            <span class="text-slate-700 font-medium text-sm">
                Search for: {{ $q }}
            </span>
        @endif
    </div>
    <div class="w-full flex flex-col">
        @if($discussions->count())
            @foreach($discussions as $discussion)
                @include('filament-forum::partials.discussion-item', compact('discussion'))
            @endforeach
        @else
            <span class="text-slate-700 font-medium text-sm pl-4">
                @if($q)
                    <p>
                    No discussions available for your current
                    search! @if(auth()->user() && auth()->user()->hasVerifiedEmail())
                        Maybe you should start a new discussions.</p>
                    @endif
                @else
                    <p>
                    No discussions available for now! @if(auth()->user() && auth()->user()->hasVerifiedEmail())
                        Please come back later, or start a new discussion.</p>
                    @else
                        <p>
                        Please come back later.</p>
                    @endif
                @endif
            </span>
        @endif
    </div>
    <div class="w-full flex flex-col justify-center items-center text-center gap-2">
        @if(!$disableLoadMore)
            <button type="button" wire:click="loadMore" wire:loading.attr="disabled"
                    class="bg-slate-100 disabled:bg-slate-50 px-3 py-2 text-slate-500 border-slate-100 rounded hover:cursor-pointer w-fit hover:bg-slate-200">
                Load more
            </button>
        @endif
        @if($totalCount)
            <span class="text-xs text-slate-600">
                Showing {{ min($limitPerPage, $totalCount) }} of {{ $totalCount }} {{ $totalCount > 1 ? 'discussions' : 'discussion' }}
            </span>
        @endif
        </div>
    </div>
