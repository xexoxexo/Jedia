@extends('layouts.merchant')

@section('title', 'Merchant Product Index')

@section('products', 'text-primary')
@section('products.index', 'text-primary font-bold')

@section('modals')
    <div class="fixed top-0 left-0 bg-black/50 z-50 hidden" id="modal">
        <div class="flex w-screen h-screen items-center justify-center">
            <div class="p-8 bg-white rounded-lg w-1/2">
                <div class="text-2xl mb-8 font-bold flex items-center justify-between">
                    <p id="modal-title"></p>
                    <button type="button" class="text-gray hover:text-primary" onclick="toggleModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>

                <form action="" method="POST" class="modal-form hidden" id="modal-form-add-product-variant">
                    @csrf

                    <div class="mb-8">
                        <x-form.label for="add_product_variant_name">Product Variant Name</x-form.label>
                        <x-form.input type="text" name="add_product_variant_name" id="add_product_variant_name" placeholder="Example: Nike Man Shoes Variant 1 (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255"/>
                        <x-form.text>Product variant name min. 3 character</x-form.text>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="add_product_variant_price">Product Variant Price</x-form.label>
                        <x-form.input type="number" name="add_product_variant_price" id="add_product_variant_price" placeholder="Example: 50000" required min="1"/>
                        <x-form.text>Product variant price must be more than 0</x-form.text>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="add_product_variant_stock">Product Variant Stock</x-form.label>
                        <x-form.input type="number" name="add_product_variant_stock" id="add_product_variant_stock" placeholder="Example: 50" required min="1"/>
                        <x-form.text>Product variant stock must be more than 0</x-form.text>
                    </div>
                    <x-button type="reset" variant="gray" outline class="hidden form_button_reset">Reset</x-button>
                    <x-button type="submit" variant="primary" block>Save</x-button>
                </form>

                <form action="" method="POST" class="modal-form hidden" id="modal-form-edit-product-variant">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <x-form.label for="edit_product_variant_name">Product Variant Name</x-form.label>
                        <x-form.input type="text" name="edit_product_variant_name" id="edit_product_variant_name" placeholder="Example: Nike Man Shoes Variant 1 (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255"/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="edit_product_variant_price">Product Variant Price</x-form.label>
                        <x-form.input type="number" name="edit_product_variant_price" id="edit_product_variant_price" placeholder="Example: 50000" required min="1"/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="edit_product_variant_stock">Product Variant Stock</x-form.label>
                        <x-form.input type="number" name="edit_product_variant_stock" id="edit_product_variant_stock" placeholder="Example: 50" required min="1"/>
                    </div>
                    <x-button type="submit" variant="primary" block>Update</x-button>
                </form>

                <form action="" method="POST" class="modal-form hidden" id="modal-form-delete-product-variant">
                    @csrf
                    @method('DELETE')

                    <div class="mb-8">
                        <x-form.label for="delete_product_variant_name">Product Variant Name</x-form.label>
                        <x-form.input type="text" name="delete_product_variant_name" id="delete_product_variant_name" placeholder="Example: Nike Man Shoes Variant 1 (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255" disabled/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="delete_product_variant_price">Product Variant Price</x-form.label>
                        <x-form.input type="number" name="delete_product_variant_price" id="delete_product_variant_price" placeholder="Example: 50000" required min="1" disabled/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="delete_product_variant_stock">Product Variant Stock</x-form.label>
                        <x-form.input type="number" name="delete_product_variant_stock" id="delete_product_variant_stock" placeholder="Example: 50" required min="1" disabled/>
                    </div>
                    <x-button type="submit" variant="red" block>Delete</x-button>
                    <x-form.text>Delete won't work if product variants less or equals to 2</x-form.text>
                </form>

                <form action="" method="POST" class="modal-form hidden" id="modal-form-edit-product" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex gap-8 mb-8">
                        @for ($image = 1; $image <= 5; $image++)
                            <div class="flex items-center justify-center w-1/5">
                                <label for="edit_product_image{{ $image }}" class="flex flex-col items-center justify-center w-full h-20 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                    <div class="flex flex-col items-center justify-center h-full w-full" id="div_edit_product_image{{ $image }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        <p class="text-sm">Image {{ $image }}</p>
                                    </div>
                                    <input id="edit_product_image{{ $image }}" name="edit_product_images[]" type="file" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateBoxImage(this, 'div_edit_product_image{{ $image }}')"/>
                                </label>
                            </div>
                        @endfor
                    </div>
                    <div class="mb-8">
                        <x-form.label for="edit_product_name">Product Name</x-form.label>
                        <x-form.input type="text" name="edit_product_name" id="edit_product_name" placeholder="Example: Nike Man Shoes (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255"/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="edit_product_category_id">Product Category</x-form.label>
                        <x-form.select id="edit_product_category_id" name="edit_product_category_id" required>
                            <option value="">--- Product Category ---</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="edit_product_description">Product Description</x-form.label>
                        <x-form.textarea rows="3" name="edit_product_description" id="edit_product_description" required maxlength="255"></x-form.textarea>
                    </div>
                    <x-button type="reset" variant="gray" outline class="hidden form_button_reset">Reset</x-button>
                    <x-button type="submit" variant="primary" block>Update</x-button>
                </form>

                <form action="" method="POST" class="modal-form hidden" id="modal-form-delete-product" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')

                    <div class="flex gap-8 mb-8">
                        @for ($image = 1; $image <= 5; $image++)
                            <div class="flex items-center justify-center w-1/5">
                                <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                    <div class="flex flex-col items-center justify-center h-full w-full" id="div_delete_product_image{{ $image }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        <p class="text-sm">Image {{ $image }}</p>
                                    </div>
                                    <input id="delete_product_image{{ $image }}" name="delete_product_images[]" type="file" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateBoxImage(this, 'div_delete_product_image{{ $image }}')" disabled/>
                                </label>
                            </div>
                        @endfor
                    </div>
                    <div class="mb-8">
                        <x-form.label for="delete_product_name">Product Name</x-form.label>
                        <x-form.input type="text" name="delete_product_name" id="delete_product_name" placeholder="Example: Nike Man Shoes (Product Type/Category/Brand/Other)" required minlength="3" maxlength="255" disabled/>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="delete_product_category_id">Product Category</x-form.label>
                        <x-form.select id="delete_product_category_id" name="delete_product_category_id" required disabled>
                            <option value="">--- Product Category ---</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <div class="mb-8">
                        <x-form.label for="delete_product_description">Product Description</x-form.label>
                        <x-form.textarea rows="3" name="delete_product_description" id="delete_product_description" required maxlength="255" disabled></x-form.textarea>
                    </div>
                    <x-button type="submit" variant="red" block>Delete</x-button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section>
        <div class="flex justify-between mb-4">
            <p class="font-bold text-xl">Product List</p>
            <x-button href="{{ route('merchant.products.create') }}" variant="primary">+ Add Product</x-button>
        </div>

        <div class="border border-gray-light rounded-lg">
            <button
                class="inline-flex text-primary px-4 py-2 border-b-2 border-primary font-bold"
            >
                All Products
            </button>
            <div class="p-4 border-t border-gray-light">
                <x-form.input type="text" placeholder="Search Product"/>
            </div>
            <div>
                <table class="table-auto w-full text-left text-sm">
                    <thead class="border-y border-gray-light">
                        <tr>
                            <th class="p-4 font-normal">Product Information</th>
                            <th class="p-4 font-normal">Name</th>
                            <th class="p-4 font-normal">Price</th>
                            <th class="p-4 font-normal">Stock</th>
                            <th class="p-4 font-normal">Category</th>
                            <th class="p-4 font-normal"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Auth::user()->merchant->products as $product)
                            <tr>
                                <td class="p-4 font-normal">
                                    <img src="{{ asset($product->images[0]->image) }}" alt="" class="w-20 h-20 object-cover">
                                </td>
                                <td class="p-4 font-normal">{{ $product->name }}</td>
                                <td class="p-4 font-normal">@money($product->variants[0]->price)</td>
                                <td class="p-4 font-normal">{{ $product->variants[0]->stock }}</td>
                                <td class="p-4 font-normal">{{ $product->category->name }}</td>
                                <td class="p-4 font-normal">
                                    @php
                                        $image1 = isset($product->images[0]) ? asset($product->images[0]->image) : '';
                                        $image2 = isset($product->images[1]) ? asset($product->images[1]->image) : '';
                                        $image3 = isset($product->images[2]) ? asset($product->images[2]->image) : '';
                                        $image4 = isset($product->images[3]) ? asset($product->images[3]->image) : '';
                                        $image5 = isset($product->images[4]) ? asset($product->images[4]->image) : '';
                                    @endphp
                                    <x-form.select onchange="toggleManageProduct(this, '{{ $product->id }}', '{{ $image1 }}', '{{ $image2 }}', '{{ $image3 }}', '{{ $image4 }}', '{{ $image5 }}', '{{ $product->name }}', '{{ $product->product_category_id }}', '{{ $product->description }}')">
                                        <option value="">--- Manage Product ---</option>
                                        <option value="Add Product Variant">Add Product Variant</option>
                                        <option value="Edit Product">Edit</option>
                                        <option value="Delete Product">Delete</option>
                                    </x-form.select>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4 font-bold bg-gray text-white" colspan="6">
                                    <div class="flex justify-between">
                                        <p>Look Product Variant</p>
                                        <button onclick="toggleRow(this, 'row-product-{{ $product->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($product->variants as $variant)
                                <tr class="bg-gray-light hidden row-product-{{ $product->id }}">
                                    <td class="p-4 font-normal" colspan="2">{{ $variant->name }}</td>
                                    <td class="p-4 font-normal">@money($variant->price)</td>
                                    <td class="p-4 font-normal" colspan="2">{{ $variant->stock }}</td>
                                    <td class="p-4 font-normal">
                                        <x-form.select onchange="toggleManageVariant(this, '{{ $product->id }}', '{{ $variant->id }}', '{{ $variant->name }}', '{{ $variant->price }}', '{{ $variant->stock }}')">
                                            <option value="">--- Manage Variant ---</option>
                                            <option value="Edit Product Variant">Edit</option>
                                            <option value="Delete Product Variant">Delete</option>
                                        </x-form.select>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function toggleManageProduct(select, product_id, image1, image2, image3, image4, image5, product_name, product_category_id, product_description)
        {
            if (select.value == '') {
                select.selectedIndex = 0;
                return;
            }
            var title = select.value;
            select.selectedIndex = 0;

            var form = toggleModal(title);

            title = title.trim().replace(/ /g, '-').toLowerCase();

            document.querySelectorAll('.form_button_reset').forEach((element) => {
                element.click();
            });

            if (title.includes('add')) {
                let actionURL = '{{ route("merchant.variants.store", ["product_id" => "param1"]) }}'.replace('param1', product_id);
                form.setAttribute('action', actionURL);
            } else if (title.includes('edit')) {
                $('#edit_product_name').val(product_name);
                $('#edit_product_category_id').val(product_category_id);
                $('#edit_product_description').val(product_description);

                changeDivImage(image1, 'div_edit_product_image1');
                changeDivImage(image2, 'div_edit_product_image2');
                changeDivImage(image3, 'div_edit_product_image3');
                changeDivImage(image4, 'div_edit_product_image4');
                changeDivImage(image5, 'div_edit_product_image5');

                let actionURL = '{{ route("merchant.products.update", ["id" => "param1"]) }}'.replace('param1', product_id);
                form.setAttribute('action', actionURL);
            } else if (title.includes('delete')) {
                $('#delete_product_name').val(product_name);
                $('#delete_product_category_id').val(product_category_id);
                $('#delete_product_description').val(product_description);

                changeDivImage(image1, 'div_delete_product_image1');
                changeDivImage(image2, 'div_delete_product_image2');
                changeDivImage(image3, 'div_delete_product_image3');
                changeDivImage(image4, 'div_delete_product_image4');
                changeDivImage(image5, 'div_delete_product_image5');

                let actionURL = '{{ route("merchant.products.destroy", ["id" => "param1"]) }}'.replace('param1', product_id);
                form.setAttribute('action', actionURL);
            }
        }

        function changeDivImage(imgSRC, divId)
        {
            if (imgSRC == '') return;
            let img = document.createElement("img");
            img.className = "w-full h-full object-cover";
            img.src = imgSRC;
            $('#' + divId).html(img);
        }

        function toggleManageVariant(select, product_id, variant_id, variant_name, variant_price, variant_stock)
        {
            if (select.value == '') {
                select.selectedIndex = 0;
                return;
            }
            var title = select.value;
            select.selectedIndex = 0;

            var form = toggleModal(title);

            title = title.trim().replace(/ /g, '-').toLowerCase();

            if (title.includes('edit')) {
                $('#edit_product_variant_name').val(variant_name);
                $('#edit_product_variant_price').val(variant_price);
                $('#edit_product_variant_stock').val(variant_stock);

                let actionURL = '{{ route("merchant.variants.update", ["product_id" => "param1", "variant_id" => "param2"]) }}'.replace('param1', product_id).replace('param2', variant_id);
                form.setAttribute('action', actionURL);
            } else if (title.includes('delete')) {
                $('#delete_product_variant_name').val(variant_name);
                $('#delete_product_variant_price').val(variant_price);
                $('#delete_product_variant_stock').val(variant_stock);

                let actionURL = '{{ route("merchant.variants.destroy", ["product_id" => "param1", "variant_id" => "param2"]) }}'.replace('param1', product_id).replace('param2', variant_id);
                form.setAttribute('action', actionURL);
            }
        }

        function toggleModal(modalTitle)
        {
            $('#modal').fadeToggle();

            if (modalTitle === undefined) return;
            document.querySelector('#modal-title').innerHTML = modalTitle;
            var modalFormId = 'modal-form-' + modalTitle.trim().replace(/ /g, '-').toLowerCase();

            var form = null;
            document.querySelectorAll('.modal-form').forEach((element) => {
                if (element.getAttribute('id') == modalFormId) {
                    element.classList.remove('hidden');
                    element.classList.add('block');

                   form = element;
                } else {
                    element.classList.remove('block');
                    element.classList.add('hidden');
                }
            });

            return form;
        }

        function toggleRow(btn, rowId)
        {
            if (btn.classList.contains('row-expanded')) {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>';
            } else {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" /></svg>';
            }
            btn.classList.toggle('row-expanded');

            var rows = document.querySelectorAll('.' + rowId);

            rows.forEach(function (element) {
                $(element).toggle();
            })
        }
    </script>
@endpush
