<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('renders the overview dashboard', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('Track Overview')
        ->assertSee('Jobs')
        ->assertSee('Kochkammer')
        ->assertSee('Linie 1')
        ->assertSee('Linie 6')
        ->assertSee('Arbeitsplatz')
        ->assertSee('Required PEText1')
        ->assertSee('/api/coldstore/jobs', false)
        ->assertDontSee('Bewegung aktiv')
        ->assertDontSee('track-map--rotated', false)
        ->assertSee('Kuehlhaus')
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

it('embeds map rotation config for the frontend dashboard', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.overview_path', '/overview');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    Http::fake([
        'http://coldstore.test/overview' => Http::response([
            'frame_id' => 'remote_frame',
            'stamp_sec' => 1050.0,
            'bev_stamp_sec' => 1049.8,
            'lookup_mode' => 'track_id',
            'map_rotation_deg' => 22.0,
            'overview_image_url' => 'http://coldstore.test/overview-image',
            'tracks' => [
                [
                    'track_id' => 7,
                    'barcode_id' => '',
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'static',
                    'confidence' => 0.91,
                    'x' => 1.2,
                    'y' => 2.5,
                    'z' => 0,
                ],
            ],
            'highlighted_racks' => [],
            'coldstore' => [
                'name' => 'Remote Haus',
                'summary' => 'Live vom KI-PC',
            ],
        ]),
    ]);

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('"rotation_deg":22', false)
        ->assertSee('"track_stamp_sec":1050', false);
});
