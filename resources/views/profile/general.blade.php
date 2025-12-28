@extends('layouts.profile')

@section('title', 'General')

@section('general', 'text-primary')

@section('modals')
    <div class="fixed top-0 left-0 bg-black/50 z-50 hidden" id="modal">
        <div class="flex w-screen h-screen items-center justify-center">
            <div class="p-8 bg-white rounded-lg w-1/3">
                <div class="text-2xl mb-8 font-bold flex items-center justify-between">
                    <p id="modal-title"></p>
                    <button type="button" class="text-gray hover:text-primary" onclick="toggleModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('general.update') }}" method="POST" class="modal-form hidden" id="modal-form-username">
                    @method('PUT')
                    @csrf

                    <div class="mb-8">
                        <x-form.label for="username">Username</x-form.label>
                        <x-form.input type="text" name="username" id="username" value="{{ Auth::user()->username }}" required maxlength="255"/>
                        <x-form.text>Username could be seen by others</x-form.text>
                    </div>
                    <x-button type="submit" variant="primary" block>Save</x-button>
                </form>

                <form action="{{ route('general.update') }}" method="POST" class="modal-form hidden" id="modal-form-date-of-birth">
                    @method('PUT')
                    @csrf

                    <div class="mb-8">
                        <x-form.label for="dob">Date of birth</x-form.label>
                        <x-form.input type="date" name="dob" id="dob" value="{{ Auth::user()->dob }}" required/>
                        <x-form.text>1 January 1970 - 31 December 2009</x-form.text>
                    </div>
                    <x-button type="submit" variant="primary" block>Save</x-button>
                </form>

                <form action="{{ route('general.update') }}" method="POST" class="modal-form hidden" id="modal-form-gender">
                    @method('PUT')
                    @csrf

                    <input type="hidden" name="gender" id="gender">
                    <div class="mb-8 flex gap-4">
                        @if (Auth::user()->gender === 'Male')
                            <button type="button" class="border py-2 w-1/3 border-blue-500 bg-blue-500 text-white" name="gender" id="gender-male" onclick="changeGender(this)">Male</button>
                        @else
                            <button type="button" class="border py-2 w-1/3 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white" name="gender" id="gender-male" onclick="changeGender(this)">Male</button>
                        @endif

                        @if (Auth::user()->gender === 'Female')
                            <button type="button" class="border py-2 w-1/3 border-pink-500 bg-pink-500 text-white" name="gender" id="gender-female" onclick="changeGender(this)">Female</button>
                        @else
                            <button type="button" class="border py-2 w-1/3 border-pink-500 text-pink-500 hover:bg-pink-500 hover:text-white" name="gender" id="gender-female" onclick="changeGender(this)">Female</button>
                        @endif

                        @if (Auth::user()->gender === 'Other')
                            <button type="button" class="border py-2 w-1/3 border-primary bg-primary text-white" name="gender" id="gender-other" onclick="changeGender(this)">Other</button>
                        @else
                            <button type="button" class="border py-2 w-1/3 border-primary text-primary hover:bg-primary hover:text-white" name="gender" id="gender-other" onclick="changeGender(this)">Other</button>
                        @endif
                    </div>

                    <x-button type="submit" variant="primary" block>Save</x-button>
                </form>

                <form action="{{ route('general.update') }}" method="POST" class="modal-form hidden" id="modal-form-phone-number">
                    @method('PUT')
                    @csrf

                    <div class="mb-8">
                        <x-form.label for="phone">Phone</x-form.label>
                        <x-form.input type="text" name="phone" id="phone" minlength="12" maxlength="12" pattern="[0-9]{12}" value="{{ Auth::user()->phone }}" placeholder="ex. 081234567891" required/>
                    </div>
                    <x-button type="submit" variant="primary" block>Save</x-button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="flex gap-8">
        <form action="{{ route('general.update') }}" method="POST" enctype="multipart/form-data" class="w-1/3 rounded-lg border border-gray-light p-4" id="form-image">
            @method('PUT')
            @csrf

            <div class="text-center">
                <img id="user_image" src="{{ asset(Auth::user()->getImage()) }}" alt="" class="inline-flex w-48 h-48 rounded-full mb-4 object-cover">
            </div>
            <input type="file" name="image" id="image" class="hidden" accept=".jpg,.jpeg,.png" onchange="submitImage(this)">
            <x-button variant="gray" outline onclick="openInputImage('image')" block class="mb-4">Choose Image</x-button>
            <p class="text-sm text-black">File Size: Maximum 10.000.000 bytes (10 Megabytes). File extension allowed: .JPG, .JPEG, .PNG</p>
        </form>
        <div class="w-2/3">
            <p class="text-xl mb-4 text-gray font-bold">Change Biodata</p>
            <div class="flex gap-20 text-black">
                <div class="flex flex-col gap-4">
                    <p>Username</p>
                    <p>Date Of Birth</p>
                    <p>Gender</p>
                    <p>Phone Number</p>
                    <p>Email</p>
                </div>
                <div class="flex flex-col gap-4">
                    <p class="flex gap-4">
                        {{ Auth::user()->username }}
                        <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Edit Username</button>
                    </p>
                    <p class="flex gap-4">
                        @if (Auth::user()->dob == null)
                            <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Input Date Of Birth</button>
                        @else
                            {{ Auth::user()->dob }}
                            <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Edit Date Of Birth</button>
                        @endif
                    </p>
                    <p class="flex gap-4">
                        @if (Auth::user()->gender == null)
                            <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Input Gender</button>
                        @else
                            {{ Auth::user()->gender }}
                            <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Edit Gender</button>
                        @endif
                    </p>
                    <p class="flex gap-4">
                        @if (Auth::user()->phone == null)
                            <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Input Phone Number</button>
                        @else
                        {{ Auth::user()->phone }}
                        <button type="button" class="text-primary" onclick="toggleModal(this.innerHTML)">Edit Phone Number</button>
                        @endif
                    </p>
                    <p>
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function submitImage(input) {
            if (validateInputImage(input, 'user_image')) {
                document.querySelector('#form-image').submit();
            }
        }

        function changeGender(selectedButton)
        {
            var buttonHTML = selectedButton.innerHTML;

            document.querySelector('#gender').value = buttonHTML;

            document.querySelector('#gender-male').className = 'border py-2 w-1/3 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white';
            document.querySelector('#gender-female').className = 'border py-2 w-1/3 border-pink-500 text-pink-500 hover:bg-pink-500 hover:text-white';
            document.querySelector('#gender-other').className = 'border py-2 w-1/3 border-primary text-primary hover:bg-primary hover:text-white';

            switch (buttonHTML) {
                case 'Male':
                    selectedButton.className = 'border py-2 w-1/3 border-blue-500 bg-blue-500 text-white';
                    break;
                case 'Female':
                    selectedButton.className = 'border py-2 w-1/3 border-pink-500 bg-pink-500 text-white';
                    break;
                case 'Other':
                    selectedButton.className = 'border py-2 w-1/3 border-primary bg-primary text-white';
                    break;
            }
        }

        function toggleModal(modalTitle)
        {
            $('#modal').fadeToggle();

            if (modalTitle === undefined) return;
            document.querySelector('#modal-title').innerHTML = modalTitle;
            var modalFormId = 'modal-form-' + modalTitle.replace('Edit', '').replace('Input', '').trim().replace(/ /g, '-').toLowerCase();

            document.querySelectorAll('.modal-form').forEach((element) => {
                if (element.getAttribute('id') == modalFormId) {
                    element.classList.remove('hidden');
                    element.classList.add('block');
                } else {
                    element.classList.remove('block');
                    element.classList.add('hidden');
                }
            });
        }
    </script>
@endpush
