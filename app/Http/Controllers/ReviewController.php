<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create($th_id, $pr_id, $va_id, Request $request)
    {
        $recommendations = Product::all()->random(5);
        $td = TransactionDetail::where('transaction_id', $th_id)->where('product_id', $pr_id)->where('variant_id', $va_id)->firstOrFail();

        return view('reviews.create', [
            'recommendations' => $recommendations,
            'td' => $td,
        ]);
    }

    public function store($th_id, $pr_id, $va_id, Request $request)
    {
        $td = TransactionDetail::where('transaction_id', $th_id)->where('product_id', $pr_id)->where('variant_id', $va_id)->firstOrFail();

        $validated = $request->validate([
            'review' => ['required', 'min:1', 'max:5'],
            'message' => ['required', 'max:255'],
            'review_images' => ['array'],
            'review_images.*' => ['file', 'max:' . (10 * 1024 * 1024), 'mimes:jpg,jpeg,png'],
            'review_videos' => ['array'],
            'review_videos.*' => ['file', 'max:' . (10 * 1024 * 1024), 'mimes:mp4,mov'],
        ]);

        $review = Review::create([
            'user_id' => Auth::user()->id,
            'transaction_id' => $th_id,
            'product_id' => $pr_id,
            'variant_bought' => $va_id,
            'review' => $request->review,
            'message' => $request->message,
        ]);

        if (!empty($request->review_images)) {
            foreach ($request->review_images as $image) {
                $review_image = Storage::disk('public')->put('review/images', $image);
                $review->images()->create([
                    'image' => 'storage/' . $review_image,
                ]);
            }
        }

        if (!empty($request->review_videos)) {
            foreach ($request->review_videos as $video) {
                $review_video = Storage::disk('public')->put('review/videos', $video);
                $review->videos()->create([
                    'video' => 'storage/' . $review_video,
                ]);
            }
        }

        return redirect()->route('history-transaction.index');
    }

    public function show($review_id)
    {
        $recommendations = Product::all()->random(5);
        $review = Review::findOrFail($review_id);

        return view('reviews.show', [
            'recommendations' => $recommendations,
            'review' => $review,
        ]);
    }
}
