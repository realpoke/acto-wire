<div>
    <flux:dropdown position="bottom">
        <flux:button variant="subtle" square class="group">
            <flux:icon.sun
                x-cloak
                x-show="$flux.appearance === 'light'"
                variant="mini"
                class="text-zinc-500 dark:text-white"
            />
            <flux:icon.moon
                x-cloak
                x-show="$flux.appearance === 'dark'"
                variant="mini"
                class="text-zinc-500 dark:text-white"
            />
            <flux:icon.moon
                x-cloak
                x-show="$flux.appearance === 'system' && $flux.dark"
                variant="mini"
            />
            <flux:icon.sun
                x-cloak
                x-show="$flux.appearance === 'system' && ! $flux.dark"
                variant="mini"
            />
        </flux:button>

        <flux:menu>
            <flux:menu.item
                icon="sun"
                x-on:click="$flux.appearance = 'light'"
            >
                {{ __('setting.darkmode.light') }}
            </flux:menu.item>
            <flux:menu.item
                icon="moon"
                x-on:click="$flux.appearance = 'dark'"
            >
                {{ __('setting.darkmode.dark') }}
            </flux:menu.item>
            <flux:menu.item
                icon="computer-desktop"
                x-on:click="$flux.appearance = 'system'"
            >
                {{ __('setting.darkmode.system') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</div>
