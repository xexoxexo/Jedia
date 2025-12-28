@extends('layouts.chat')

@section('title', 'Chat Show')

@section('content')
    <div class="h-20 flex items-center gap-4 px-4 bg-white border-b border-gray-light absolute top-0 left-0 w-full">
        @switch($chat_type)
            @case('user')
                <img
                    src="{{ asset($room->roomable_merchant()->merchant->getImage()) }}"
                    alt=""
                    class="rounded-full w-14 h-14 object-cover"
                />
                <p>{{ $room->roomable_merchant()->merchant->name }}</p>
                @break
            @case('merchant')
                <img
                    src="{{ asset($room->roomable_user()->user->getImage()) }}"
                    alt=""
                    class="rounded-full w-14 h-14 object-cover"
                />
                <p>{{ $room->roomable_user()->user->username }}</p>
                @break
        @endswitch
    </div>

    <div class="p-4 flex flex-col min-h-96 max-h-96 overflow-y-auto my-20">
        <div class="flex flex-col min-h-full max-h-full gap-4">
            @foreach ($room->messages as $message)
                @switch($chat_type)
                    @case('user')
                        @if ($message->messageable_id == $sender->id && $message->messageable_type == $chat_type)
                            <div class="flex items-end gap-4 max-w-96 ms-auto">
                                <div class="text-end">
                                    <p class="text-black mb-1 text-sm">{{ $sender->username }}</p>
                                    <p class="bg-gray-dark text-white py-2 px-4 rounded-lg rounded-br-none mb-1 inline-flex">{{ $message->message}}</p>
                                    <p class="text-xs text-gray">{{ $message->read_at }}</p>
                                </div>
                                <img src="{{ asset($sender->getImage()) }}" alt="" class="w-8 h-8 rounded-full">
                            </div>
                        @else
                            <div class="flex items-end gap-4 max-w-96">
                                <img src="{{ asset($room->roomable_merchant()->merchant->getImage()) }}" alt="" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-black mb-1 text-sm">{{ $room->roomable_merchant()->merchant->name }}</p>
                                    <p class="bg-gray-dark text-white py-2 px-4 rounded-lg rounded-bl-none mb-1 inline-flex">{{ $message->message }}</p>
                                    <p class="text-xs text-gray">{{ $message->read_at }}</p>
                                </div>
                            </div>
                        @endif
                        @break
                    @case('merchant')
                        @if ($message->messageable_id == $sender->id && $message->messageable_type == $chat_type)
                            <div class="flex items-end gap-4 max-w-96 ms-auto">
                                <div class="text-end">
                                    <p class="text-black mb-1 text-sm">{{ $sender->name }}</p>
                                    <p class="bg-gray-dark text-white py-2 px-4 rounded-lg rounded-br-none mb-1 inline-flex">{{ $message->message }}</p>
                                    <p class="text-xs text-gray">{{ $message->read_at }}</p>
                                </div>
                                <img src="{{ asset($sender->getImage()) }}" alt="" class="w-8 h-8 rounded-full">
                            </div>
                        @else
                            <div class="flex items-end gap-4 max-w-96">
                                <img src="{{ asset($room->roomable_user()->user->getImage()) }}" alt="" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-black mb-1 text-sm">{{ $room->roomable_user()->user->username }}</p>
                                    <p class="bg-gray-dark text-white py-2 px-4 rounded-lg rounded-bl-none mb-1 inline-flex">{{ $message->message }}</p>
                                    <p class="text-xs text-gray">{{ $message->read_at }}</p>
                                </div>
                            </div>
                        @endif
                        @break
                @endswitch
            @endforeach
        </div>
    </div>


    <div class="h-20 absolute bottom-0 left-0 w-full flex items-center px-4">
        @switch($chat_type)
            @case('user')
                <form action="{{ route('chats.store', ['room_id' => $room->id]) }}" method="POST" class="flex gap-4 w-full">
                    @csrf
                    <x-form.input type="text" name="message" placeholder="Send something as {{ $sender->username }}" required maxlength="255"/>
                    <x-button type="submit" variant="primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </x-button>
                </form>
                @break
            @case('merchant')
                <form action="{{ route('merchant.chats.store', ['room_id' => $room->id]) }}" method="POST" class="flex gap-4 w-full">
                    @csrf
                    <x-form.input type="text" name="message" placeholder="Send something as {{ $sender->name }}" required maxlength="255"/>
                    <x-button type="submit" variant="primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </x-button>
                </form>
                @break
        @endswitch
    </div>
@endsection
