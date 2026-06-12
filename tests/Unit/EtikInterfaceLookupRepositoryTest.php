<?php

use App\Services\ColdstoreJobs\EtikInterfaceLookupRepository;
use Illuminate\Database\QueryException;

it('builds the product name lookup query from required pe text1 against matstamm', function () {
    $sql = (new EtikInterfaceLookupRepository)->productNameLookupSql();

    expect($sql)->toContain('FROM MatStamm m')
        ->and($sql)->toContain('MatStamm_FuellArtNr')
        ->and($sql)->toContain('MatStamm_MaktX')
        ->and($sql)->toContain('END = ?')
        ->and($sql)->toContain('MatStamm_MatNr ASC');
});

it('returns the matching product name for a required pe text1 lookup', function () {
    $repository = new class extends EtikInterfaceLookupRepository
    {
        protected function fetchProductNameRow(string $requiredPeText1): mixed
        {
            expect($requiredPeText1)->toBe('95012');

            return (object) [
                'MatStamm_MaktX' => ' ALDI GUE Gef-Mortadella HF2 QS 200g MAP ',
            ];
        }
    };

    expect($repository->productNameForRequiredPeText1('95012'))
        ->toBe('ALDI GUE Gef-Mortadella HF2 QS 200g MAP');
});

it('returns null when the required pe text1 lookup query fails', function () {
    $repository = new class extends EtikInterfaceLookupRepository
    {
        protected function fetchProductNameRow(string $requiredPeText1): mixed
        {
            throw new QueryException(
                'coldstore_sqlsrv',
                $this->productNameLookupSql(),
                [$requiredPeText1],
                new PDOException('could not find driver'),
            );
        }
    };

    expect($repository->productNameForRequiredPeText1('95012'))->toBeNull();
});
