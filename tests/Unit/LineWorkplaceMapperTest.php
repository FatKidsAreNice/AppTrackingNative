<?php

use App\Services\ColdstoreJobs\LineWorkplaceMapper;

it('maps lines to workplace numbers centrally', function () {
    $mapper = new LineWorkplaceMapper;

    expect($mapper->workplaceNumberForLine(1))->toBe(3501)
        ->and($mapper->workplaceNumberForLine(6))->toBe(3506)
        ->and($mapper->workplaceNumberForLine(20))->toBe(3520)
        ->and($mapper->labelForLine(30))->toBe('Linie 30');
});

it('returns the supported lines list', function () {
    expect((new LineWorkplaceMapper)->lines())->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30]);
});
