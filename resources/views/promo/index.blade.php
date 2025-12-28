@extends('layouts.dashboard')

@section('title', 'Promo Page')

@push('styles')
    <style>
        .slider {
            display: flex;
            aspect-ratio: 16 / 5;
            overflow-x: hidden;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            box-shadow: 0 1.5rem 3rem -0.75rem hsla(0, 0%, 0%, 0.25);
        }

        .slider img {
            scroll-snap-align: start;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <section class="relative mt-0 mb-8 mx-auto text-black">
        <p class="font-bold text-2xl mb-4">{{ $promo->promo_name }}</p>
        <div class="slider rounded-lg mb-4">
            <a href="{{ url('/promos/' . $promo->id) }}" style="flex: 1 0 100%">
                <img src="{{ asset($promo->promo_image) }}" alt="" class="cursor-pointer w-full h-full">
            </a>
        </div>
        <p>{{ $promo->promo_description }}</p>
    </section>

    <section>
        <p class="text-xl font-bold mb-4 text-black">Our Best Deals</p>

        <div class="flex flex-wrap -m-2">
            @foreach ($promo->products as $pp)
                <x-product.card :product="$pp->product" />
            @endforeach
        </div>
    </section>
@endsection
