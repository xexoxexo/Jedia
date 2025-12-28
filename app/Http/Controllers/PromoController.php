<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index($id)
    {
        $recommendations = Product::all()->random(5);
        $promo = Promo::findOrFail($id);

        return view('promo.index', [
            'recommendations' => $recommendations,
            'promo' => $promo,
        ]);
    }
}
