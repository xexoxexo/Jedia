<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FlashSaleProduct;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(6);
        $promos = Promo::all();
        $categories = ProductCategory::has('products')->get()->random(4);
        $flash_sale_products = FlashSaleProduct::all()->random(5);

        if ($request->ajax()) {
            $view = view('product.load', compact('products'))->render();
            return Response::json(['view' => $view, 'nextPageUrl' => $products->nextPageUrl()]);
        }

        return view('home.index', [
            'recommendations' => Product::all()->random(5),
            'promos' => $promos,
            'categories' => $categories,
            'flash_sale_products' => $flash_sale_products,
            'products' => $products,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }

    public function search(Request $request)
    {
        $products = Product::paginate(6);
        $recommendations = Product::all()->random(5);

        if ($request->ajax()) {
            $view = view('product.load', compact('products'))->render();
            return Response::json(['view' => $view, 'nextPageUrl' => $products->nextPageUrl()]);
        }

        $keyword = $request->input('keyword');

        $sProducts = Product::where('name', 'LIKE', '%'. $keyword . '%')->get();
        $sMerchants = Merchant::where('name', 'LIKE', '%' . $keyword . '%')->get();

        return view('home.search', [
            'keyword' => $keyword,
            'recommendations' => Product::all()->random(5),
            'products' => $products,
            'sProducts' => $sProducts,
            'sMerchants' => $sMerchants,
        ]);
    }

    public function show($id)
    {
        $recommendations = Product::all()->random(5);
        $category = ProductCategory::find($id);

        return view('home.show', [
            'recommendations' => $recommendations,
            'category' => $category,
        ]);
    }
}
