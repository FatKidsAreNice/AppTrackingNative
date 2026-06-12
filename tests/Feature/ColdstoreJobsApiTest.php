<?php

use Illuminate\Support\Facades\Config;

it('returns a matching job payload for a line with a track linked uid', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 6]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 6)
        ->assertJsonPath('arbeitsplatz_nr', 3506)
        ->assertJsonPath('order.required_pe_text1', '95106')
        ->assertJsonPath('next_order.required_pe_text1', '91200')
        ->assertJsonPath('matching_uids.0.uid', 'UID-L6-A')
        ->assertJsonPath('matching_uids.0.track_id', 101)
        ->assertJsonPath('next_matching_uids.0.uid', 'UID-L1-A');
});

it('accepts line as an alias for selected line in the jobs api', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['line' => 6]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 6)
        ->assertJsonPath('arbeitsplatz_nr', 3506);
});

it('returns a line with order but without matching inventory hit', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 2]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 2)
        ->assertJsonPath('order.required_pe_text1', '97777')
        ->assertJsonPath('next_order', null)
        ->assertJsonCount(0, 'next_matching_uids')
        ->assertJsonCount(0, 'matching_uids');
});

it('returns a line without an open order', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 3]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 3)
        ->assertJsonPath('arbeitsplatz_nr', 3503)
        ->assertJsonPath('order', null)
        ->assertJsonPath('next_order', null)
        ->assertJsonCount(0, 'next_matching_uids')
        ->assertJsonCount(0, 'matching_uids');
});

it('returns multiple matching uids for a line with several hits', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 4]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 4)
        ->assertJsonCount(2, 'matching_uids')
        ->assertJsonPath('matching_uids.1.has_track_assignment', false);
});

it('returns matching uids even when no track is assigned', function () {
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 5]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 5)
        ->assertJsonPath('matching_uids.0.uid', 'UID-L5-A')
        ->assertJsonPath('matching_uids.0.track_id', null)
        ->assertJsonPath('matching_uids.0.has_track_assignment', false);
});
