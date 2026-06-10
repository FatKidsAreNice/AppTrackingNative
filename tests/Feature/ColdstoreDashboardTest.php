<?php

use Illuminate\Support\Facades\Config;

it('renders the overview dashboard', function () {
    Config::set('coldstore.remote.base_url', null);

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('Track Overview')
        ->assertSee('Jobs')
        ->assertSee('Linie 1')
        ->assertSee('Kühlhaus')
        ->assertSee('BEV-Quelle');
});

it('renders the scanner module', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->get(route('coldstore.scanner'));

    $response->assertSuccessful()
        ->assertSee('Barcode Modul')
        ->assertSee('Scanner ID')
        ->assertSee('Richtung');
});

it('renders the settings page', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->get(route('coldstore.settings'));

    $response->assertSuccessful()
        ->assertSee('Integration Status')
        ->assertSee('http://coldstore.test')
        ->assertSee('Scan-Richtung');
});
