<x-layout>
    <div class="w-full py-4 rounded-lg flex flex-row justify-center items-center lg:px-0 px-6 mb-4"
         style="background-color: {{ $tag->color }}CC">
        <div class="container flex flex-col justify-center items-center gap-3 text-white text-center text-lg">
            <div class="w-full flex items-center justify-center gap-2 text-xl font-medium">
                {{ svg($tag->icon, ['class'=>'w-8']) }} {{ $tag->name }}
            </div>
            <div class="w-full text-sm font-light">
                {!! nl2br(e($tag->description)) !!}
            </div>
        </div>
    </div>

    <x-slot name="forum">
        @include('filament-forum::partials.side-menu', ['tag' => $tag->id])
    </x-slot>

    <x-slot name="center">

        <!-- Page content -->
        <div class="w-full flex justify-center items-center px-2 sm:px-4">

            <livewire:discussions :tag="$tag->id"/>
        </div>
    </x-slot>
</x-layout>
