<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ isset($title) ? __($title) : __('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
            rel="stylesheet"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-800">
        {{ $slot }}

        @persist('toast')
            <flux:toast position="bottom left right" />
        @endpersist

        @fluxScripts

        <script>
            const handleSessionExpiration = ({ status, preventDefault }) => {
                if (status === 419) {
                    Flux.toast({
                        heading: '{{ __('toast.logout.warning') }}',
                        variant: 'warning',
                    });
                    Livewire.navigate('{{ route('login.page') }}');
                    preventDefault();
                }
            };

            document.addEventListener('livewire:init', () => {
                Livewire.hook('request', ({ fail }) => {
                    fail(handleSessionExpiration);
                });
            });

            document.addEventListener('livewire:navigated', () => {
                Livewire.hook('request', ({ fail }) => {
                    fail(handleSessionExpiration);
                });
            });
        </script>
    </body>
</html>
