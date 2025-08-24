<flux:header sticky container class="flex items-center border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <div class="w-full flex items-center justify-between">
        <div class="flex items-center">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('home') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-app-logo />
            </a>
        </div>

        <div class="max-lg:hidden flex items-stretch gap-4">
            @foreach ($categories->take($min_category_number) as $elem)
                <a class="p-2 cursor-pointer hover:bg-zinc-200 rounded-md {{ $elem['is_current'] ? 'border-b-2 border-b-black rounded-none' : '' }}" href="{{ $elem['href'] }}">{{ $elem['name'] }}</a>
            @endforeach
            @if ($categories->count() > $min_category_number)
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down" class="!border-0 !shadow-none">{{ __('More') }}</flux:button>
                    <flux:menu class="overflow-hidden">
                        @foreach ($categories->skip($min_category_number) as $elem)
                            <flux:menu.item :href="$elem['href']" class="{{ $elem['is_current'] ? 'bg-zinc-200' : '' }}">{{ $elem['name'] }}</flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
            @endif
        </div>

        <div class="flex items-center">
            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Cart')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="shopping-cart" :href="route('cart')" :label="__('Cart')" />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            @if (Auth::check())
                <flux:dropdown position="top" align="end">
                    <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            @role('admin')
                                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                            @else
                                <flux:menu.item :href="route('client.settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                            @endrole
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
                @role('admin')
                    <div class="max-sm:hidden">
                        <flux:button :href="route('admin.dashboard')">{{ __('Admin Dashboard') }}</flux:button>
                    </div>
                @endrole
            @else
                <div class="flex gap-3 max-sm:hidden">
                    <flux:button :href="route('login')">{{ __('Login') }}</flux:button>
                    <flux:button :href="route('register')" variant="primary">{{ __('Register') }}</flux:button>
                </div>
            @endif
        </div>
    </div>
</flux:header>

<!-- Mobile Menu -->
<flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('home') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist.group heading="Categories" expandable :expanded="request()->routeIs('home')">
        @foreach ($categories as $elem)
            <flux:navlist.item class="" :href="$elem['href']" :current="$elem['is_current']">{{ $elem['name'] }}</flux:navbar.item>
        @endforeach
    </flux:navlist.group>


    <flux:spacer />

    <flux:navlist variant="outline">
        @if (auth()->check())
            @role('admin')
                <flux:navlist.item :href="route('admin.dashboard')">
                    {{ __('Admin Dashboard') }}
                </flux:navlist.item>
            @endrole
        @else
            <flux:navlist.item :href="route('login')">
                {{ __('Login') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('register')">
                {{ __('Register') }}
            </flux:navlist.item>
        @endif
    </flux:navlist>
</flux:sidebar>

<div class="mx-auto max-w-7xl">
    {{ $slot }}
</div>
