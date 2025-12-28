@extends('layouts.dashboard')

@section('title', 'Category Show')

@section('content')
    <section>
        <p class="font-bold text-2xl mb-4 text-gray">Category Title : <span class="text-black">{{ $category->name }}</span></p>

        <div class="flex flex-wrap -m-2">
            @foreach ($category->products as $pp)
                <x-product.card :product="$pp" />
            @endforeach
        </div>
    </section>
@endsection
