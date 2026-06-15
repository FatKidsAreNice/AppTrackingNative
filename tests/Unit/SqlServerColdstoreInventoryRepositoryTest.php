<?php

use App\Services\ColdstoreJobs\ColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository;
use Illuminate\Database\QueryException;

it('exposes the expected sql server cabinet content query', function () {
    $sql = (new SqlServerColdstoreInventoryRepository('coldstore_sqlsrv'))->sql();

    expect($sql)->toContain('SELECT TOP (1)')
        ->and($sql)->toContain('FROM ChargenGewicht cg')
        ->and($sql)->toContain('LEFT JOIN Lager lager_von')
        ->and($sql)->toContain('LEFT JOIN Lager lager_nach')
        ->and($sql)->toContain('LEFT JOIN EtikInterface ei')
        ->and($sql)->toContain('ei.EtikInterface_ID = cg.ChargenStueck_UID')
        ->and($sql)->toContain('cg.ChargenStueck_UID = ?')
        ->and($sql)->toContain('cg.last_booking = 1')
        ->and($sql)->toContain('Lager_Von_Name')
        ->and($sql)->toContain('Lager_Nach_Name')
        ->and($sql)->toContain('EtikInterface_PEText1');
});

it('maps a sql server cabinet content row to the expected payload shape', function () {
    $row = (object) [
        'ChargenStueck_UID' => ' 32171700 ',
        'ChargenGewicht_Netto' => 123.45,
        'Lager_ID_Von' => 12,
        'Lager_Von_Name' => ' Produktion ',
        'Lager_ID_Nach' => 34,
        'Lager_Nach_Name' => ' Kühlhaus ',
        'last_booking' => 1,
        'EtikInterface_PEText1' => ' 95106 ',
    ];

    expect((new SqlServerColdstoreInventoryRepository('coldstore_sqlsrv'))->mapContentRow($row))->toMatchArray([
        'uid' => '32171700',
        'material_pe_text1' => '95106',
        'net_weight_kg' => 123.45,
        'lager_von_id' => 12,
        'lager_von_name' => 'Produktion',
        'lager_nach_id' => 34,
        'lager_nach_name' => 'Kühlhaus',
        'last_booking' => true,
    ]);
});

it('returns null when no last booking row is found for a uid', function () {
    $repository = new class extends SqlServerColdstoreInventoryRepository
    {
        protected function fetchContentRow(string $uid): mixed
        {
            return null;
        }
    };

    expect($repository->findCurrentContentByUid('32171700'))->toBeNull();
});

it('keeps the uid as a string when loading cabinet content', function () {
    $repository = new class extends SqlServerColdstoreInventoryRepository
    {
        protected function fetchContentRow(string $uid): mixed
        {
            return (object) [
                'ChargenStueck_UID' => '32171700',
                'ChargenGewicht_Netto' => 123.45,
                'Lager_ID_Von' => 12,
                'Lager_Von_Name' => 'Produktion',
                'Lager_ID_Nach' => 34,
                'Lager_Nach_Name' => 'Kühlhaus',
                'last_booking' => 1,
                'EtikInterface_PEText1' => '95106',
            ];
        }
    };

    expect($repository->findCurrentContentByUid('32171700')['uid'])->toBe('32171700');
});

it('falls back to the mock inventory repository when the sql server query fails', function () {
    $repository = new class extends SqlServerColdstoreInventoryRepository
    {
        protected function fetchContentRow(string $uid): mixed
        {
            throw new QueryException(
                'coldstore_sqlsrv',
                $this->sql(),
                [$uid],
                new PDOException('could not find driver'),
            );
        }

        protected function fallbackRepository(): ColdstoreInventoryRepository
        {
            return new class extends ColdstoreInventoryRepository
            {
                public function allCurrent(): array
                {
                    return [];
                }

                public function findCurrentContentByUid(string $uid): ?array
                {
                    return [
                        'uid' => '32171700',
                        'material_pe_text1' => '95106',
                        'net_weight_kg' => 123.45,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ];
                }

                public function sourceMode(): string
                {
                    return 'mock';
                }
            };
        }
    };

    expect($repository->findCurrentContentByUid('32171700'))->toMatchArray([
        'uid' => '32171700',
        'material_pe_text1' => '95106',
        'lager_von_name' => 'Produktion',
        'lager_nach_name' => 'Kühlhaus',
        'last_booking' => true,
    ])->and($repository->sourceMode())->toBe('mock');
});
