<?php

namespace App\Providers;

use App\Views\Composers\ContactsComposer;
use App\Views\Composers\InquiriesComposer;
use App\Views\Composers\PaymentsComposer;
use App\Views\Composers\RolesComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['dashboard.core.includes.sidebar'], RolesComposer::class);
        view()->composer(['dashboard.core.includes.sidebar'], InquiriesComposer::class);
        view()->composer(['dashboard.core.includes.sidebar'], ContactsComposer::class);
        view()->composer(['dashboard.core.includes.sidebar'], PaymentsComposer::class);
    }
}
