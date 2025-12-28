<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('money', function ($money) {
            return "<?php echo 'Rp' . number_format($money, 0, ',', '.'); ?>";
        });

        Blade::directive('str_limit', function ($str, $count = 20) {
            return "<?php echo Str::of({$str})->limit({$count}); ?>";
        });
    }
}
