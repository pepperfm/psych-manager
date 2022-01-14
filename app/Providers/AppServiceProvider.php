<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

use App\Http\Responders\APIBaseResponder;

use App\Contracts\ResponseContract;
use App\Services\App\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (!config('app.docker_building')) {
            $client = \DB::table('oauth_clients')->where([
                ['password_client', true],
                ['personal_access_client', false],
            ])->first();
            $this->app->singleton(Client::class, fn() => new Client(
                clientId: $client->id,
                clientSecret: $client->secret
            ));
        }

        $this->app->singleton(ResponseContract::class, APIBaseResponder::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     *
     * @return void
     */
    public function boot(UrlGenerator $url): void
    {
        if (!$this->app->isLocal()) {
            $url->forceScheme('https');
        }
        Paginator::useBootstrap();
    }
}
