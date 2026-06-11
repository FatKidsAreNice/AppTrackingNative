<?php

it('returns a matching job payload for a line with a track linked uid', function () {
    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 6]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 6)
        ->assertJsonPath('arbeitsplatz_nr', 3506)
        ->assertJsonPath('order.required_pe_text1', '95106')
        ->assertJsonPath('matching_uids.0.uid', 'UID-L6-A')
        ->assertJsonPath('matching_uids.0.track_id', 101);
});

it('returns a line with order but without matching inventory hit', function () {
    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 2]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 2)
        ->assertJsonPath('order.required_pe_text1', '97777')
        ->assertJsonCount(0, 'matching_uids');
});

it('returns a line without an open order', function () {
    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 3]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 3)
        ->assertJsonPath('arbeitsplatz_nr', 3503)
        ->assertJsonPath('order', null)
        ->assertJsonCount(0, 'matching_uids');
});

it('returns multiple matching uids for a line with several hits', function () {
    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 4]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 4)
        ->assertJsonCount(2, 'matching_uids')
        ->assertJsonPath('matching_uids.1.has_track_assignment', false);
});

it('returns matching uids even when no track is assigned', function () {
    $response = $this->getJson(route('api.coldstore.jobs', ['selected_line' => 5]));

    $response->assertSuccessful()
        ->assertJsonPath('selected_line', 5)
        ->assertJsonPath('matching_uids.0.uid', 'UID-L5-A')
        ->assertJsonPath('matching_uids.0.track_id', null)
        ->assertJsonPath('matching_uids.0.has_track_assignment', false);
});
