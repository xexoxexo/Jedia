@extends('layouts.profile')

@section('title', 'Location')

@section('location', 'text-primary')

@section('modals')
    <div class="fixed top-0 left-0 bg-black/50 z-50 hidden" id="modal">
        <div class="flex w-screen h-screen items-center justify-center">
            <div class="p-8 bg-white rounded-lg w-1/2">
                <div class="text-xl mb-8 font-bold flex items-center justify-between">
                    <p></p>
                    <p class="text-black">Add Address</p>
                    <button type="button" class="text-gray hover:text-primary" onclick="toggleModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-evenly mb-4 text-black">
                    <div class="text-center inline-flex flex-col items-center">
                        <button class="border border-primary rounded-full text-primary h-8 w-8 flex items-center justify-center mb-1" id="button-pinpoint-location">1</button>
                        <p class="text-xs">Pinpoint location</p>
                    </div>
                    <div class="text-center inline-flex flex-col items-center">
                        <button class="border border-primary rounded-full text-white bg-gray-light h-8 w-8 flex items-center justify-center mb-1">2</button>
                        <p class="text-xs">Complete address detail</p>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="" id="tab-pinpoint-location">
                    <p class="mb-4 text-lg font-bold text-black">Please Confirm this is your address?</p>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.975452942829!2d106.60240637399052!3d-6.266958593721699!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fcf537584e4f%3A0xb5876d02e74ff281!2sCluster%20Alloggio%20Kost%20Gading%20Serpong!5e0!3m2!1sid!2sid!4v1703840150169!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-full h-64 rounded-lg mb-2"></iframe>
                    <div class="flex justify-evenly text-gray text-sm mb-4">
                        <p>Latitude: <span class="latitude"></span></p>
                        <p>Longitude: <span class="longitude"></span></p>
                    </div>
                    <x-button variant="primary" onclick="moveTab()" block>Confirm Location</x-button>
                </div>
                <form class="hidden" id="tab-complete-address-detail" action="{{ route('locations.store') }}" method="POST" onsubmit="addNewAddress(event)">
                    @csrf

                    <p class="mb-4 text-lg font-bold text-black">Please Confirm this is your address?</p>

                    <input type="hidden" name="latitude" class="latitude">
                    <input type="hidden" name="longitude" class="longitude">
                    <div class="mb-4">
                        <x-form.label for="address">Address</x-form.label>
                        <x-form.textarea name="address" id="address" placeholder="ex. New Kemanggisan Street No. 10 2F" rows="2" required></x-form.textarea>
                    </div>
                    <div class="flex gap-4 mb-4">
                        <div class="w-1/3">
                            <x-form.label for="city">City</x-form.label>
                            <x-form.input type="text" name="city" id="city" placeholder="ex. Jakarta" required/>
                        </div>
                        <div class="w-1/3">
                            <x-form.label for="country">Country</x-form.label>
                            <x-form.input type="text" name="country" id="country" placeholder="ex. Indonesia" required/>
                        </div>
                        <div class="w-1/3">
                            <x-form.label for="postal_code">Postal Code</x-form.label>
                            <x-form.input type="text" name="postal_code" id="postal_code" placeholder="ex. 12025" minlength="5" maxlength="5" pattern="[0-9]{5}" required/>
                        </div>
                    </div>

                    <div class="mb-8">
                        <x-form.label for="notes">Notes</x-form.label>
                        <x-form.input type="text" name="notes" id="notes" placeholder="ex. Black Gate, White Building" required/>
                    </div>
                    <x-button type="submit" variant="primary" block>Add New Address</x-button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section>
        <div class="flex justify-end mb-4">
            <x-button variant="primary" onclick="toggleModal()">+ Add New Address</x-button>
        </div>

        <div class="flex flex-col gap-4 text-black">
            @if (Auth::user()->locations->count() > 0)
                @foreach (Auth::user()->locations as $key => $location)
                    @if ($key === 0)
                        <div class="p-4 bg-primary-light border border-primary rounded-lg">
                    @else
                        <div class="p-4 bg-white border border-gray-light rounded-lg hover:border-primary">
                    @endif
                            <p class="font-bold">{{ $location->user->username }}</p>
                            <p>{{ $location->city }}, {{ $location->country }}</p>
                            <p>{{ $location->notes }}, {{ $location->postal_code }}</p>
                            <form action="{{ route('locations.destroy', ['id' => $location->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-sm text-primary font-bold">Delete</button>
                            </form>
                        </div>
                @endforeach
            @else
                <p class="text-lg font-bold">You got no location registered yet.</p>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function moveTab() {
            if (getLocation()) {
                document.querySelector('#button-pinpoint-location').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>';
                document.querySelector('#button-pinpoint-location').className = 'border border-primary bg-primary rounded-full text-white h-8 w-8 flex items-center justify-center mb-1';
                document.querySelector('#tab-pinpoint-location').classList.add('hidden');
                document.querySelector('#tab-complete-address-detail').classList.remove('hidden');
            }
        }

        function addNewAddress(e) {
            e.preventDefault();

            if (getLocation()) {
                e.target.submit();
            }
        }

        function toggleModal()
        {
            if (getLocation()) {
                var modal = document.querySelector('#modal');

                $(modal).fadeToggle();
            }
        }

        function showPosition(position) {
            document.querySelectorAll(".latitude").forEach((element) => {
                element.innerHTML = element.value = position.coords.latitude;
            });
            document.querySelectorAll(".longitude").forEach((element) => {
                element.innerHTML = element.value = position.coords.longitude;
            });
        }
    </script>
@endpush
