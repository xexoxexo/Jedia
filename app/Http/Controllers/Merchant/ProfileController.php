<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $recommendations = Product::all()->random(5);

        return view('merchant.profile', [
            'recommendations' => $recommendations,
        ]);
    }

    public function update(Request $request)
    {
        $merchant = Merchant::findOrFail(Auth::user()->merchant->id);

        $request->validate([
            'name' => ['required', 'max:255'], // 1 GB = 1024 MB, 1 MB = 1024 KB
            'process_time' => ['max:255'],
            'operational_time' => ['max:255'],
            'description' => ['max:255'],
            'catch_phrase' => ['max:255'],
            'full_description' => ['max:255'],
        ]);

        $merchant->name = $request->name;
        $merchant->process_time = $request->process_time;
        $merchant->operational_time = $request->operational_time;
        $merchant->description = $request->description;
        $merchant->catch_phrase = $request->catch_phrase;
        $merchant->full_description = $request->full_description;

        if ($request->image != null) {
            $request->validate([
                'image' => ['required', 'file', 'max:' . (10 * 1024 * 1024), 'mimes:jpg,jpeg,png'],
            ]);

            if ($merchant->image != null) {
                $path = str_replace('storage/', '', $merchant->image);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $image = Storage::disk('public')->put('merchant/images', $request->file('image'));
            $merchant->image = 'storage/' . $image;
        }

        if ($request->banner_image != null) {
            $request->validate([
                'banner_image' => ['required', 'file', 'max:' . (10 * 1024 * 1024), 'mimes:jpg,jpeg,png'], // 1 GB = 1024 MB, 1 MB = 1024 KB
            ]);

            if ($merchant->banner_image != null) {
                $path = str_replace('storage/', '', $merchant->banner_image);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $banner_image = Storage::disk('public')->input('merchant/banner-images', $request->file('banner_image'));
            $merchant->banner_image = 'storage/' . $banner_image;
        }

        $merchant->save();

        return redirect()->back();
    }
}
