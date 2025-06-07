<x-layouts.base :title="$title ?? null">
    <div class="absolute top-0 right-0 flex space-x-2 p-4 sm:p-6">
        <x-partials.pickers />
    </div>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900"
    >
        <div class="flex w-full max-w-sm flex-col gap-2">
            <div class="flex flex-col items-center gap-2 font-medium">
                <span
                    class="mb-1 flex h-9 w-9 items-center justify-center rounded-md"
                >
                    <a href="{{ route('landing.page') }}">
                        <x-app.logo-icon
                            class="size-9 fill-current text-black dark:text-white"
                        />
                    </a>
                </span>
                <span class="sr-only">{{ __('app.name') }}</span>
            </div>
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.base>
