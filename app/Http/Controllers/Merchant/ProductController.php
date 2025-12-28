<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $recommendations = Product::all()->random(5);
        $categories = ProductCategory::all();

        return view('merchant.products.index', [
            'recommendations' => $recommendations,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $recommendations = Product::all()->random(5);
        $categories = ProductCategory::all();

        if ($request->ajax()) {
            $variant = $request->variant;
            $view = view('merchant.products.load', ['variant' => $variant])->render();
            return Response::json(['view' => $view]);
        }

        return view('merchant.products.create', [
            'recommendations' => $recommendations,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'max:255'],
            'category_id' => ['required'],
            'condition' => ['required', 'max:255'],
            'product_description' => ['required', 'max:255'],
            'variant_names' => ['required', 'array', 'min:2'],
            'variant_names.*' => ['required', 'min:3', 'max:255'],
            'variant_prices' => ['required', 'array', 'min:2'],
            'variant_prices.*' => ['required', 'min:0'],
            'variant_stocks' => ['required', 'array', 'min:2'],
            'variant_stocks.*' => ['required', 'min:0'],
            'product_images' => ['required', 'array'],
            'product_images.*' => ['required', 'file', 'max:' . (10 * 1024 * 1024), 'mimes:jpg,jpeg,png'],
        ]);

        $merchant = Merchant::findOrFail(Auth::user()->merchant->id);

        $product = $merchant->products()->create([
            'name' => $request->product_name,
            'description' => $request->product_description,
            'condition' => $request->condition,
            'product_category_id' => $request->category_id,
        ]);

        foreach ($request->product_images as $image) {
            $product_image = Storage::disk('public')->put('product-images', $image);
            $product->images()->create([
                'image' => 'storage/' . $product_image,
            ]);
        }

        $vl = count($request->variant_names);
        for ($i = 0; $i < $vl; $i++) {
            $product->variants()->create([
                'name' => $request->variant_names[$i],
                'stock' => $request->variant_stocks[$i],
                'price' => $request->variant_prices[$i],
            ]);
        }

        return redirect()->route('merchant.products.index');
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'edit_product_name' => ['required', 'max:255'],
            'edit_product_category_id' => ['required'],
            'edit_product_description' => ['required', 'max:255'],
            'edit_product_images' => ['required', 'array'],
            'edit_product_images.*' => ['required', 'file', 'max:' . (10 * 1024 * 1024), 'mimes:jpg,jpeg,png'],
        ]);

        $product = Product::findOrFail($id);

        foreach ($product->images as $pi) {
            $path = str_replace('storage/', '', $pi->image);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        $product->images()->delete();

        foreach ($request->edit_product_images as $image) {
            $product_image = Storage::disk('public')->put('product-images', $image);
            $product->images()->create([
                'image' => 'storage/' . $product_image,
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id, Request $request)
    {
        $product = Product::findOrFail($id);

        Cart::where('product_id', $product->id)->delete();
        $product->delete();

        return redirect()->back();
    }
}
