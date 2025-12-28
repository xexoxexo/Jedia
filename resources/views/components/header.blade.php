<header>
    <div class="flex justify-between px-8 py-2 bg-slate-100 text-sm text-gray-dark">
        <div>
            <a href="" class="hover:text-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
                Download TokoNJedia App
            </a>
        </div>

        <div class="flex gap-8">
            <a href="" class="hover:text-primary">Tentang Tokopedia</a>
            <a href="" class="hover:text-primary">Mitra Tokopedia</a>
            <a href="" class="hover:text-primary">Mulai Berjualan</a>
            <a href="" class="hover:text-primary">Promo</a>
            <a href="" class="hover:text-primary">TokoNJedia Care</a>
        </div>
    </div>

    <div class="flex justify-between items-start px-8 py-4 bg-white border-b border-b-gray-light text-sm gap-8">
        <div class="flex items-end gap-2">
            <div class="h-8 flex items-center">
                <x-logo />
            </div>

            @if (Auth::check() && Auth::user()->merchant != null)
                <p>Seller</p>
            @endif
        </div>

        <div class="flex-grow">
            <form action="{{ route('home.search') }}" method="GET">
                <x-form.input type="search" name="keyword" value="{{ Request::get('keyword') }}" placeholder="Search" required/>
            </form>

            @isset($recommendations)
                <div class="flex gap-4 text-sm text-gray mt-4">
                    @foreach ($recommendations as $recommendation)
                        <a href="{{ route('home.search', ['keyword' => $recommendation->name]) }}" name="keyword" class="hover:text-primary">{{ $recommendation->name }}</a>
                    @endforeach
                </div>
            @endisset
        </div>

        @if (Auth::check())
            <div class="flex items-center gap-4 h-8">
                <a href="{{ route('cart.index') }}" class="relative text-black hover:text-primary @yield('cart')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    @if (Auth::user()->carts->count() > 0)
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-red text-white rounded-full p-1 text-xs flex items-center justify-center">
                            {{ Auth::user()->carts->count() }}
                        </div>
                    @endif
                </a>
                <a href="{{ route('chats.index') }}" class="text-black hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </a>
            </div>

            @if (Auth::user()->merchant != null)
                <a href="{{ route('merchant.index') }}" class="flex items-center gap-2 text-black hover:text-primary @yield('merchant')">
                    <img src="{{ asset(Auth::user()->merchant->getImage()) }}" alt="" class="w-8 h-8 rounded-full object-cover">
                    <p>@str_limit(Auth::user()->merchant->name, 10)</p>
                </a>
            @else
                <x-button variant="primary" outline href="{{ route('merchant.register.index') }}">Be Merchant</x-button>
            @endif

            <a href="{{ route('general.index') }}" class="flex items-center gap-2 text-black hover:text-primary @yield('profile')">
                <img src="{{ asset(Auth::user()->getImage()) }}" alt="" class="w-8 h-8 rounded-full">
                <p>{{ Auth::user()->username }}</p>
            </a>
        @else
            <div class="flex gap-2">
                <x-button href="{{ route('login.index') }}" variant="primary">Login</x-button>
                <x-button href="{{ route('register.index') }}" variant="primary" outline>Register</x-button>
            </div>
        @endif
    </div>
</header>
