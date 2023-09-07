<div class="w-full">
    @if(!$discussion->is_locked)
        <x-filament::modal id="add-reply-modal">
            <x-slot name="trigger">
                    <button type="button"
                            class="w-full bg-blue-500 hover:bg-blue-600 hover:cursor-pointer px-3 py-2 rounded shadow hover:shadow-lg text-white font-medium text-center">
                        Reply
                    </button>
            </x-slot>
            <livewire:reply :discussion="$discussion"/>
        </x-filament::modal>
    @endif
</div>
