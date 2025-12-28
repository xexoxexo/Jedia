@extends('layouts.merchant')

@section('title', 'Merchant Home')

@section('home', 'text-primary')

@section('content')
    <section>
        <div class="bg-black/5 rounded-lg p-8 mb-8">
            <p class="font-bold">Pending Orders</p>
            @if (Auth::user()->merchant->pendings()->count() > 0)
                <div class="flex flex-wrap -m-2 mt-4">
                    @foreach (Auth::user()->merchant->pendings as $pending)
                        <div class="w-1/2 p-2">
                            <div class="bg-white rounded-md p-4">
                                <div class="flex items-center gap-4 text-black mb-4 text-sm">
                                    <p class="font-bold">{{ $pending->header->user->username }}</p>
                                    <p>{{ date('d M Y', strtotime($pending->header->date)) }}</p>
                                    <p>{{ date('H:i', strtotime($pending->header->date)) }}</p>
                                </div>
                                <div class="mb-4 flex gap-4 text-black">
                                    <img src="{{ asset($pending->product->images[0]->image) }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                    <div>
                                        <p class="text-md gap-4 text-md font-bold">{{ $pending->product->name }}</p>
                                        <p class="text-gray text-sm font-normal">{{ $pending->variant->name}}</p>
                                        <p class="text-gray text-sm">{{ $pending->quantity }} pcs x @money($pending->price)</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <form class="w-1/2" action="{{ route('order.update', ['th_id' => $pending->transaction_id, 'pr_id' => $pending->product_id, 'va_id' => $pending->variant_id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <x-button type="submit" variant="red" block name="status" value="Rejected">Reject Order</x-button>
                                    </form>
                                    <form class="w-1/2" action="{{ route('order.update', ['th_id' => $pending->transaction_id, 'pr_id' => $pending->product_id, 'va_id' => $pending->variant_id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <x-button type="submit" variant="primary" block name="status" value="Shipping">Shipping Order</x-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center">
                    <img src="{{ asset('img/merchants/no-pending.png') }}" alt="" class="inline-flex h-40 mb-4 object-cover">
                    <p class="font-bold text-xl mb-2">No Pending Orders</p>
                    <p>Start promoting your products to reach more customers</p>
                </div>
            @endif
        </div>
        <div class="bg-black/5 rounded-lg p-8">
            <p class="font-bold">Shipped Orders</p>
            @if (Auth::user()->merchant->shippings()->count() > 0)
                <div class="flex flex-wrap -m-2 mt-4">
                    @foreach (Auth::user()->merchant->shippings as $shipping)
                        <div class="w-1/3 p-2">
                            <div class="bg-white rounded-md p-4 h-full">
                                <div class="flex items-center gap-4 text-black mb-4 text-sm">
                                    <p class="font-bold">@str_limit($shipping->header->user->username)</p>
                                    <p>{{ date('d M Y', strtotime($shipping->header->date)) }}</p>
                                    <p>{{ date('H:i', strtotime($shipping->header->date)) }}</p>
                                </div>
                                <div class="mb-4 flex gap-4 text-black">
                                    <img src="{{ asset($shipping->product->images[0]->image) }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                    <div>
                                        <p class="text-md gap-4 text-md font-bold">@str_limit($shipping->product->name)</p>
                                        <p class="text-gray text-sm font-normal">@str_limit($shipping->variant->name)</p>
                                        <p class="text-gray text-sm">{{ $shipping->quantity }} pcs x @money($shipping->price)</p>
                                    </div>
                                </div>
                                <hr class="text-gray-light mb-4">
                                <div class="text-sm mb-4">
                                    <p class="font-semibold mb-2 text-black">Shipping Details</p>
                                    <p class="text-gray mb-2">Shipping Type: <span class="text-black">{{ $shipping->shipment->name }}</span></p>
                                    <p class="text-gray">Shipping Date: <span class="text-black">{{ date('d M Y', strtotime($shipping->header->date)) }}</span></p>
                                </div>
                                <div class="text-sm text-gray">
                                    <p class="font-semibold mb-2 text-black">Destination Address</p>
                                    <p class="mb-2">{{ $shipping->header->location->city }}</p>
                                    <p>{{ $shipping->header->location->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center">
                    <img src="{{ asset('img/merchants/no-shipping.png') }}" alt="" class="inline-flex h-40 mb-4 object-cover">
                    <p class="font-bold text-xl mb-2">No Shipped Orders</p>
                    <p>Start completing pending orders</p>
                </div>
            @endif
        </div>
    </section>
@endsection
