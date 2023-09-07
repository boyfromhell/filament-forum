@php
    $type = $type ?? null;
    if (!$type) {
        if(auth()->check()) {
            $type = $discussion->followers()->where('user_id', auth()->user()->id)->first()?->pivot?->type ?? \IchBin\FilamentForum\Core\FollowerConstants::NONE->value;
        } else {
            $type = \IchBin\FilamentForum\Core\FollowerConstants::NONE->value;
        }
    }
@endphp
    <!-- Item -->
<a href="{{ route('forum.discussion', ['discussion' => $discussion, 'slug' => Str::slug($discussion->name)]) }}"
   class="w-full flex lg:flex-row flex-col lg:gap-0 gap-3 items-start justify-between hover:bg-slate-100 hover:cursor-pointer px-3 hover:rounded transition-all border-slate-200 py-5 {{ $loop->last ? '' : 'border-b' }}">
    <div class="flex gap-3">
        <img src="{{ $discussion->user->profile_photo_url }}" alt="Avatar"
             class="rounded-full w-10 h-10 border border-slate-200 shadow"/>
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-1">
                @if($discussion->is_locked)
                    <x-fas-lock class="w-4 inline"/>
                @endif
                @switch($type)
                    @case(\IchBin\FilamentForum\Core\FollowerConstants::FOLLOWING->value)
                        <x-fas-star class="text-green-500 w-4 inline"/>
                        @break
                    @case(\IchBin\FilamentForum\Core\FollowerConstants::NOT_FOLLOWING->value)
                        <x-fas-star class="text-orange-500 w-4 inline"/>
                        @break
                    @case(\IchBin\FilamentForum\Core\FollowerConstants::IGNORING->value)
                        <x-fas-eye-slash class="text-red-500 w-4 inline"/>
                        @break
                @endswitch
                <span class="font-medium text-slate-500">
                    @if($discussion->is_resolved)
                        <span class="font-normal">[Resolved]</span>
                    @endif
                    {{ $discussion->name }}
                </span>
            </div>
            <span class="text-slate-400 text-sm">
                Created by <span class="font-medium">{{ $discussion->user->username }}</span> (<span
                    class="text-xs">{{ $discussion->created_at->diffForHumans() }}</span>)
            </span>
            <span class="text-slate-400 font-light lg:max-w-[90%] max-w-full text-sm">
                {{ Str::limit(strip_tags($discussion->content), 200) }}
            </span>
            <div class="flex items-center mt-2">
                @include('filament-forum::partials.tags', ['tags' => $discussion->tags, 'ignore_first' => true])
            </div>
        </div>
    </div>
    <div class="flex flex-row items-center gap-3 lg:pl-0 pl-14">
        <div class="flex items-center gap-3 lg:order-2 order-1">
            <span class="text-sm text-slate-500 flex items-center gap-1">
                <x-far-thumbs-up class="w-4"/> {{ $discussion->likes()->count() }}
            </span>
            <span class="text-sm text-slate-500 flex items-center gap-1">
                <x-far-comment class="w-4"/> {{ $discussion->replies()->count() }}
            </span>
        </div>
    </div>
</a>
