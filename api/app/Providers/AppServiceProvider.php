<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $frontend = rtrim(config('app.frontend_url'), '/');
    
            return $frontend.'/reset-password?token='.$token.'&email='.urlencode($user->email);
        });

        Carbon::serializeUsing(fn ($carbon) => $carbon->format('Y-m-d H:i:s'));
    }
}
