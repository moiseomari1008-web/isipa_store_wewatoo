<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('client.boutique') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="shopping-bag" href="{{ route('client.boutique') }}" :current="request()->routeIs('client.boutique')" wire:navigate>
                    Boutique
                </flux:navbar.item>
                <flux:navbar.item icon="shopping-cart" href="{{ route('panier.index') }}" :current="request()->routeIs('panier.*')" wire:navigate>
                    Panier
                </flux:navbar.item>
                <flux:navbar.item icon="clipboard-document-list" href="{{ route('client.commandes.index') }}" :current="request()->routeIs('client.commandes.*')" wire:navigate>
                    Commandes
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
                <flux:tooltip content="Retour a l'accueil" position="bottom">
                    <flux:navbar.item class="h-10 [&>div>svg]:size-5" icon="arrow-uturn-left" href="{{ route('home') }}" label="Accueil" />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('client.boutique') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="Client">
                    <flux:navlist.item icon="shopping-bag" href="{{ route('client.boutique') }}" :current="request()->routeIs('client.boutique')" wire:navigate>
                        Boutique
                    </flux:navlist.item>
                    <flux:navlist.item icon="shopping-cart" href="{{ route('panier.index') }}" :current="request()->routeIs('panier.*')" wire:navigate>
                        Panier
                    </flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" href="{{ route('client.commandes.index') }}" :current="request()->routeIs('client.commandes.*')" wire:navigate>
                        Commandes
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <a href="{{ route('home') }}" class="rounded-2xl border border-zinc-200 px-4 py-3 text-sm font-semibold text-zinc-700 dark:border-zinc-700 dark:text-zinc-200">
                Retour a la vitrine
            </a>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
