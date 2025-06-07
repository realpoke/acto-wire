<x-layouts.base :title="$title ?? null">
    <livewire:partials.navigation-component />

    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.base>
