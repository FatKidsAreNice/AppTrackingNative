<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('proxies a manual track marriage request to the remote api', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.assignment_path', '/track-marriages');

    Http::fake([
        'http://coldstore.test/track-marriages' => Http::response([
            'success' => true,
            'message' => 'UID 4711 wurde Track T88 zugeordnet.',
            'track_id' => 88,
            'uid' => '4711',
            'marriage_state' => 'assigned',
        ], 201),
    ]);

    $response = $this->postJson(route('api.coldstore.track-marriages.store'), [
        'track_id' => 88,
        'uid' => '4711',
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('track_id', 88)
        ->assertJsonPath('uid', '4711')
        ->assertJsonPath('marriage_state', 'assigned');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://coldstore.test/track-marriages'
            && $request['track_id'] === 88
            && $request['uid'] === '4711'
            && $request['mode'] === 'manual_overview_assignment';
    });
});

it('returns a service error when no remote assignment endpoint is configured', function () {
    Config::set('coldstore.remote.base_url', null);

    $response = $this->postJson(route('api.coldstore.track-marriages.store'), [
        'track_id' => 88,
        'uid' => '4711',
    ]);

    $response->assertStatus(503)
        ->assertJsonPath('message', 'Kein Remote-Endpunkt fuer Track-Assignment konfiguriert.');
});

it('validates track marriage payloads', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->postJson(route('api.coldstore.track-marriages.store'), [
        'track_id' => 0,
        'uid' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['track_id', 'uid']);
});

it('passes through remote validation errors for track marriages', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.assignment_path', '/track-marriages');

    Http::fake([
        'http://coldstore.test/track-marriages' => Http::response([
            'success' => false,
            'reason' => 'track_not_eligible',
            'message' => 'Track is moving or not confirmed.',
        ], 409),
    ]);

    $response = $this->postJson(route('api.coldstore.track-marriages.store'), [
        'track_id' => 88,
        'uid' => '4711',
    ]);

    $response->assertStatus(409)
        ->assertJsonPath('reason', 'track_not_eligible')
        ->assertJsonPath('message', 'Track is moving or not confirmed.');
});
