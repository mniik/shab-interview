<?php

namespace App\Providers;

use App\Repositories\Product\ProductElasticSearchRepository;
use App\Repositories\Product\ProductEloquentSearchRepository;
use App\Repositories\Product\ProductSearchRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductSearchRepository::class, function ($app) {
            if (! config('services.search.enabled')) {
                return new ProductEloquentSearchRepository();
            }

            return new ProductElasticSearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
