@extends('layouts.merchant')

@section('title', 'Merchant Profile')

@section('profile', 'text-primary')

@section('content')
    <form action="{{ route('profile.general.update') }}" method="POST" enctype="multipart/form-data">
        @method('PUT')

        <p class="text-xl font-bold mb-4">Edit Profile</p>
        <div class="flex gap-8 mb-8 items-center">
            <div class="relative w-32 h-32">
                <img src="{{ asset(Auth::user()->merchant->getImage()) }}" alt="" class="w-full h-full rounded-full object-cover" id="img_merchant_image">
                <input type="file" name="image" id="merchant_image" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateInputImage(this, 'img_merchant_image')">
                <input type="text" name="username" value="{{ auth()->user()->username }}"/>
                <input type="date" name="dob"value="{{ auth()->user()->tanggal_lahir }}"/>
                <select name="gender">
                <option value="L" {{ auth()->user()->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ auth()->user()->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <input type="text" name="phone"value="{{ auth()->user()->telepon }}"/>
                <button type="button" class="bg-gray-light text-black p-2 rounded-full absolute bottom-0 right-0" onclick="openInputImage('merchant_image')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5
                    </svg>
                </button>
            </div>
            <div class="flex-grow">
                <div class="mb-4 w-1/3">
                    <x-form.label for="merchant_name">Merchant Name</x-form.label>
                    <x-form.input type="text" name="name" id="merchant_name" placeholder="ABC Store" value="{{ Auth::user()->merchant->name }}" class="font-bold" maxlength="255"/>
                </div>
                <div class="flex gap-4">
                    <div class="w-1/3">
                        <x-form.label for="process_time">Process Time</x-form.label>
                        <x-form.input type="text" name="process_time" id="process_time" placeholder="Process Time" value="{{ Auth::user()->merchant->process_time }}" maxlength="255"/>
                    </div>
                    <div class="w-1/3">
                        <x-form.label for="operational_time">Operational Time</x-form.label>
                        <x-form.input type="text" name="operational_time" id="operational_time"  placeholder="Operational Time" value="{{ Auth::user()->merchant->operational_time }}" maxlength="255"/>
                    </div>
                    <div class="w-1/3">
                        <x-form.label for="status">Status</x-form.label>
                        <x-form.select value="{{ Auth::user()->merchant->status }}">
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </x-form.select>
                    </div>
                </div>
            </div>
        </div>
        <p class="mb-4">Banner Image</p>
        <button class="w-full h-96 hover:opacity-50" onclick="openInputImage('merchant_banner_image')" type="button">
            <img src="{{ asset(Auth::user()->merchant->getBannerImage()) }}" alt="" class="w-full h-full object-cover rounded-lg mb-8" id="img_merchant_banner_image">
            <input type="file" name="banner_image" id="merchant_banner_image" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateInputImage(this, 'img_merchant_banner_image')">
        </button>
        <div class="flex gap-4 mb-4">
            <div class="w-3/4">
                <x-form.label for="description">Description</x-form.label>
                <x-form.input type="text" name="description" id="description" placeholder="Description" value="{{ Auth::user()->merchant->description }}" maxlength="255"/>
            </div>
            <div class="w-1/4">
                <x-form.label for="catch_phrase">Catchphrase</x-form.label>
                <x-form.input type="text" name="catch_phrase" id="catch_phrase" placeholder="ex. Thrive for the better" value="{{ Auth::user()->merchant->catch_phrase }}" maxlength="255"/>
            </div>
        </div>
        <div class="mb-8">
            <x-form.label for="about_store">About Store</x-form.label>
            <x-form.textarea name="full_description" id="about_store" placeholder="Tell your customer all about your store here" maxlength="255">{{ Auth::user()->merchant->full_description }}</x-form.textarea>
        </div>
        <div class="flex gap-4 justify-end">
            <x-button variant="red" class="flex items-center gap-2" type="reset" onclick="resetImages()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Discard Changes
            </x-button>
            <x-button variant="primary" class="flex items-center gap-2" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
                Edit Profile
            </x-button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function resetImages() {
            $('#img_merchant_image').attr('src', '{{ asset(Auth::user()->merchant->getImage()) }}');
            $('#img_merchant_banner_image').attr('src', '{{ asset(Auth::user()->merchant->getBannerImage()) }}');
        }
    </script>
@endpush
