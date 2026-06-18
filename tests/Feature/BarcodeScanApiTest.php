<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('forwards a barcode scan to the remote pc', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.barcode_path', '/barcode-scan');
    Http::fake([
        'http://coldstore.test/barcode-scan' => Http::response([
            'accepted' => true,
            'event_id' => 'scan-20260608-0001',
            'scanner_id' => 'coldstore-entry-01',
            'direction' => 'entry',
            'barcode_id' => 'BC-12345',
            'message' => 'Scan accepted and forwarded to tracking.',
        ], 200),
    ]);

    $response = $this->postJson(route('api.coldstore.barcodes.store'), [
        'barcode_id' => 'BC-12345',
        'scanner_id' => 'coldstore-entry-01',
        'direction' => 'entry',
        'scanned_at' => '2026-06-08T10:00:00+02:00',
    ]);

    $response->assertCreated()
        ->assertJsonPath('scan.barcode_id', 'BC-12345')
        ->assertJsonPath('scan.direction', 'entry')
        ->assertJsonPath('message', 'Barcode erfolgreich an den anderen PC gesendet.');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://coldstore.test/barcode-scan'
            && $request['barcode_id'] === 'BC-12345'
            && $request['direction'] === 'entry'
            && $request['source'] === 'nativephp-mobile';
    });
});

it('forwards a marriage scan with the selected track context', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.barcode_path', '/barcode-scan');
    Http::fake([
        'http://coldstore.test/barcode-scan' => Http::response([
            'accepted' => true,
            'message' => 'Marriage scan accepted.',
        ], 200),
    ]);

    $response = $this->postJson(route('api.coldstore.barcodes.store'), [
        'barcode_id' => '32171700',
        'scanner_id' => 'coldstore-entry-01',
        'direction' => 'entry',
        'mode' => 'marriage',
        'track_id' => 1,
    ]);

    $response->assertCreated()
        ->assertJsonPath('scan.mode', 'marriage')
        ->assertJsonPath('scan.track_id', 1);

    Http::assertSent(function ($request) {
        return $request->url() === 'http://coldstore.test/barcode-scan'
            && $request['mode'] === 'marriage'
            && $request['track_id'] === 1
            && $request['barcode_id'] === '32171700';
    });
});

it('returns a service error when no remote endpoint is configured', function () {
    Config::set('coldstore.remote.base_url', null);

    $response = $this->postJson(route('api.coldstore.barcodes.store'), [
        'barcode_id' => 'BC-12345',
        'scanner_id' => 'coldstore-entry-01',
        'direction' => 'entry',
    ]);

    $response->assertStatus(503)
        ->assertJsonPath('message', 'Kein Remote-Endpunkt für Barcode-POST konfiguriert.');
});

it('validates barcode payloads', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->postJson(route('api.coldstore.barcodes.store'), [
        'barcode_id' => '',
        'scanner_id' => '',
        'direction' => 'sideways',
        'scanned_at' => 'not-a-date',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['barcode_id', 'scanner_id', 'direction', 'scanned_at']);
});

it('requires a track id in marriage mode', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->postJson(route('api.coldstore.barcodes.store'), [
        'barcode_id' => '32171700',
        'scanner_id' => 'coldstore-entry-01',
        'direction' => 'entry',
        'mode' => 'marriage',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['track_id']);
});
