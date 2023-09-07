<div class="w-full bg-white rounded-md shadow p-4 flex flex-col space-y-3">
@can('create', IchBin\FilamentForum\Models\Discussion::class)
{{--    <button type="button" data-modal-toggle="add-discussion-modal"--}}
{{--            class="bg-blue-500 hover:bg-blue-600 hover:cursor-pointer px-3 py-2 rounded shadow hover:shadow-lg text-white font-medium text-center">--}}
{{--        Start a Discussion--}}
{{--    </button>--}}
        <x-filament::modal
            wire:ignore
            id="add-discussion-modal"
            width="7xl"
            :close-by-clicking-away="false"
        >
            <x-slot name="trigger">
                <button type="button" data-modal-toggle="add-discussion-modal"
                        class="bg-blue-500 hover:bg-blue-600 hover:cursor-pointer px-3 py-2 rounded shadow hover:shadow-lg text-white font-medium text-center">
                    Start a Discussion
                </button>
            </x-slot>
            <x-slot name="heading">
            </x-slot>
            <livewire:add-discussion />
        </x-filament::modal>
@endcan
<div class="w-full flex flex-col gap-5">
    <a href="{{ route('forum.index') }}"
       class="w-full flex items-center {{ Route::is('forum.index') ? 'text-blue-500 font-medium' : 'hover:text-blue-500 text-slate-500' }}">
        <span class="w-[25px] mr-2"><x-fas-comments class="w-[25px]"/></span>
        <span>All discussions</span>
    </a>
    <a href="{{ route('forum.tags') }}"
       class="w-full flex items-center {{ Route::is('forum.tags') ? 'text-blue-500 font-medium' : 'hover:text-blue-500 text-slate-500' }}">
        <span class="w-[25px] mr-2"><x-heroicon-o-tag class="w-[25px]"/></span>
        <span>Tags</span>
    </a>
</div>
<div class="w-full flex flex-col gap-5">
    <x-filament-forum::tags :tag="$tag ?? null"/>
</div>
@can('create', IchBin\FilamentForum\Models\Discussion::class)
    @include('filament-forum::partials.add-discussion')
@endcan
</div>
