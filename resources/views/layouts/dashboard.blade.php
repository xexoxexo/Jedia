@extends('layouts.document')

@section('root')
    <x-loading />
    @isset($recommendations)
        <x-header :recommendations='$recommendations' />
    @else
        <x-header />
    @endisset

    <div class="py-8 flex justify-center">
        <div class="w-full max-w-screen-xl">
            @yield('content')
        </div>
    </div>

    <x-footer />
@endsection
