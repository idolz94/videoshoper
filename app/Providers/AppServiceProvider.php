<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD

=======
use Illuminate\Support\Facades\Schema;
>>>>>>> 29b43b86229b8c31f6cceb3709c8fc2790350a03
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
<<<<<<< HEAD
        //
=======
            Schema::defaultStringLength(191);
                    
>>>>>>> 29b43b86229b8c31f6cceb3709c8fc2790350a03
    }
}
