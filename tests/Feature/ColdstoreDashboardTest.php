<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('defines local as the default jobs data source in config', function () {
    expect(file_get_contents(config_path('coldstore.php')))
        ->toContain("env('COLDSTORE_JOBS_DATA_SOURCE', 'local')");
});

it('renders the overview dashboard with local jobs api config', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
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
        ->assertSee('"dataSource":"local"', false)
        ->assertSee('"jobsPath":"\\/api\\/coldstore\\/jobs"', false)
        ->assertDontSee('Bewegung aktiv')
        ->assertDontSee('track-map--rotated', false)
        ->assertSee('Kühlhaus')
        ->assertSee('BEV-Quelle');
});

it('renders a neutral loading jobs state for remote api mode', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'remote_api');
    Config::set('coldstore.jobs.remote_api_base_url', 'http://10.10.123.66:8000');
    Config::set('coldstore.jobs.production_orders.source', 'sqlsrv');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('"dataSource":"remote_api"', false)
        ->assertSee('"baseUrl":"http:\\/\\/10.10.123.66:8000"', false)
        ->assertSee('"jobsPath":"\\/api\\/coldstore\\/jobs"', false)
        ->assertSee('Jobs werden geladen ...')
        ->assertDontSee('UID-L6-A')
        ->assertDontSee('4711-06');
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
    Config::set('coldstore.jobs.data_source', 'local');
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
