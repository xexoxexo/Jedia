@extends('layouts.merchant')

@section('title', 'Merchant Product Create')

@section('products', 'text-primary')
@section('products.create', 'text-primary font-bold')

@section('content')
    <form action="{{ route('merchant.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <p class="font-bold text-xl mb-8">Add Product</p>

        <div class="border border-gray-light rounded-lg p-8 mb-8">
            <p class="font-bold text-xl mb-4">Product Information</p>

            <div class="flex items-center gap-16 mb-4">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Product Name <span class="text-red font-bold">*</span></p>
                    <x-form.label for="product_name">Product name min. 3 character</x-form.label>
                </div>
                <div class="flex-grow">
                    <x-form.input type="text" name="product_name" id="product_name" placeholder="Example: Nike Man Shoes (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255"/>
                </div>
            </div>
            <div class="flex items-center gap-16">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Category <span class="text-red font-bold">*</span></p>
                    <x-form.label for="category_id">Choose category from the list</x-form.label>
                </div>
                <div class="flex-grow">
                    <x-form.select name="category_id" required>
                        <option value="">--- Product Category ---</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>
        </div>

        <div class="border border-gray-light rounded-lg p-8 mb-8">
            <p class="font-bold text-xl mb-4">Product Detail</p>

            <div class="flex items-center gap-16 mb-4">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Product Image <span class="text-red font-bold">*</span></p>
                    <x-form.label>File Size: Maximum 10.000.000 bytes (10 Megabytes). File extension allowed: .JPG, .JPEG, .PNG</x-form.label>
                </div>
                <div class="flex-grow flex gap-8">
                    @for ($image = 1; $image <= 5; $image++)
                        <div class="flex items-center justify-center w-1/5">
                            <label for="product_image{{ $image }}" class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                <div class="flex flex-col items-center justify-center h-full w-full" id="div_product_image{{ $image }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <p class="text-sm">Image {{ $image }}</p>
                                </div>
                                <input id="product_image{{ $image }}" name="product_images[]" type="file" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateBoxImage(this, 'div_product_image{{ $image }}')"/>
                            </label>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="flex items-center gap-16 mb-4">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold">Condition <span class="text-red font-bold">*</span></p>
                </div>
                <div class="flex-grow flex justify-center">
                    <div class="flex gap-8">
                        <div>
                            <input type="radio" value="New" name="condition" id="new" class="scale-150 me-1" required>
                            <label for="new">New</label>
                        </div>
                        <div>
                            <input type="radio" value="Used" name="condition" id="used" class="scale-150 me-1" required>
                            <label for="used">Used</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-16">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Product Description <span class="text-red font-bold">*</span></p>
                    <x-form.label for="product_description">Make sure the product description contains a detailed explanation regarding your product so that buyers can easily understand and find your product</x-form.label>
                </div>
                <div class="flex-grow">
                    <x-form.textarea rows="6" name="product_description" required maxlength="255"></x-form.textarea>
                </div>
            </div>
        </div>

        <div class="border border-gray-light rounded-lg p-8">
            <p class="font-bold text-xl mb-1">Product Variant</p>
            <p class="mb-4 text-sm">Add variant so that customer can choose the right product. Enter max. 5 types of variants</p>

            <div id="variants-container">
                @php
                    $variant = 1;
                @endphp
                @include('merchant.products.load')
                @php
                    $variant++;
                @endphp
                @include('merchant.products.load')
            </div>

            <div class="flex justify-between">
                <x-button variant="primary" outline onclick="loadMoreVariants()">+ Add Variant</x-button>
                <x-button variant="primary" type="submit">Save Product</x-button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        var variant = parseInt('{{ $variant }}');
        function loadMoreVariants() {
            variant++;
            $.ajax({
                url: '{{ route("merchant.products.create") }}?variant=' + variant,
                type: 'get',
                success: function (data) {
                    $('#variants-container').append(data.view);
                },
                error: function (xhr, status, error) {
                    console.log('Error loading more variants', error);
                }
            });
        }
    </script>
@endpush
