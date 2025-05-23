<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Sleep;

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
        Model::unguard();
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();

        DB::prohibitDestructiveCommands(
            App::isProduction()
        );

        Http::preventStrayRequests(
            App::runningUnitTests()
        );

        Date::use(
            CarbonImmutable::class
        );

        URL::forceHttps(
            App::isProduction()
        );

        Sleep::fake(
            Sleep::fake()
        );

        Vite::useAggressivePrefetching();
    }
}
