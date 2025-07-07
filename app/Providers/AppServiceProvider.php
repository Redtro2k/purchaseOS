<?php

namespace App\Providers;


use App\Http\Reponses\LogoutResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
       $this->app->singleton(LoginResponse::class, \App\Http\Responses\LoginResponse::class);
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        });
    }
}
