@extends('layouts.merchant')

@section('title', 'Merchant Profile')

@section('transactions', 'text-primary')

@section('content')
    <section>
        <p class="text-xl font-bold mb-4">Transaction History</p>

        @if (Auth::user()->merchant->transactions->count() > 0)
            @foreach (Auth::user()->merchant->transactions as $td)
                <div class="rounded-lg border border-gray-light p-4 mb-4 hover:border-primary">
                    <div>
                        <div class="flex items-center gap-4 text-black mb-4">
                            <span class="flex items-center gap-2 text-black font-semibold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                Shopping
                            </span>
                            <p>{{ date('d M Y', strtotime($td->header->date)) }}</p>
                            <p>{{ date('H:i', strtotime($td->header->date)) }}</p>
                            @switch($td->status)
                                @case('Pending')
                                    <p class="text-yellow-500 bg-yellow-100 px-2">{{ $td->status }}</p>
                                    @break
                                @case('Shipping')
                                    <p class="text-blue-500 bg-blue-100 px-2">{{ $td->status }}</p>
                                    @break
                                @case('Rejected')
                                    <p class="text-red bg-red-light px-2">{{ $td->status }}</p>
                                    @break
                                @case('Completed')
                                    <p class="text-primary bg-primary-light px-2">{{ $td->status }}</p>
                                    @break
                            @endswitch
                            <p class="text-gray">{{ $td->header->id }}</p>
                        </div>
                        <p class="font-bold mb-4">{{ $td->product->merchant->name }}</p>
                        <div class="flex gap-4 text-black">
                            <a href="{{ route('products.show', ['id' => $td->product->id]) }}" class="w-16 h-16">
                                <img src="{{ asset($td->product->images[0]->image) }}" alt="" class="w-full h-full object-cover rounded-lg">
                            </a>
                            <div>
                                <p class="text-md flex items-center gap-4 text-md font-bold">{{ $td->product->name }} <span class="text-gray text-sm font-normal">{{ $td->variant->name}}</span></p>
                                <p class="text-gray text-sm">{{ $td->quantity }} pcs x @money($td->price)</p>
                            </div>
                            <div class="ms-auto">
                                <div class="flex justify-end">
                                    <div class="border-s border-gray-light px-4 inline-flex flex-col">
                                        <p class="text-gray">Total Price</p>
                                        <p class="font-bold">@money($td->total_paid)</p>
                                    </div>
                                </div>
                                <div>
                                    @switch($td->status)
                                        @case('Completed')
                                            @if ($td->header->review($td->product_id, $td->variant_id) != null)
                                                <x-button variant="primary" class="mt-4" outline href="{{ route('reviews.show', ['review_id' => $td->header->review($td->product_id, $td->variant_id)->id]) }}">See Reviews</x-button>
                                            @endif
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-lg font-bold">You got no history transaction yet.</p>
        @endif
    </section>
@endsection
