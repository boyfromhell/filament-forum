<div class="w-fit">
    @if(
            (auth()->user() && auth()->user()->hasVerifiedEmail())
//            &&
//            (
//                auth()->user()->id === $discussion->user_id
//                || auth()->user()->can(Permissions::EDIT_POSTS->value)
//            )
        )
        <button type="button" wire:click="toggleResolvedFlag()" class="flex items-center gap-1 w-fit {{ $discussion->is_resolved ? 'bg-slate-500 hover:bg-slate-600' : 'bg-green-500 hover:bg-green-600' }} hover:cursor-pointer px-3 py-2 rounded shadow hover:shadow-lg text-white font-medium text-center text-xs">
            <div wire:loading><x-fas-spinner id="spin" class="w-4" /></div>
            @if($discussion->is_resolved)
                <div wire:loading.remove><x-fas-rotate-left class="w-4" /> </div> Reopen discussion
            @else
                <div wire:loading.remove><x-fas-check class="w-4" /> </div> Mark as resolved
            @endif
        </button>
    @endif
</div>
