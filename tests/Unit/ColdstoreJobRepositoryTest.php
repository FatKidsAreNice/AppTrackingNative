<?php

use App\Services\ColdstoreJobRepository;

it('returns prioritized sample jobs for the dashboard', function () {
    $jobs = app(ColdstoreJobRepository::class)->all();

    expect($jobs)
        ->toHaveCount(4)
        ->and($jobs[0])->toMatchArray([
            'uid' => '1',
            'destination' => 'Linie 1',
            'priority' => 1,
        ])
        ->and($jobs[3])->toMatchArray([
            'uid' => '4',
            'destination' => 'Linie 4',
            'priority' => 4,
        ]);
});
