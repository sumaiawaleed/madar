<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Link;
use App\Models\Project;
use App\Models\Proposal;
use App\Policies\OwnedRecordPolicy;
use App\View\Composers\NavigationComposer;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        Carbon::setLocale('ar');

        foreach ([Client::class, Proposal::class, Project::class, Invoice::class, Appointment::class, Link::class] as $model) {
            Gate::policy($model, OwnedRecordPolicy::class);
        }

        View::composer('components.layouts.app', NavigationComposer::class);

        RateLimiter::for('login', fn (Request $request): Limit => Limit::perMinute(5)->by(
            Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip())
        ));
    }
}
