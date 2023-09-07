<div class="w-full py-4 flex flex-row justify-center rounded-lg items-center lg:px-0 px-6"
     style="background-color: {{ $discussion->tags->first()->color }}CC">
    <div class="container pl-4 flex flex-row justify-start items-center gap-5">
        <div class="flex flex-col justify-center items-start gap-3">
            <div class="flex flex-col gap-2">
                <span class="text-xl font-medium text-white text-shadow-lg">
                    @if($discussion->is_locked)
                        <x-fas-lock class="w-4 inline"/>
                    @endif
                    @if($discussion->is_sticky)
                        <x-fas-location-pin class="w-4 inline"/>
                    @endif
                    <span class="font-normal">{{ $isResolved ? '[Resolved]' : '' }}</span>
                    {{ $discussion->name }}
                </span>
                @include('filament-forum::partials.tags', ['tags' => $discussion->tags])
            </div>
            <div
                class="w-full lg:flex lg:flex-row grid grid-cols-2 justify-start items-center lg:gap-5 gap-2 text-white text-xs text-shadow">
                <span class="lg:flex lg:flex-row block justify-center lg:items-center items-start gap-2">
                    <x-fas-clock class="w-4"/> {{ $discussion->updated_at->diffForHumans() }}
                </span>
                <span class="lg:flex lg:flex-row block justify-center lg:items-center items-start gap-2">
                    <x-fas-message class="w-4"/> Created {{ $discussion->created_at->diffForHumans() }}
                </span>
                @if($isResolved)
                    <span class="lg:flex lg:flex-row block justify-center lg:items-center items-start gap-2">
                        <x-fas-check class="w-4"/> Resolved
                    </span>
                @endif
                <span class="lg:flex lg:flex-row block justify-center lg:items-center items-start gap-2">
                    <x-fas-comments
                        class="w-4"/> {{ $replies }} {{ $replies > 1 ? 'replies' : 'reply' }} / {{ $comments }} {{ $comments > 1 ? 'comments' : 'comment' }}
                </span>
                @if($isPublic)
                    <span class="lg:flex lg:flex-row block justify-center lg:items-center items-start gap-2">
                        <x-fas-globe class="w-4"/> Public discussion
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
