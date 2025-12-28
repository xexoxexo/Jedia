@extends('layouts.dashboard')

@section('title', 'Not Found')

@section('content')
    <div class="text-center py-8">
        <img src="{{ asset('img/general/error-not-found.png') }}" alt="" class="h-64 inline-flex mb-8">
        <p class="text-black font-bold text-2xl mb-2">Wadoeh, your destination is not found!</p>
        <p class="text-gray text-semibold mb-8 text-sm">Maybe there is a typo? Let's go back before dark!</p>
        <x-button variant="primary" href="{{ route('home.index') }}">Go back to home</x-button>
    </div>
@endsection
