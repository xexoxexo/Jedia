@extends('layouts.profile')

@section('title', 'History Transaction')

@section('history', 'text-green-500')

@section('content')
    <section>
        @if (Auth::user()->transaction_headers->count() > 0)
            @foreach (Auth::user()->transaction_headers as $th)
                @if ($th->location_id == null)
                    <div class="rounded-lg border border-gray-light p-4 mb-4 hover:border-primary">
                        <div class="flex items-center gap-4 text-black mb-4">
                            <span class="flex items-center gap-2 text-black font-semibold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                    </svg>
                                Electric
                            </span>
                            <p>{{ date('d M Y', strtotime($th->date)) }}</p>
                            <p>{{ date('H:i', strtotime($th->date)) }}</p>
                            <p class="text-primary bg-primary-light px-2">
                                Completed
                            </p>
                            <p class="text-gray">{{ $th->id }}</p>
                        </div>
                        <div class="text-gray">
                            <p>Electric Token : <span class="text-black">{{ $th->electric()->electric_token }}</span></p>
                            <p>Subscription Number : <span class="text-black">{{ $th->electric()->subscription_number }}</span></p>
                            <p>Nominal : <span class="text-black">@money($th->electric()->nominal)</span></p>
                        </div>
                    </div>
                @else
                    @foreach ($th->details as $td)
                        <div class="rounded-lg border border-gray-light p-4 mb-4 hover:border-primary">
                            <div>
                                <div class="flex items-center gap-4 text-black mb-4">
                                    <span class="flex items-center gap-2 text-black font-semibold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                        Shopping
                                    </span>
                                    <p>{{ date('d M Y', strtotime($th->date)) }}</p>
                                    <p>{{ date('H:i', strtotime($th->date)) }}</p>
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
                                    <p class="text-gray">{{ $th->id }}</p>
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
                                            <div class="border-s border-gray-light px-4 mb-4 inline-flex flex-col">
                                                <p class="text-gray">Total Price</p>
                                                <p class="font-bold">@money($td->total_paid)</p>
                                            </div>
                                        </div>
                                        <div>
                                            @switch($td->status)
                                                @case('Pending')
                                                    <form action="{{ route('chats.redirect', ['merchant_id' => $td->product->merchant->id]) }}" method="POST">
                                                        @csrf

                                                        <x-button type="submit" variant="primary">Chat Seller</x-button>
                                                    </form>
                                                    @break
                                                @case('Shipping')
                                                    <form action="{{ route('order.update', ['th_id' => $td->transaction_id, 'pr_id' => $td->product_id, 'va_id' => $td->variant_id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <x-button variant="primary" name="status" value="Completed" type="submit">Confirm Received</x-button>
                                                    </form>
                                                    @break
                                                @case('Completed')
                                                    @if ($th->review($td->product_id, $td->variant_id) == null)
                                                        <x-button variant="primary" href="{{ route('reviews.create', ['th_id' => $td->transaction_id, 'pr_id' => $td->product_id, 'va_id' => $td->variant_id]) }}">Give Reviews and Buy Again</x-button>
                                                    @else
                                                        <x-button variant="primary" outline href="{{ route('reviews.show', ['review_id' => $th->review($td->product_id, $td->variant_id)->id]) }}">See Reviews</x-button>
                                                    @endif
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        @else
            <p class="text-lg font-bold">You got no history transaction yet.</p>
        @endif
    </section>
@endsection
