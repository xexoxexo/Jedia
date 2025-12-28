@extends('layouts.chat')

@section('title', 'Chat Index')

@section('content')
    <div class="flex flex-col justify-center items-center h-full bg-white py-16">
        <img src="{{ asset('img/chat/chat.png') }}" alt="" class="h-40 inline-flex mb-8">
        <p class="text-black font-bold text-2xl mb-2">Welcome To Chat</p>
        <p class="text-gray text-semibold mb-4 text-sm">Select message to start conversation</p>
    </div>
@endsection
