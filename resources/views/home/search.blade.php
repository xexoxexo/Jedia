@extends('layouts.dashboard')

@section('title', 'Search')

@section('content')
    <section class="mb-8">
        <div class="mb-4 flex gap-8 border-b-2 border-gray-light">
            <button class="text-primary flex gap-2 font-bold items-center border-b-2 border-primary pb-2 tab tab-active" onclick="changeTab(this)" id="tab-product">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                Product
            </button>
            <button class="text-gray flex gap-2 font-bold items-center border-b-2 border-transparent pb-2 hover:text-primary tab" onclick="changeTab(this)" id="tab-shop">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                Shop
            </button>
        </div>

        <p class="text-gray mb-4">Searching for <span class="text-black font-bold">"{{ $keyword }}"</span></p>

        @if ($sProducts->count() > 0)
            <section class="tab-section tab-section-active" id="section-product">
                <div class="flex flex-wrap -m-2">
                    @foreach ($sProducts as $sProduct)
                        <x-product.card :product="$sProduct" />
                    @endforeach
                </div>
            </section>
        @else
            <div class="tab-section tab-section-active" id="section-product">
                <div class="rounded-xl border border-gray-light py-8 px-16 gap-8 flex">
                    <img src="{{ asset('img/checkout/not-found.png') }}" alt="" class="h-32 object-cover">
                    <div>
                        <p class="font-bold text-xl mb-2">Oops, we think it hides somewhere</p>
                        <p class="mb-4">Try another keyword or check product recommendation below.</p>
                        <a href="{{ route('home.index') }}" class="inline-flex text-white bg-primary font-bold py-2 px-8 rounded-md border border-primary hover:text-primary hover:bg-white text-sm">Go Back Home</a>
                    </div>
                </div>
            </div>
        @endif

        @if ($sMerchants->count() > 0)
            <section class="hidden tab-section" id="section-shop">
                <div class="flex flex-wrap -m-2">
                    @foreach ($sMerchants as $sMerchant)
                        <div class="w-1/3 p-2">
                            <div class="border border-gray-light rounded-lg p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset($sMerchant->getImage()) }}" alt="" class="h-14 w-14 object-cover rounded-full">
                                    <div>
                                        <p class="font-bold text-black">@str_limit($sMerchant->name)</p>
                                        <p class="text-xs text-gray">@str_limit($sMerchant->location->city)</p>
                                    </div>
                                    <x-button href="{{ route('merchant.show', ['id' => $sMerchant->id]) }}" variant="primary" outline class="ms-auto">View Shop</x-button>
                                </div>

                                @if ($sMerchant->products->count() > 0)
                                    <div class="flex gap-4 mt-4">
                                        @foreach ($sMerchant->products->take(3) as $sMerchant_product)
                                            <a href="{{ route('products.show', ['id' => $sMerchant_product->id]) }}" class="w-1/3">
                                                <img src="{{ asset($sMerchant_product->images[0]->image) }}" alt="" class="w-full h-24 object-cover mb-2 rounded-lg">
                                                <p class="font-semibold text-sm">@money($sMerchant_product->variants->min('price'))</p>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <div class="hidden tab-section" id="section-shop">
                <div class="rounded-xl border border-gray-light py-8 px-16 gap-8 flex">
                    <img src="{{ asset('img/checkout/not-found.png') }}" alt="" class="h-32 object-cover">
                    <div>
                        <p class="font-bold text-xl mb-2">Oops, we think it hides somewhere</p>
                        <p class="mb-4">Try another keyword or check product recommendation below.</p>
                        <a href="{{ route('home.index') }}" class="inline-flex text-white bg-primary font-bold py-2 px-8 rounded-md border border-primary hover:text-primary hover:bg-white text-sm">Go Back Home</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @include('product.section')
@endsection

@push('scripts')
    @include('product.script')
    <script>
        function changeTab(selectedTab) {
            document.querySelectorAll(".tab").forEach((element) => {
                if (element != selectedTab) {
                    element.className = "text-gray flex gap-2 font-bold items-center border-b-2 border-gray-light border-transparent pb-2 hover:text-primary tab";
                } else {
                    element.className = "text-primary flex gap-2 font-bold items-center border-b-2 border-gray-light border-primary pb-2 tab tab-active";
                }
            });

            var tabName = selectedTab.textContent.trim().toLowerCase();
            var tabSectionName = 'section-' + tabName;

            document.querySelectorAll(".tab-section").forEach((element) => {
                if (element.getAttribute('id') == tabSectionName) {
                    element.classList.add('tab-section-active');
                    element.classList.remove('hidden');
                } else {
                    element.classList.remove('tab-section-active');
                    element.classList.add('hidden');
                }
            });
        }
    </script>
@endpush
