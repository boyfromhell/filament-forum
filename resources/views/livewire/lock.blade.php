<button type="button" wire:click="toggleLockedFlag()" class="w-full {{ $discussion->is_locked ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} hover:cursor-pointer px-3 py-2 rounded shadow hover:shadow-lg text-white font-medium text-center flex items-center justify-center text-center gap-2">
    <div wire:loading.remove>
        @if($discussion->is_locked)
            <x-fas-lock-open class="w-4 inline" />
        @else
        <x-fas-lock class="w-4 inline" />
        @endif
    </div>
    <div wire:loading>
        <x-fas-spinner class="w-4 inline" id="spin"/>
    </div>
    {{ $discussion->is_locked ? 'Unlock discussion' : 'Lock discussion' }}
</button>
