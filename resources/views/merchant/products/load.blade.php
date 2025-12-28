<hr class="text-gray-light mb-4">
<p class="font-bold mb-4">Product Variant {{ $variant }}</p>
<div class="flex items-center gap-16 mb-4">
    <div class="min-w-60 max-w-60">
        <label for="variant_name_{{ $variant }}" class="text-gray font-semibold mb-1">Product Variant Name <span class="text-red font-bold">*</span></label>
        <x-form.label for="variant_name_{{ $variant }}">Product variant name min. 3 character</x-form.label>
    </div>
    <div class="flex-grow">
        <x-form.input type="text" id="variant_name_{{ $variant }}" name="variant_names[]" placeholder="Example: Nike Man Shoes Variant {{ $variant }} (Product Type/Category/Brand/Other)" minlength="3" maxlength="255" required/>
    </div>
</div>
<div class="flex items-center gap-16 mb-4">
    <div class="min-w-60 max-w-60">
        <label for="variant_price_{{ $variant }}" class="text-gray font-semibold mb-1">Product Variant Price <span class="text-red font-bold">*</span></label>
        <x-form.label for="variant_price_{{ $variant }}">Product variant price must be more than 0</x-form.label>
    </div>
    <div class="flex-grow">
        <x-form.input type="number" id="variant_price_{{ $variant }}" name="variant_prices[]" placeholder="Example: 50000" required min="1"/>
    </div>
</div>
<div class="flex items-center gap-16 mb-4">
    <div class="min-w-60 max-w-60">
        <label for="variant_stock_{{ $variant }}" class="text-gray font-semibold mb-1">Product Variant Stock <span class="text-red font-bold">*</span></label>
        <x-form.label for="variant_stock_{{ $variant }}">Product variant stock must be more than 0</x-form.label>
    </div>
    <div class="flex-grow">
        <x-form.input type="number" id="variant_stock_{{ $variant }}" name="variant_stocks[]" placeholder="Example: 50" required min="1"    />
    </div>
</div>
