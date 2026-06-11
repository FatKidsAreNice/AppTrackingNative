<?php

use App\Services\ColdstoreJobs\MockProductionOrderRepository;
use App\Services\ColdstoreJobs\ProductionOrderRepository;
use App\Services\ColdstoreJobs\SqlServerProductionOrderRepository;
use Illuminate\Support\Facades\Config;

it('binds the mock production order repository by default source', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    expect(app(ProductionOrderRepository::class))->toBeInstanceOf(MockProductionOrderRepository::class);
});

it('binds the sql server production order repository when configured', function () {
    Config::set('coldstore.jobs.production_orders.source', 'sqlsrv');

    $resolvedRepository = app(ProductionOrderRepository::class);

    if (SqlServerProductionOrderRepository::driverAvailable()) {
        expect($resolvedRepository)->toBeInstanceOf(SqlServerProductionOrderRepository::class);

        return;
    }

    expect($resolvedRepository)->toBeInstanceOf(MockProductionOrderRepository::class);
});

it('falls back to the mock production order repository for unknown source values', function () {
    Config::set('coldstore.jobs.production_orders.source', 'unexpected');

    expect(app(ProductionOrderRepository::class))->toBeInstanceOf(MockProductionOrderRepository::class);
});
