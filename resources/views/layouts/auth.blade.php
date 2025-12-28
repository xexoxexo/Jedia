@extends('layouts.document')

@section('root')
    <x-loading />

    <div class="root w-screen h-screen flex flex-col justify-between items-center py-8">
        <x-logo />

        <div>
            @yield('content')
        </div>

        <p class="text-primary font-semibold">&copy; DuTiSa, Breaking and Overcoming Challenges Through Courage, Hardwork, and Persistence</p>
    </div>
@endsection
