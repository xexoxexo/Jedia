@extends('layouts.document')

@section('root')
    <x-loading />
    <x-header :recommendations='$recommendations' />

    <div class="py-8 flex justify-center">
        <div class="w-full max-w-screen-xl">
            @if ($sender->rooms->count() > 0)
                <section class="flex rounded-lg border border-gray-light">
                    <div class="w-1/3 p-4 border-e border-gray-light ">
                        <p class="text-lg font-bold mb-4">Chats</p>
                        @switch($chat_type)
                            @case('user')
                                <x-form.input type="search" placeholder="Search merchant"/>
                                @break
                            @case('merchant')
                                <x-form.input type="search" placeholder="Search user"/>
                                @break
                        @endswitch

                        <div class="max-h-96 overflow-y-auto">
                            @foreach ($sender->rooms as $room_item)
                                @switch($chat_type)
                                    @case('user')
                                        <a href="{{ route('chats.show', ['room_id' => $room_item->id]) }}" class="flex items-center gap-4 mt-4 hover:text-primary">
                                            <img
                                                src="{{ asset($room_item->roomable_merchant()->merchant->getImage()) }}"
                                                alt=""
                                                class="rounded-full w-14 h-14 object-cover"
                                            />
                                            <p>{{ $room_item->roomable_merchant()->merchant->name }}</p>
                                        </a>
                                        @break
                                    @case('merchant')
                                        <a href="{{ route('merchant.chats.show', ['room_id' => $room_item->id]) }}" class="flex items-center gap-4 mt-4 hover:text-primary">
                                            <img
                                                src="{{ asset($room_item->roomable_user()->user->getImage()) }}"
                                                alt=""
                                                class="rounded-full w-14 h-14 object-cover"
                                            />
                                            <p>{{ $room_item->roomable_user()->user->username }}</p>
                                        </a>
                                        @break
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                    <div class="w-2/3 bg-slate-100 relative">
                        @yield('content')
                    </div>
                </section>
            @else
                @switch($chat_type)
                    @case('user')
                        <div class="text-center py-16">
                            <img src="{{ asset('img/chat/chat.png') }}" alt="" class="h-40 inline-flex mb-8">
                            <p class="text-black font-bold text-2xl mb-2">Welcome To Chat</p>
                            <p class="text-gray text-semibold mb-4 text-sm">Explore to start conversation</p>
                            <x-button variant="primary" href="{{ route('home.index') }}">See store recommendations</x-button>
                        </div>
                        @break
                    @case('merchant')
                        <div class="text-center py-16">
                            <img src="{{ asset('img/chat/chat.png') }}" alt="" class="h-40 inline-flex mb-8">
                            <p class="text-black font-bold text-2xl mb-2">Welcome To Chat</p>
                            <p class="text-gray text-semibold mb-4 text-sm">No one has chat your store yet!</p>
                        </div>
                        @break
                @endswitch
            @endif
        </div>
    </div>

    <x-footer />
@endsection
