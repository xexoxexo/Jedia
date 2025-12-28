@php
    $discount = $product->lowestDiscount();
@endphp

<a href="{{ route('products.show', ['id' => $product->id]) }}" class="w-1/6 p-2 z-10">
    <div class="rounded-lg overflow-hidden border border-gray-light hover:border-primary cursor-pointer bg-white h-full">
        <div class="relative">
            <img src="{{ asset($product->images[0]->image) }}" alt="" class="w-full aspect-[4/3] object-cover @if($discount != null) {{ 'rounded-br-3xl' }} @endif">
            @if ($discount != 0)
                <img src="{{ asset('img/deals/deals-product-image-accent.png') }}" alt="" class="absolute bottom-0 left-0 h-6">
                <p class="absolute bottom-1 left-2 text-white text-sm font-semibold">
                    @if ($product->flash_sale != null && (date('H') >= 22 && date('H') <= 23))
                        {{ 'Flash Sale' }}
                    @else
                        {{ $product->lowestPromo()->promo->promo_name }}
                    @endif
                </p>
            @endif
        </div>
        <div class="p-2 text-sm">
            <p class="text-black">@str_limit($product->name)</p>
            <p class="font-bold text-black">@money($product->lowestPriceVariant()->price - ($product->lowestPriceVariant()->price * $discount / 100))</p>
            @if ($discount != 0)
                <p><strike class="text-gray">@money($product->lowestPriceVariant()->price)</strike> <span class="text-red font-bold">{{ $discount }}%</span></p>
            @endif
            <div class="text-gray">
                <p >@str_limit($product->merchant->name)</p>
                <p>{{ $product->soldCount() }} | Terjual</p>
            </div>
        </div>
    </div>
</a>
