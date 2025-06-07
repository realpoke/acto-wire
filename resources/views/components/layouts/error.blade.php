<x-layouts.base>
    @sectionMissing('no-navigation')
        <div
            class="absolute top-2 left-2 flex justify-center space-x-6 lg:top-6 lg:left-6"
        >
            @if (url()->previous())
                <flux:button
                    wire:navigate
                    href="{{ url()->previous() }}"
                    icon="arrow-uturn-left"
                    variant="subtle"
                />
            @endif

            <a
                href="{{ route('landing.page') }}"
                class="flex items-center space-x-2 lg:ml-0"
                wire:navigate
            >
                <x-app.logo />
            </a>
        </div>
    @endif

    <flux:main
        class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900"
    >
        <flux:heading size="xl" align="center">
            @yield('code')
            -
            @yield('title')
        </flux:heading>

        <flux:subheading align="center">
            @yield('message')
        </flux:subheading>
    </flux:main>
</x-layouts.base>
