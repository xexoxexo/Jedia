<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Merchant;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// welcome generated
// Route::get('/', function () {
//     return view('welcome');
// });

// all role routes

Route::controller(HomeController::class)->name('home.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
});

Route::get('/categories/{id}', [HomeController::class, 'show'])->name('categories.show');
Route::get('/promos/{id}', [PromoController::class, 'index'])->name('promos.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/merchants/{id}', [Merchant\HomeController::class, 'show'])->name('merchant.show');

// guest only routes
Route::middleware('guest')->group(function () {
    // login controllers
    Route::prefix('login')->controller(Auth\LoginController::class)->name('login.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'authenticate')->name('authenticate');
    });

    // register controllers
    Route::prefix('register')->controller(Auth\RegisterController::class)->name('register.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'authenticate')->name('authenticate');
    });

    // login register google controllers
    Route::prefix('auth')->controller(GoogleController::class)->group(function () {
        Route::get('redirect', 'redirect');
        Route::get('callback', 'callback');
    });
});

// authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [HomeController::class, 'logout'])->name('logout');

    // prefix chats controllers
    Route::prefix('chats')->controller(ChatController::class)->name('chats.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('redirect/{merchant_id}', 'redirect')->name('redirect');
        Route::get('{room_id}', 'show')->name('show');
        Route::post('{room_id}', 'store')->name('store');
    });

    // cart controllers
    Route::controller(CartController::class)->group(function () {
        Route::get('cart', 'index')->name('cart.index');
        Route::post('cart', 'store')->name('cart.store');
        Route::get('checkout', 'checkout_index')->name('checkout.index');
        Route::post('checkout', 'checkout_store')->name('checkout.store');
        Route::post('bill', 'bill_store')->name('bill.store');
    });

    // profile controllers
    Route::controller(ProfileController::class)->group(function () {
        // prefix general
        Route::prefix('general')->name('general.')->group(function () {
            Route::get('', 'general_index')->name('index');
            Route::put('', 'general_update')->name('update');
        });

        // prefix locations
        Route::prefix('locations')->name('locations.')->group(function () {
            Route::get('', 'location_index')->name('index');
            Route::post('', 'location_store')->name('store');
            Route::delete('{id}', 'location_destroy')->name('destroy');
        });

        Route::get('history-transaction', 'history_index')->name('history-transaction.index');

        Route::prefix('following-list')->name('following-list.')->group(function () {
            Route::get('', 'following_index')->name('index');
            Route::post('', 'following_store')->name('store');
            Route::delete('', 'following_destroy')->name('destroy');
        });
    });

    // update transaction / order status by merchant / user
    Route::put('th/{th_id}/pr/{pr_id}/va/{va_id}', [OrderController::class, 'update'])->name('order.update');
    Route::get('th/{th_id}/pr/{pr_id}/va/{va_id}/reviews', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('th/{th_id}/pr/{pr_id}/va/{va_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('reviews/{review_id}', [ReviewController::class, 'show'])->name('reviews.show');

    // merchant controllers
    Route::prefix('merchant')->name('merchant.')->group(function () {
        // only user able to register be merchant
        Route::prefix('register')->controller(Merchant\RegisterController::class)->middleware('not.user')->name('register.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('', 'store')->name('store');
        });

        // only merchant pages
        Route::middleware('not.merchant')->group(function () {
            Route::get('home', [Merchant\HomeController::class, 'index'])->name('index');

            // prefix chats controllers
            Route::prefix('chats')->controller(Merchant\ChatController::class)->name('chats.')->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('{room_id}', 'show')->name('show');
                Route::post('{room_id}', 'store')->name('store');
            });

            // profile controllers
            Route::prefix('profile')->controller(Merchant\ProfileController::class)->name('profile.')->group(function () {
                Route::get('', 'index')->name('index');
                Route::put('', 'update')->name('update');
            });

            Route::get('transactions', [Merchant\TransactionController::class, 'index'])->name('transactions.index');

            // prefix products
            Route::prefix('products')->group(function () {
                // product controllers
                Route::controller(Merchant\ProductController::class)->name('products.')->group(function () {
                    Route::get('', 'index')->name('index');
                    Route::get('create', 'create')->name('create');
                    Route::post('store', 'store')->name('store');
                    Route::put('{id}', 'update')->name('update');
                    Route::delete('{id}', 'destroy')->name('destroy');
                });

                // variant controllers
                Route::controller(Merchant\VariantController::class)->name('variants.')->group(function () {
                    Route::post('{product_id}/variants', 'store')->name('store');
                    Route::put('{product_id}/variants/{variant_id}', 'update')->name('update');
                    Route::delete('{product_id}/variants/{variant_id}', 'destroy')->name('destroy');
                });
            });
        });
    });
});
