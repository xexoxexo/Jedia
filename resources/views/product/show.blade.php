@extends('layouts.dashboard')

@section('title', 'Product Detail')

@section('content')
    @php
        $lp = $product->lowestPriceVariant();
        $discount = $product->lowestDiscount();
    @endphp
    <section class="flex gap-8">
        <div class="w-1/4">
            <img
                src="{{ asset($product->images[0]->image) }}"
                alt=""
                class="w-full h-80 object-cover rounded-lg mb-2"
                id ="product_image"
            />
            <div class="flex gap-2">
                @foreach ($product->images as $pi)
                <img
                    src="{{ asset($pi->image) }}"
                    alt=""
                    onmouseover="previewImage(event)"
                    class="w-14 h-14 rounded-lg hover:border-2 hover:border-primary"
                />
                @endforeach
            </div>
        </div>
        <div class="w-1/2">
            <p class="font-bold text-2xl text-black" id="product-name">{{ $product->name }}</p>
            <p class="flex gap-2 text-sm items-center mb-4 text-black">
                Sold {{ $product->soldCount() }} &#183;
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"
                        class="text-yellow-500 fill-yellow-500"
                    />
                </svg>
                {{ $product->reviewsAverage() }} ({{ $product->reviews()->count() }} review)
            </p>
            <p class="font-bold text-3xl mb-4 text-black" id="product-new-price">
                @money($lp->price - ($lp->price * $discount / 100))
            </p>
            @if ($discount != 0)
                <p class="flex items-center gap-2 text-sm mb-4">
                    <span class="text-red bg-red-light px-2 py-1 rounded-md"
                        >{{ $discount }}%</span
                    >
                    <strike class="text-gray" id="product-original-price"
                        >@money($product->lowestPriceVariant()->price)</strike
                    >
                </p>
            @endif
            <p class="mb-4 text-black">
                Choose Variant: <span class="text-gray">{{ $lp->name }}</span>
            </p>
            @foreach ($product->variants as $key => $pv)
                @if ($pv->id === $lp->id)
                    <button
                        id="{{ $pv->id }}"
                        type="button"
                        onclick="selectVariant('{{ $pv->name }}', {{ $pv->price }}, '{{ $pv->id }}', {{ $pv->stock }})"
                        class="button-variant inline-flex rounded-xl p-2 bg-primary-light text-primary border border-primary mb-4 me-2"
                    >
                        {{ $pv->name }}
                    </button>
                @else
                    <button
                        id="{{ $pv->id }}"
                        type="button"
                        onclick="selectVariant('{{ $pv->name }}', {{ $pv->price }}, '{{ $pv->id }}', {{ $pv->stock }})"
                        class="button-variant inline-flex rounded-xl p-2 text-gray border border-gray-light mb-4 me-2 hover:border-primary hover:text-primary"
                    >
                        {{ $pv->name }}
                    </button>
                @endif
            @endforeach
            <div class="mb-4">
                <button
                    class="inline-flex text-primary px-4 py-2 border-b-2 border-primary font-bold"
                >
                    Detail
                </button>
            </div>
            <div class="text-sm mb-4 text-black">
                <p class="mb-2">
                    <span class="text-gray">Condition: </span>{{
                    $product->condition }}
                </p>
                <p>{{ $product->description }}</p>
            </div>
            <div class="flex items-center gap-4 text-sm mb-16">
                <a class="w-14 h-14" href="{{ route('merchant.show', ['id' => $product->merchant->id]) }}">
                    <img
                        src="{{ asset($product->merchant->getImage()) }}"
                        alt=""
                        class="rounded-full w-full h-full object-cover"
                    />
                </a>
                <div>
                    <p class="font-bold mb-2 text-black">{{ $product->merchant->name }}</p>
                    @if (Auth::check())
                        @if (Auth::user()->following($product->merchant->id) == null)
                            <form action="{{ route('following-list.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="merchant_id" value="{{ $product->merchant->id }}">
                                <button type="submit" class="inline-flex py-1 px-4 text-primary border border-primary rounded-md hover:text-white hover:bg-primary">Follow</button>
                            </form>
                        @else
                            <form action="{{ route('following-list.destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="merchant_id" value="{{ $product->merchant->id }}">
                                <button type="submit" class="inline-flex py-1 px-4 text-primary border border-primary rounded-md hover:text-white hover:bg-primary">Unfollow</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.index') }}" class="inline-flex py-1 px-4 text-primary border border-primary rounded-md hover:text-white hover:bg-primary">Follow</a>
                    @endif
                </div>
            </div>

            <div>
                <p class="font-bold text-black mb-8">REVIEWS</p>

                @if ($product->reviews->count() == 0)
                    <div class="rounded-lg border border-gray-light flex gap-4 p-8 items-center">
                        <img src="{{ asset('img/general/no-review.png') }}" alt="" class="w-20">
                        <div>
                            <p class="font-bold text-3xl text-black">There is no review yet.</p>
                            <p class="text-gray">Be the first one to buy and review the product.</p>
                        </div>
                    </div>
                @else
                    <div class="max-h-96 overflow-y-auto border border-gray-light rounded-lg flex flex-col gap-8 p-4">
                        @foreach ($product->reviews as $review)
                            <div class="flex gap-4">
                                <img src="{{ asset($review->user->getImage()) }}" alt="" class="w-16 h-16">
                                <div class="flex-grow">
                                    <p class="font-semibold">{{ $review->user->username }}</p>
                                    <p class="text-xs text-gray">{{ $review->created_at }}</p>
                                    <p>{{ $review->message }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="w-1/4">
            @if ($discount != 0)
                <div class="relative mb-4">
                    <img src="{{ asset('img/deals/deals-product-image-accent.png') }}" class="h-12" />
                    <p class="absolute left-3 top-3 font-bold text-white">
                        @if ($product->flash_sale != null && (date('H') >= 22 && date('H') <= 23))
                            {{ 'Flash Sale' }}
                        @else
                            {{ $product->lowestPromo()->promo->promo_name }}
                        @endif
                    </p>
                </div>
            @endif

            <form action="{{ route('cart.store') }}" method="POST" class="border border-gray-light p-4 rounded-lg">
                @csrf
                @foreach ($product->variants as $key => $pv)
                    @if ($pv->id === $lp->id)
                        <input type="hidden" id="variant-id" name="variant_id" value="{{ $pv->id }}">
                    @endif
                @endforeach
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <p class="mb-4 text-black">Atur Jumlah dan Catatan</p>

                <div class="flex gap-4 items-center mb-4">
                    <div class="inline-flex items-center border border-gray-light rounded-md">
                        <button onclick="changeQty(this.innerHTML)" type="button" class="text-primary px-2 text-2xl">-</button>
                        <input
                            type="number"
                            name="product_quantity"
                            id="product-quantity"
                            value="1"
                            class="text-center w-16 focus:outline-none"
                            oninput="changeQty('')"
                        />
                        <button onclick="changeQty(this.innerHTML)" type="button" class="text-primary px-2 text-2xl">+</button>
                    </div>
                    <p class="text-black">
                        Stock Total: <span class="font-bold" id="product-total-stock">{{ $lp->stock }}</span>
                    </p>
                </div>

                <div class="flex items-end justify-between mb-4 text-black">
                    <p>Subtotal</p>
                    <div class="text-right">
                        @if ($discount != 0)
                            <strike class="text-gray" id="subtotal-original-price">@money($product->lowestPriceVariant()->price)</strike>
                        @endif
                        <p class="font-bold text-2xl" id="subtotal-new-price">
                            @if ($discount != 0)
                                @money($lp->price * $discount / 100)
                            @else
                                @money($lp->price)
                            @endif
                        </p>
                    </div>
                </div>

                @if ($product->trashed())
                    <x-button variant="gray" block>Product Not Available Anymore</x-button>
                @else
                    <x-button class="mb-2" type="submit" variant="primary" block>+ Add To Cart</x-button>
                    <x-button variant="primary" outline block>Buy Now</x-button>
                @endif
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var vDiscount, productNewPrice, productOriginalPrice, productTotalStock, productQuantity, subtotalNewPrice, subtotalOriginalPrice;

        window.onload = function() {
            vDiscount = {{ $discount }};
            productTotalStock = document.querySelector('#product-total-stock');
            productNewPrice = document.querySelector('#product-new-price');
            productOriginalPrice = document.querySelector('#product-original-price');
            productQuantity = document.querySelector('#product-quantity');
            subtotalNewPrice = document.querySelector('#subtotal-new-price');
            subtotalOriginalPrice = document.querySelector('#subtotal-original-price');
        }

        function changeQty(task, vPrice) {
            if (task === '+' && productQuantity.value < parseInt(productTotalStock.innerHTML)) {
                productQuantity.value++;
            } else if (task === "-" && productQuantity.value != 1) {
                productQuantity.value--;
            }

            var newPrice = parseInt(productNewPrice.innerHTML.trim().replace(/\D/g,''));
            subtotalNewPrice.innerHTML = formatRupiah(newPrice * productQuantity.value);

            if (productOriginalPrice != null) {
                var originalPrice = parseInt(productOriginalPrice.innerHTML.trim().replace(/\D/g,''));
                subtotalOriginalPrice.innerHTML = formatRupiah(originalPrice * productQuantity.value);
            }
        }

        function selectVariant(vName, vPrice, vId, vStock) {
            document.querySelector('#product-name').innerHTML = vName;
            productNewPrice.innerHTML = subtotalNewPrice.innerHTML = formatRupiah(vPrice - (vPrice * vDiscount / 100));
            productQuantity.value = 1;
            productTotalStock.innerHTML = vStock;

            if (productOriginalPrice != null && subtotalOriginalPrice != null) {
                productOriginalPrice.innerHTML = formatRupiah(vPrice);
                subtotalOriginalPrice.innerHTML = productOriginalPrice.innerHTML;
            }

            document.querySelectorAll('.button-variant').forEach((element) => {
                if (element.getAttribute('id') !== vId) {
                    element.className = 'button-variant inline-flex rounded-xl p-2 text-gray border border-gray mb-4 me-2 hover:border-primary hover:text-primary';
                } else {
                    element.className = 'button-variant inline-flex rounded-xl p-2 bg-primary-light text-primary border border-primary mb-4 me-2';
                }
            });

            document.querySelector("#variant-id").value = vId;
        }

        function previewImage(e)
        {
            document.querySelector('#product_image').setAttribute('src', event.target.getAttribute('src'));
        }
    </script>
@endpush
