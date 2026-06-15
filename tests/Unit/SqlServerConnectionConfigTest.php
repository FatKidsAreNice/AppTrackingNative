<?php

use App\Support\SqlServerConnectionConfig;

it('normalizes sql server encrypt flags to driver-compatible strings', function (mixed $input, string $expected) {
    expect(SqlServerConnectionConfig::normalizeEncryptOption($input))->toBe($expected);
})->with([
    ['true', 'yes'],
    ['false', 'no'],
    [true, 'yes'],
    [false, 'no'],
    ['yes', 'yes'],
    ['no', 'no'],
    ['optional', 'optional'],
    ['strict', 'strict'],
    ['unexpected', 'yes'],
]);

it('normalizes the sql server charset for the native driver', function (mixed $input, string $expected) {
    expect(SqlServerConnectionConfig::normalizeCharset($input))->toBe($expected);
})->with([
    ['utf8', 'UTF-8'],
    ['UTF-8', 'UTF-8'],
    [' utf-8 ', 'UTF-8'],
    [null, 'UTF-8'],
    ['CP1', 'CP1'],
]);
