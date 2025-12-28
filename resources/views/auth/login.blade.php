@extends('layouts.auth')

@section('title', 'Login')

@push('styles')
    <style>
        .root {
            background: url("{{ asset('img/backgrounds/login-bg.png') }}") center no-repeat;
            background-size: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="shadow-2xl p-8 rounded-lg bg-white h-auto">
        <p class="text-center text-2xl font-bold mb-2 text-black">Welcome Back!</p>

        <div class="text-center mb-4 text-black">
            <a href="{{ route('register.index') }}">Don't have an account? <span class="text-primary">Register</span></a>
        </div>

        <a href="/auth/redirect" class="border border-gray-light text-black w-full flex items-center justify-between px-4 py-1 mb-4 hover:border-primary rounded-md">
            <img src="{{ asset('img/google.png') }}" alt="" class="w-4 h-4">
            Google
            <p></p>
        </a>

        <div class="flex justify-between items-center gap-4 mb-4">
            <hr class="w-32 border border-gray-light">
            <p>or</p>
            <hr class="w-32 border border-gray-light">
        </div>

        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf

            <div class="mb-4">
                <x-form.label for="email">Email</x-form.label>
                <x-form.input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Example: email@tokonjedia.com" required maxlength="255"/>
            </div>
            <div class="mb-4">
                <x-form.label for="password">Password</x-form.label>
                <x-form.input type="password" name="password" id="password" placeholder="Input Password" required maxlength="255"/>
            </div>

            @if ($errors->any())
                <div class="text-gray mb-4">
                    <p>Requirements:</p>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <x-button type="submit" variant="primary" block>Login</x-button>
        </form>
    </div>
@endsection
