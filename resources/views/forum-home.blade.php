<x-layout>
    <x-slot name="center">
        <livewire:discussions/>
    </x-slot>
    <x-slot name="forum">
        @include('filament-forum::partials.side-menu')
    </x-slot>
</x-layout>
