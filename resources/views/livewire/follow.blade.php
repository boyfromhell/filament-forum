<div class="w-full">
    @if(!$discussion->is_locked)
        <x-filament-forum::dropdown>
            <x-slot name="trigger">
                <button type="button"
                        class="w-full {{ $bgClass }} px-3 py-2 text-white border-slate-100 rounded hover:cursor-pointer">
                    <div wire:loading.remove>
                        @switch($type)
                            @case(\IchBin\FilamentForum\Core\FollowerConstants::NONE->value)
                                <x-far-star class="w-4 inline"/> Follow
                                @break
                            @case(\IchBin\FilamentForum\Core\FollowerConstants::FOLLOWING->value)
                                <x-fas-star class="w-4 inline"/> Following
                                @break
                            @case(\IchBin\FilamentForum\Core\FollowerConstants::NOT_FOLLOWING->value)
                                <x-far-star class="w-4 inline"/> Not following
                                @break
                            @case(\IchBin\FilamentForum\Core\FollowerConstants::IGNORING->value)
                                <x-far-eye-slash class="w-4 inline"/> Ignoring
                                @break
                        @endswitch
                    </div>
                    <div wire:loading>
                        <x-fas-spinner id="spin" class="w-4"/>
                    </div>
                </button>
            </x-slot>
            <x-slot name="content">
                <div class="z-10 bg-white rounded divide-y divide-slate-100 shadow dark:bg-slate-700" wire:ignore>
                    <ul class="text-sm text-slate-700 dark:text-slate-200" aria-labelledby="follow-dropdown-btn">
                        <li>
                            <button
                                wire:click="toggle('{{ \IchBin\FilamentForum\Core\FollowerConstants::NOT_FOLLOWING->value }}')"
                                type="button"
                                class="text-left w-full flex flex-col gap-2 py-2 px-4 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                <div class="w-full flex items-center font-medium text-slate-700 gap-2">
                                    <x-far-star class="w-4"/>
                                    Not Following
                                </div>
                                <span class="text-slate-500 text-xs">
                        Be notified only when @mentioned.
                    </span>
                            </button>
                        </li>
                        <li>
                            <button
                                wire:click="toggle('{{ \IchBin\FilamentForum\Core\FollowerConstants::FOLLOWING->value }}')"
                                type="button"
                                class="text-left w-full flex flex-col gap-2 py-2 px-4 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                <div class="w-full flex items-center font-medium text-slate-700 gap-2">
                                    <x-fas-star class="w-4"/>
                                    Following
                                </div>
                                <span class="text-slate-500 text-xs">
                        Be notified of all replies.
                    </span>
                            </button>
                        </li>
                        <li>
                            <button
                                wire:click="toggle('{{ \IchBin\FilamentForum\Core\FollowerConstants::IGNORING->value }}')"
                                type="button"
                                class="text-left w-full flex flex-col gap-2 py-2 px-4 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                <div class="w-full flex items-center font-medium text-slate-700 gap-2">
                                    <x-far-eye-slash class="w-4"/>
                                    Ignoring
                                </div>
                                <span class="text-slate-500 text-xs">
                        Never be notified. Hide from the discussion list.
                    </span>
                            </button>
                        </li>
                    </ul>
                </div>
            </x-slot>
        </x-filament-forum::dropdown>
    @endif
</div>
