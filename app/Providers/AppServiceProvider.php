<?php

namespace App\Providers;

use App\Services\ColdstoreJobs\ColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\MockColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\MockProductionOrderRepository;
use App\Services\ColdstoreJobs\ProductionOrderRepository;
use App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\SqlServerProductionOrderRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductionOrderRepository::class, function ($app): ProductionOrderRepository {
            return match (config('coldstore.jobs.production_orders.source', 'mock')) {
                'mock' => $app->make(MockProductionOrderRepository::class),
                'sqlsrv' => SqlServerProductionOrderRepository::driverAvailable()
                    ? $app->make(SqlServerProductionOrderRepository::class)
                    : $app->make(MockProductionOrderRepository::class),
                default => $app->make(MockProductionOrderRepository::class),
            };
        });

        $this->app->bind(ColdstoreInventoryRepository::class, function ($app): ColdstoreInventoryRepository {
            return match (config('coldstore.jobs.inventory.driver', 'mock')) {
                'mock' => $app->make(MockColdstoreInventoryRepository::class),
                'sqlsrv' => SqlServerColdstoreInventoryRepository::driverAvailable()
                    ? $app->make(SqlServerColdstoreInventoryRepository::class)
                    : $app->make(MockColdstoreInventoryRepository::class),
                default => $app->make(MockColdstoreInventoryRepository::class),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isProduction()) {
            URL::forceHttps();
        }

        Vite::createAssetPathsUsing(function (string $path): string {
            return '/'.ltrim($path, '/');
        });
    }
}
