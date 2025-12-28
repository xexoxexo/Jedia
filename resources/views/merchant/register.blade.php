@extends('layouts.dashboard')

@section('title', 'Merchant Register')

@section('content')
    <section class="flex gap-16 items-center">
        <div class="w-1/3 text-black">
            <div class="flex items-center gap-8 mb-8">
                <img src="{{ asset('img/merchants/free-benefit.png') }}" alt="" class="w-16 h-16 object-cover">
                <p class="text-sm">Open a merchant Account <span class="font-bold">Free</span> without any charges.</p>
            </div>
            <div class="flex items-center gap-8 mb-8">
                <img src="{{ asset('img/merchants/reach-benefit.png') }}" alt="" class="w-16 h-16 object-cover">
                <p class="text-sm">More than <span class="font-bold">90 millions</span> active users every month.</p>
            </div>
            <div class="flex items-center gap-8">
                <img src="{{ asset('img/merchants/user-benefit.png') }}" alt="" class="w-16 h-16 object-cover">
                <p class="text-sm"><span class="font-bold">Reach 97%</span> potential user across Indonesia.</p>
            </div>
        </div>
        <div class="w-2/3 border rounded-lg border-gray-light p-8">
            <p class="text-lg font-semibold text-black mb-8">Hello, {{ Auth::user()->username }} lets fill in your merchant detail</p>
            <div>
                <form action="" method="" id="form-phone-number" onsubmit="moveTab('form-phone-number', 'form-merchant-name', event)">
                    <div class="flex gap-4 mb-8">
                        <button class="border border-primary rounded-full text-primary h-8 w-8 flex items-center justify-center">1</button>
                        <div class="flex-grow">
                            <p class="text-xl font-semibold text-black mb-4">Enter Your Phone Number</p>
                            <x-form.label for="phone-number">Phone Number <span class="text-red font-bold">*</span></x-form.label>
                            <x-form.input type="text" name="phone-number" id="phone-number" placeholder="08XXXXXXXXXX" minlength="12" maxlength="12" pattern="[0-9]{12}" required/>
                            <x-form.text class="mb-4">Make sure your phone number is active to speed up the registration process</x-form.text>
                            <x-button variant="gray" type="submit">Next</x-button>
                        </div>
                    </div>
                </form>
                <form action="" method="" id="form-merchant-name" class="hidden" onsubmit="moveTab('form-merchant-name', 'form-location', event)">
                    <div class="flex gap-4 mb-8">
                        <button class="border border-gray rounded-full text-gray h-8 w-8 flex items-center justify-center">2</button>
                        <div class="flex-grow">
                            <p class="text-xl font-semibold text-black mb-4">Enter Your Merchant Name</p>
                            <x-form.label for="merchant-name">Merchant Name <span class="text-red font-bold">*</span></x-form.label>
                            <x-form.input type="text" name="merchant-name" id="merchant-name" placeholder="ABC Store" required/>
                            <x-form.text class="mb-4">Merchant name will be displayed on your products</x-form.text>
                            <div class="flex gap-4">
                                <x-button variant="gray" outline onclick="moveTab('form-merchant-name', 'form-phone-number')">Before</x-button>
                                <x-button variant="gray" type="submit">Next</x-button>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="{{ route('merchant.register.store') }}" method="POST" id="form-location" class="hidden" onsubmit="registerMerchant(event)">
                    @csrf

                    <div class="flex gap-4">
                        <button class="border border-gray rounded-full text-gray h-8 w-8 flex items-center justify-center">3</button>
                        <div class="flex-grow">
                            <p class="text-xl font-semibold text-black mb-4">Enter Your Location</p>
                            <p id="lbl-location" class="text-gray font-bold mb-4"></p>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="hidden" name="name" id="name">
                            <input type="hidden" name="phone" id="phone">
                            <div class="mb-4">
                                <x-form.label for="city">City <span class="text-red font-bold">*</span></x-form.label>
                                <x-form.input type="text" name="city" id="city" placeholder="ex. Jakarta" required/>
                            </div>
                            <div class="mb-4">
                                <x-form.label for="country">Country <span class="text-red font-bold">*</span></x-form.label>
                                <x-form.input type="text" name="country" id="country" placeholder="ex. Indonesia" required/>
                            </div>
                            <div class="mb-4">
                                <x-form.label for="address">Address <span class="text-red font-bold">*</span></x-form.label>
                                <x-form.input type="text" name="address" id="address" placeholder="ex. Mister Potato Street No. 1" required/>
                            </div>
                            <div class="mb-4">
                                <x-form.label for="postal_code">Postal Code <span class="text-red font-bold">*</span></x-form.label>
                                <x-form.input type="text" name="postal_code" id="postal_code" placeholder="ex. 14045" required/>
                            </div>
                            <div class="mb-4">
                                <x-form.label for="notes">Notes <span class="text-red font-bold">*</span></x-form.label>
                                <x-form.input type="text" name="notes" id="notes" placeholder="ex. White building, Yellow Roof" required/>
                            </div>
                            <div class="flex gap-4">
                                <x-button variant="gray" outline onclick="moveTab('form-location', 'form-merchant-name')">Before</x-button>
                                <x-button type="submit" variant="gray" class="flex-grow" type="submit">Save</x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function registerMerchant(e)
        {
            e.preventDefault();

            if (getLocation()) {
                if (e.target.checkValidity()) {
                    $('#name').val($('#merchant-name').val());
                    $('#phone').val($('#phone-number').val());
                }

                e.target.submit();
            }
        }

        function moveTab(currTab, nextTab, e = null)
        {
            if (e != null) {
                e.preventDefault();
                if (!e.target.checkValidity()) return;
            }

            if (getLocation()) {
                $('#' + currTab).toggle();
                $('#' + nextTab).toggle();
            }
        }

        function showPosition(position) {
            $('#latitude').val(position.coords.latitude)
            $('#longitude').val(position.coords.longitude);
        }
    </script>
@endpush
