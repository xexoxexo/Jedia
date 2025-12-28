@extends('layouts.dashboard')

@section('title', 'Cart')

@section('cart', 'text-primary')

@section('modals')
    <div class="fixed top-0 left-0 bg-black/50 z-50 hidden" id="modal">
        <div class="flex w-screen h-screen items-center justify-center">
            <div class="p-8 bg-white rounded-lg w-1/3">
                <div class="text-2xl mb-8 font-bold flex items-center justify-between">
                    <p></p>
                    <p>Select Shipment Address</p>
                    <button type="button" class="text-gray hover:text-primary" onclick="toggleModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>

                @foreach (Auth::user()->locations as $key => $location)
                    @if ($key === 0)
                        <button class="w-full text-left mb-4 p-4 bg-primary-light border border-primary rounded-lg text-black tab-address tab-address-active" onclick="selectAddress(this, '{{ $location->city }}', '{{ $location->country }}', '{{ $location->notes }}', '{{ $location->postal_code }}', '{{ $location->id }}', {{ $location->latitude }}, {{ $location->longitude }})">
                    @else
                        <button class="w-full text-left mb-4 p-4 bg-white border border-gray-light rounded-lg hover:border-primary text-black tab-address" onclick="selectAddress(this, '{{ $location->city }}', '{{ $location->country }}', '{{ $location->notes }}', '{{ $location->postal_code }}', '{{ $location->id }}', {{ $location->latitude }}, {{ $location->longitude }})">
                    @endif
                            <p class="font-bold">{{ $location->user->username }}</p>
                            <p>{{ $location->city }}, {{ $location->country }}</p>
                            <p>{{ $location->notes }}, {{ $location->postal_code }}</p>
                        </button>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="flex gap-8 mb-8">
        <div class="w-2/3 text-black">
            <p class="text-2xl font-bold mb-4">Checkout</p>
            <p class="mb-4 font-bold text-sm">Shipping Address</p>
            <hr class="mb-4 text-gray-light">
            <p class="font-bold" id="lbl-username">{{ Auth::user()->username }}</p>
            <p id="lbl-city-country">{{ Auth::user()->locations[0]->city }}, {{ Auth::user()->locations[0]->country }}</p>
            <p class="text-gray mb-4" id="lbl-notes-postal_code">{{ Auth::user()->locations[0]->notes }}, {{ Auth::user()->locations[0]->postal_code }}</p>

            <x-button variant="gray" outline class="mb-4" onclick="toggleModal()">Choose other address</x-button>
            <div>
                <div class="h-1 bg-gray-light mb-4"></div>
                @php
                    $total_price = 0;
                @endphp

                @foreach (Auth::user()->carts as $cart)
                    @php
                        $total_price += $cart->variant->price;
                    @endphp
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <p class="font-bold font-lg">{{ $cart->product->merchant->name }}</p>
                            <p class="text-gray mb-4">{{ $cart->product->merchant->location->city }}</p>
                            <div class="flex gap-4">
                                <img src="{{ $cart->product->images[0]->image }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <p class="text-md">{{ $cart->product->name }}</p>
                                    <p class="text-md font-bold">@money ($cart->variant->price)</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3">
                            <p class="font-semibold text-xs mb-2">Choose Duration</p>
                            <x-form.select variant="primary" class="font-semibold input-shipment" merchant_latitude='{{ $cart->product->merchant->location->latitude }}' merchant_longitude='{{ $cart->product->merchant->location->longitude }}' product_id='{{ $cart->product->id }}' variant_id='{{ $cart->variant_id }}' price='{{ $cart->variant->price }}' onchange="selectShipment()">
                                <option value="" class="bg-white text-black">--- Shipment ---</option>
                                @foreach ($shipments as $shipment)
                                    <option shipment_id="{{ $shipment->id }}" shipment_base_price='{{ $shipment->base_price }}' shipment_variable_price='{{ $shipment->variable_price }}' class="bg-white text-black">{{ $shipment->name }}</option>
                                @endforeach
                            </x-form.select>
                        </div>
                    </div>
                @endforeach
                <div class="h-1 bg-gray-light mb-4"></div>
                <div class="flex justify-between font-bold">
                    <p>Subtotal</p>
                    <p>@money($total_price)</p>
                </div>
            </div>
        </div>
        <div class="w-1/3 text-black">
            <form action="{{ route('checkout.store') }}" method="POST" onsubmit="event.preventDefault(); proceedTransaction(this)" class="border border-gray-light rounded-xl p-4">
                @csrf

                <input type="hidden" id="user-location_id" name="user-location-id" value="{{ Auth::user()->locations[0]->id }}">
                <input type="hidden" name="transaction_details" id="transaction_details">
                <p class="text-lg font-bold mb-4">Shopping Summary</p>
                <p class="flex justify-between mb-2 text-gray">Total Price ({{ Auth::user()->carts->count() }} Product) <span>@money($total_price)</span></p>
                <div class="flex justify-between mb-2 text-gray">
                    <p>Shipping (<span id="lbl-shipping-product">0</span> Product)</p>
                    <p id="lbl-shipping-price">@money(0)</p>
                </div>
                <hr class="mb-2 text-gray-light">
                <p class="flex justify-between text-xl font-bold mb-4">Shopping Total <span id="lbl-shopping-total">@money($total_price)</span></p>
                <p class="text-gray text-sm mb-4">By purchasing products from tokoNJedia, I agree to the <span class="text-primary">terms and conditions</span></p>
                <x-button variant="primary" type="submit" block>Proceed Transaction</x-button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var user_latitude = '{{ Auth::user()->locations[0]->latitude }}';
        var user_longitude = '{{ Auth::user()->locations[0]->longitude }}';

        function proceedTransaction(formElement)
        {
            let n = document.querySelectorAll('.input-shipment').length;

            for (let i = 0; i < n; i++) {
                var inputShipment = document.querySelectorAll('.input-shipment')[i];

                if (inputShipment.value == '') {
                    alert("Please select shipment for all your products.");
                    return;
                }
            }

            var transaction_details = {};

            document.querySelectorAll('.input-shipment').forEach((element) => {
                var product_id = element.getAttribute('product_id');
                var variant_id = element.getAttribute('variant_id');
                var price = parseFloat(element.getAttribute('price'));
                var merchant_latitude = parseFloat(element.getAttribute('merchant_latitude'));
                var merchant_longitude = parseFloat(element.getAttribute('merchant_longitude'));
                var option = element.options[element.selectedIndex];
                var shipment_id = option.getAttribute('shipment_id');
                var shipment_base_price = parseFloat(option.getAttribute('shipment_base_price'));
                var shipment_variable_price = parseFloat(option.getAttribute('shipment_variable_price'));
                var user_latitude = parseFloat(user_latitude);
                var user_longitude = parseFloat(user_longitude);

                var km = haversineDistance(merchant_latitude, merchant_longitude, user_latitude, user_longitude);
                var shipment_price = shipment_base_price + (8 * shipment_variable_price / 1000);

                transaction_details[product_id] = {};
                transaction_details[product_id][variant_id] = {
                    'shipment_id': shipment_id,
                    'total_paid': price + shipment_price,
                };
            });
            document.querySelector('#transaction_details').value = JSON.stringify(transaction_details);

            formElement.submit();
        }

        function selectShipment()
        {
            var shipping_price = 0;
            var shipping_product = 0;

            document.querySelectorAll('.input-shipment').forEach((element) => {
                if (element.value == '') return;
                shipping_product++;

                var option = element.options[element.selectedIndex];
                var shipment_id = option.getAttribute('shipment_id');
                var merchant_latitude = parseFloat(element.getAttribute('merchant_latitude'));
                var merchant_longitude = parseFloat(element.getAttribute('merchant_longitude'));
                var shipment_base_price = parseFloat(option.getAttribute('shipment_base_price'));
                var shipment_variable_price = parseFloat(option.getAttribute('shipment_variable_price'));
                var user_latitude = parseFloat(user_latitude);
                var user_longitude = parseFloat(user_longitude);

                var km = haversineDistance(merchant_latitude, merchant_longitude, user_latitude, user_longitude);
                var price = shipment_base_price + (8 * shipment_variable_price / 1000);
                shipping_price += price;
            });

            $('#lbl-shipping-product').html(shipping_product);
            $('#lbl-shipping-price').html(formatRupiah(shipping_price));
            var shopping_total = parseFloat('{{ $total_price }}') + shipping_price;
            $('#lbl-shopping-total').html(formatRupiah(shopping_total));
        }

        function haversineDistance(merchant_latitude, merchant_longitude, user_latitude, user_longitude) {
            Number.prototype.toRad = function() {
                return this * Math.PI / 180;
            }

            var lat2 = user_latitude;
            var lon2 = user_longitude;
            var lat1 = merchant_latitude;
            var lon1 = merchant_longitude;

            var R = 6371; // km
            var x1 = lat2-lat1;
            var dLat = x1.toRad();
            var x2 = lon2-lon1;
            var dLon = x2.toRad();
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                            Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) *
                            Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c;

            return d;
        }

        function selectAddress(address, city, country, notes, postal_code, location_id, latitude, longitude)
        {
            var tabAddressActive = document.querySelector('.tab-address-active');

            if (tabAddressActive == address) return;

            shipping_price = 0;
            document.querySelectorAll('.input-shipment').forEach((element) => {
                element.selectedIndex = 0;
            });
            $('#lbl-shipping-product').html(0);
            $('#lbl-shipping-price').html(formatRupiah(0));
            $('#lbl-shopping-total').html(formatRupiah('{{ $total_price }}'));

            $('#lbl-city-country').html(city + ', ' + country);
            $('#lbl-notes-postal_code').html(notes + ', ' + postal_code);

            document.querySelectorAll('.tab-address').forEach((element) => {
                if (element == address) {
                    element.className = 'w-full text-left mb-4 p-4 bg-primary-light border border-primary rounded-lg text-black tab-address tab-address-active';
                } else {
                    element.className = 'w-full text-left mb-4 p-4 bg-white border border-gray-light rounded-lg hover:border-primary text-black tab-address';
                }
            });

            $('#user-location_id').val(location_id);
            user_latitude = latitude;
            user_longitude = longitude;
        }

        function toggleModal()
        {
            var modal = document.querySelector('#modal');

            $('#modal').fadeToggle();
        }
    </script>
@endpush
