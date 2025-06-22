<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Elasticsearch\ProductSearchRepository;
use App\Repositories\Elasticsearch\CategorySearchRepository;
use App\Repositories\Contracts\CategorySearchRepositoryInterface;
use App\Repositories\Contracts\ProductSearchRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        $this->app->bind(
            ProductSearchRepositoryInterface::class,
            ProductSearchRepository::class
        );
        $this->app->bind(
            CategorySearchRepositoryInterface::class,
            CategorySearchRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
