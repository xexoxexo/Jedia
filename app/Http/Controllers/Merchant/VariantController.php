<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function store($product_id, Request $request)
    {
        $validated = $request->validate([
            'add_product_variant_name' => ['required', 'min:3', 'max:255'],
            'add_product_variant_price' => ['required', 'min:0'],
            'add_product_variant_stock' => ['required', 'min:0'],
        ]);

        $product = Product::findOrFail($product_id);

        $product->variants()->create([
            'name' => $request->add_product_variant_name,
            'stock' => $request->add_product_variant_stock,
            'price' => $request->add_product_variant_price,
        ]);

        return redirect()->back();
    }

    public function update($product_id, $variant_id, Request $request)
    {
        $validated = $request->validate([
            'edit_product_variant_name' => ['required', 'min:3', 'max:255'],
            'edit_product_variant_price' => ['required', 'min:0'],
            'edit_product_variant_stock' => ['required', 'min:0'],
        ]);

        $variant = ProductVariant::findOrFail($variant_id);

        $variant->name = $request->edit_product_variant_name;
        $variant->stock = $request->edit_product_variant_stock;
        $variant->price = $request->edit_product_variant_price;
        $variant->save();

        return redirect()->back();
    }

    public function destroy($product_id, $variant_id, Request $request)
    {
        $product = Product::findOrFail($product_id);

        if ($product->variants()->count() > 2) {
            $variant = ProductVariant::findOrFail($variant_id);
            $variant->delete();
        }

        return redirect()->back();
    }
}
