@extends('layouts.profile')

@section('title', 'Following List')

@section('following', 'text-green-500')

@section('content')
    <section class="text-black">
        <p class="font-bold text-xl mb-4">Following</p>
        @if (Auth::user()->followings->count() == 0)
            <div class="flex gap-4">
                <img src="{{ asset('img/general/no-following.png') }}" alt="" class="h-40 object-cover">
                <div>
                    <p class="font-bold text-lg">No Following</p>
                    <p class="text-gray">Find your favorite store and follow them to get the latest update!</p>
                </div>
            </div>
        @else
            <div class="flex flex-wrap -m-2">
                @foreach (Auth::user()->followings as $following)
                    <div class="w-full p-2">
                        <div class="border border-gray-light rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset($following->merchant->getImage()) }}" alt="" class="h-14 w-14 object-cover rounded-full">
                                <div>
                                    <p class="font-bold text-black">@str_limit($following->merchant->name)</p>
                                    <p class="text-xs text-gray">@str_limit($following->merchant->location->city)</p>
                                </div>
                                <x-button href="{{ route('merchant.show', ['id' => $following->merchant->id]) }}" variant="primary" outline class="ms-auto">View Shop</x-button>
                            </div>

                            @if ($following->merchant->products->count() > 0)
                                <div class="flex gap-4 mt-4">
                                    @foreach ($following->merchant->products->take(3) as $following->merchant_product)
                                        <a href="{{ route('products.show', ['id' => $following->merchant_product->id]) }}" class="w-1/3">
                                            <img src="{{ asset($following->merchant_product->images[0]->image) }}" alt="" class="w-full h-24 object-cover mb-2 rounded-lg">
                                            <p class="font-semibold text-sm">@money($following->merchant_product->variants->min('price'))</p>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
