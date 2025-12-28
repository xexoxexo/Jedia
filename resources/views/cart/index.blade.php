@extends('layouts.dashboard')

@section('title', 'Cart')

@section('cart', 'text-primary')

@section('content')
    @if (Auth::user()->carts->count() === 0)
        <section class="text-center mb-8">
            <img src="{{ asset('img/checkout/cart.jpg') }}" alt="" class="inline-flex mb-4 h-32">
            <p class="font-bold text-2xl">Your Cart is Empty</p>
            <p class="text-gray mb-4">Make your dream come true now!</p>
            <x-button href="{{ route('home.index') }}" variant="primary">Shop Now</x-button>
        </section>
    @else
        <section class="flex gap-8 mb-8">
            <div class="w-2/3">
                <p class="text-2xl font-bold mb-4 text-black">Cart</p>
                <div>
                    <div class="h-1 bg-gray-light mb-4"></div>
                    @php
                        $grand_total = 0;
                    @endphp

                    @foreach (Auth::user()->carts as $cart)
                        @php
                            $grand_total += $cart->variant->price;
                        @endphp
                        <div class="px-8">
                            <p class="font-bold font-lg text-black">{{ $cart->product->merchant->name }}</p>
                            <p class="text-gray mb-4">{{ $cart->product->merchant->location->city }}</p>
                            <div class="mb-4 flex gap-4 text-black">
                                <a href="{{ route('products.show', ['id' => $cart->product->id]) }}" class="w-16 h-16">
                                    <img src="{{ $cart->product->images[0]->image }}" alt="" class="w-full h-full object-cover rounded-lg">
                                </a>
                                <div>
                                    <p class="text-md">{{ $cart->product->name }}</p>
                                    <p class="text-md font-bold">@money ($cart->variant->price)</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mb-4">
                            <div class="flex items-center gap-16">
                                <button class="text-gray">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                <div class="border-b border-gray-light flex items-center gap-2">
                                    <button class="text-gray">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <input type="number" min="1" value="{{ $cart->quantity }}" class="text-center outline-none w-12">
                                    <button class="text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="h-1 bg-gray-light mb-4"></div>
                    @endforeach
                </div>
            </div>
            <div class="w-1/3">
                <div class=" border border-gray-light rounded-xl p-4">
                    <p class="text-lg font-bold mb-4 text-black">Shopping Summary</p>
                    <p class="flex justify-between mb-2 text-gray">Total Price ({{ Auth::user()->carts->count() }} item) <span>@money($grand_total)</span></p>
                    <hr class="mb-2 text-gray-light">
                    <p class="flex justify-between text-xl font-bold mb-4 text-black">Grand Total <span>@money($grand_total)</span></p>
                    <x-button href="{{ route('checkout.index') }}" variant="primary" type="submit" block>Buy</x-button>
                </div>
            </div>
        </section>
    @endif

    @include('product.section')
@endsection

@push('scripts')
    @include('product.script')
@endpush
