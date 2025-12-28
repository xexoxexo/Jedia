<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $recommendations = Product::all()->random(5);

        return view('product.show', [
            'recommendations' => $recommendations,
            'product' => $product,
        ]);
    }
}
