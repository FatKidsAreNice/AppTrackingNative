<?php

use App\Services\ColdstoreJobs\KskCsvLookup;

function writeKskCsvFixture(string $contents): string
{
    $directory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'testing';

    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $path = $directory.DIRECTORY_SEPARATOR.'ksk-test.csv';

    file_put_contents($path, $contents);

    return $path;
}

it('reads ksk percentages from the configured csv using the art nr and ksk headers', function () {
    $path = writeKskCsvFixture(<<<'CSV'
ignored;Art.-Nr.;Soll KSK in %
foo;95101;7,5
bar;95106;12,25
CSV);

    $lookup = new KskCsvLookup($path);

    expect($lookup->percentForRequiredPeText1('95101'))->toBe(7.5)
        ->and($lookup->percentForRequiredPeText1('95106'))->toBe(12.25);
});

it('falls back to columns b and c when the csv has no usable header row', function () {
    $path = writeKskCsvFixture(<<<'CSV'
row-a;95101;11,0
row-b;95106;9
CSV);

    $lookup = new KskCsvLookup($path);

    expect($lookup->percentForRequiredPeText1('95101'))->toBe(11.0)
        ->and($lookup->percentForRequiredPeText1('95106'))->toBe(9.0);
});

it('returns null when the csv is missing or the material number is unknown', function () {
    $lookup = new KskCsvLookup(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'testing'.DIRECTORY_SEPARATOR.'missing-ksk.csv');

    expect($lookup->percentForRequiredPeText1('95101'))->toBeNull()
        ->and($lookup->percentForRequiredPeText1(''))->toBeNull();
});
