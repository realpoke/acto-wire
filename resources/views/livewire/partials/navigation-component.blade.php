<div style="all: unset; display: contents">
    <flux:header
        container
        class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <a
            href="{{ route('landing.page') }}"
            class="flex items-center space-x-2 lg:ml-0"
            wire:navigate
        >
            <x-app.logo />
        </a>

        <flux:spacer />

        <div class="hidden space-x-2 lg:flex">
            <x-partials.pickers key="header" />
        </div>

        <flux:separator vertical class="mx-2 my-4 hidden lg:block" />

        @auth
            <flux:profile
                :name="$this->user->name"
                class="block lg:hidden"
                icon-trailing="bars-3"
                x-on:click="
                    document.body.hasAttribute('data-show-stashed-sidebar')
                        ? document.body.removeAttribute('data-show-stashed-sidebar')
                        : document.body.setAttribute('data-show-stashed-sidebar', '')
                "
                aria-label="{{ __('navigation.toggle-sidebar') }}"
                :avatar="$this->user->avatar_url ?? ''"
            ></flux:profile>
            <flux:dropdown position="top" align="start" class="hidden lg:block">
                <flux:profile
                    :name="$this->user->name"
                    :avatar="$this->user->avatar_url ?? ''"
                />

                <flux:menu>
                    <flux:menu.item
                        wire:click="logout"
                        class="cursor-pointer"
                        icon="arrow-right-start-on-rectangle"
                    >
                        {{ __('navigation.logout') }}
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        @else
            <flux:navbar class="hidden lg:flex">
                <flux:navbar.item
                    wire:navigate
                    icon="arrow-right-end-on-rectangle"
                    :href="route('login.page')"
                >
                    {{ __('navigation.login') }}
                </flux:navbar.item>
            </flux:navbar>
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />
        @endauth
    </flux:header>

    <flux:sidebar
        stashable
        sticky
        class="border-r border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
    >
        <div class="flex gap-2">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:separator vertical class="my-2" />

            <x-partials.pickers key="sidebar" />
        </div>

        <a
            href="{{ route('landing.page') }}"
            class="ml-1 flex items-center space-x-2"
            wire:navigate
        >
            <x-app.logo />
        </a>

        <flux:spacer />

        <flux:navlist variant="outline">
            @auth
                <flux:navlist.item
                    wire:click="logout"
                    class="cursor-pointer"
                    icon="arrow-right-start-on-rectangle"
                >
                    {{ __('navigation.logout') }}
                </flux:navlist.item>
            @else
                <flux:navlist.item
                    wire:navigate
                    icon="arrow-right-end-on-rectangle"
                    :href="route('login.page')"
                >
                    {{ __('navigation.login') }}
                </flux:navlist.item>
            @endauth
        </flux:navlist>
    </flux:sidebar>
</div>
