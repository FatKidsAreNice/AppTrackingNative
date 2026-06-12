<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('returns demo overview data when no remote url is configured', function () {
    Config::set('coldstore.remote.base_url', null);

    $response = $this->getJson(route('api.coldstore.overview'));

    $response->assertSuccessful()
        ->assertJsonPath('meta.source_mode', 'demo')
        ->assertJsonPath('overview.title', 'Coldstore Overview')
        ->assertJsonCount(2, 'tracks');
});

it('proxies overview data from the remote pc', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.overview_path', '/overview');
    Http::fake([
        'http://coldstore.test/overview' => Http::response([
            'frame_id' => 'remote_frame',
            'stamp_sec' => 1050.0,
            'bev_stamp_sec' => 1049.8,
            'lookup_mode' => 'track_id',
            'map_rotation_deg' => 22.0,
            'overview_image_url' => 'http://coldstore.test/overview-image',
            'bev_image_url' => 'http://coldstore.test/bev-image',
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
                    'yaw' => 0.3,
                    'hit_count' => 4,
                    'missed_updates' => 1,
                ],
            ],
            'highlighted_racks' => [],
            'coldstore' => [
                'name' => 'Remote Haus',
                'summary' => 'Live vom KI-PC',
            ],
        ]),
    ]);

    $response = $this->getJson(route('api.coldstore.overview'));

    $response->assertSuccessful()
        ->assertJsonPath('meta.source_mode', 'remote')
        ->assertJsonPath('meta.frame_id', 'remote_frame')
        ->assertJsonPath('meta.track_stamp_sec', 1050)
        ->assertJsonPath('tracks.0.track_id', 7)
        ->assertJsonPath('tracks.0.class_name', 'rack_side')
        ->assertJsonPath('map.rotation_deg', 22)
        ->assertJsonPath('map.background_url', 'http://coldstore.test/overview-image')
        ->assertJsonPath('coldstore.name', 'Remote Haus');
});

it('falls back to demo data when the remote pc is unavailable', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.demo_fallback', true);
    Http::fake([
        'http://coldstore.test/*' => Http::response([], 500),
    ]);

    $response = $this->getJson(route('api.coldstore.overview'));

    $response->assertSuccessful()
        ->assertJsonPath('meta.source_mode', 'demo')
        ->assertJsonPath('overview.status_text', 'Remote-API nicht erreichbar, Demo-Daten aktiv.');
});
