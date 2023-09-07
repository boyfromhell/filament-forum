<x-layout>
    <x-slot name="stanga">
        @include('filament-forum::partials.side-menu')
    </x-slot>
    <x-slot name="center">
        <livewire:discussions />
    </x-slot>
</x-layout>
