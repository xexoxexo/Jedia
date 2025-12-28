@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="flex items-center justify-center gap-64">
        <img src="{{ asset('img/backgrounds/register-bg.png') }}" alt="" class="w-3/12 h-auto">

        <div class="shadow-2xl p-8 rounded-lg bg-white h-auto">
            <p class="text-center text-2xl font-bold mb-2 text-black">Register Now</p>

            <div class="text-center mb-4 text-black">
                <a href="{{ route('login.index') }}">Already have an account? <span class="text-primary">Login</span></a>
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

            <form action="{{ route('register.authenticate') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <x-form.label for="email">Email</x-form.label>
                    <x-form.input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Example: email@gmail.com" required maxlength="255"/>
                </div>
                <div class="mb-4">
                    <x-form.label for="password">Password</x-form.label>
                    <x-form.input type="password" name="password" id="password" placeholder="Input Password" required maxlength="255"/>
                </div>
                <div class="mb-4">
                    <x-form.label for="username">Username</x-form.label>
                    <x-form.input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Input Username" required maxlength="255"/>
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

                <x-button variant="primary" type="submit" block>Register</x-button>
            </form>
        </div>
    </div>
@endsection
