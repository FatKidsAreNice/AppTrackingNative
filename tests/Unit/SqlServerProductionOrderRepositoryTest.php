<?php

use App\Services\ColdstoreJobs\MockProductionOrderRepository;
use App\Services\ColdstoreJobs\SqlServerProductionOrderRepository;
use Illuminate\Database\QueryException;

it('exposes the expected sql server query', function () {
    $sql = (new SqlServerProductionOrderRepository('coldstore_sqlsrv'))->sql();

    expect($sql)->toContain('SELECT TOP (2)')
        ->and($sql)->toContain('INNER JOIN MatStamm')
        ->and($sql)->toContain('OUTER APPLY')
        ->and($sql)->toContain('INNER JOIN MatStamm order_m')
        ->and($sql)->toContain('order_m.MatStamm_MaktX AS MatStamm_MaktX')
        ->and($sql)->toContain('order_m.MatStamm_FuellArtNr')
        ->and($sql)->toContain('required_key.Required_PEText1')
        ->and($sql)->toContain('Required_Product_Name')
        ->and($sql)->toContain('required_m.MatStamm_MatNr')
        ->and($sql)->toContain('= required_key.Required_PEText1')
        ->and($sql)->not->toContain('lookup.MatStamm_FuellArtNr')
        ->and($sql)->toContain('v.VA_Status = 2')
        ->and($sql)->toContain('v.VA_Mengekg')
        ->and($sql)->toContain('v.Arbeitsplatz_Nr = ?')
        ->and($sql)->toContain('CASE WHEN v.VA_BeginnSoll IS NULL THEN 1 ELSE 0 END')
        ->and($sql)->toContain('v.VA_BeginnSoll ASC')
        ->and($sql)->toContain('v.VA_ID ASC')
        ->and($sql)->toContain('Required_PEText1');
});

it('normalizes required pe text1 from fuellart numbers', function () {
    $repository = new SqlServerProductionOrderRepository('coldstore_sqlsrv');

    expect($repository->requiredPeText1FromFuellArtNr(' F5106 '))->toBe('95106')
        ->and($repository->requiredPeText1FromFuellArtNr('95106'))->toBe('95106');
});

it('mock production orders include current and following orders for a workplace', function () {
    $repository = new MockProductionOrderRepository;

    $orders = $repository->openOrdersForWorkplace(3506);

    expect($orders)->toHaveCount(2)
        ->and($orders[0]['va_auftragsnr'])->toBe('4711-06')
        ->and($orders[0]['required_product_name'])->toBe('Produkt fuer PEText1 95106')
        ->and($orders[0]['va_menge_kg'])->toBe(123.45)
        ->and($orders[1]['va_auftragsnr'])->toBe('4711-06-F')
        ->and($orders[1]['required_product_name'])->toBe('Rinderschinken fuer PEText1 91200')
        ->and($orders[1]['va_menge_kg'])->toBe(98.7)
        ->and($repository->nextOpenOrderForWorkplace(3506)['va_auftragsnr'])->toBe('4711-06');
});

it('limits mock production orders to the requested count', function () {
    expect((new MockProductionOrderRepository)->openOrdersForWorkplace(3506, 1))
        ->toHaveCount(1);
});

it('maps a sql server row to the existing order payload shape', function () {
    $row = (object) [
        'VA_ID' => 11006,
        'VA_Auftragsnr' => ' 4711-06 ',
        'VA_Status' => 2,
        'MatStamm_MatNr' => ' 100006 ',
        'MatStamm_MaktX' => ' Beispielauftrag Linie 6 ',
        'MatStamm_FuellArtNr' => ' F5106 ',
        'Required_Product_Name' => ' Produkt fuer PEText1 95106 ',
        'VA_Mengekg' => 123.45,
        'VA_BeginnSoll' => '2026-06-11 08:00:00',
        'VA_BeginnIst' => null,
        'VA_EndeSoll' => '2026-06-11 10:00:00',
        'VA_EndeIst' => null,
    ];

    expect((new SqlServerProductionOrderRepository('coldstore_sqlsrv'))->mapRow($row))->toMatchArray([
        'va_id' => 11006,
        'va_auftragsnr' => '4711-06',
        'va_status' => 2,
        'matstamm_matnr' => '100006',
        'matstamm_maktx' => 'Beispielauftrag Linie 6',
        'matstamm_fuellartnr' => 'F5106',
        'required_product_name' => 'Produkt fuer PEText1 95106',
        'va_menge_kg' => 123.45,
        'va_beginn_soll' => '2026-06-11 08:00:00',
        'va_beginn_ist' => null,
        'va_ende_soll' => '2026-06-11 10:00:00',
        'va_ende_ist' => null,
    ]);
});

it('maps two sql server rows as current and following open orders', function () {
    $repository = new class extends SqlServerProductionOrderRepository
    {
        protected function fetchRows(int $workplaceNumber, int $limit = 2): array
        {
            return [
                (object) [
                    'VA_ID' => 11006,
                    'VA_Auftragsnr' => '4711-06',
                    'VA_Status' => 2,
                    'MatStamm_MatNr' => '100006',
                    'MatStamm_MaktX' => 'Beispielauftrag Linie 6',
                    'MatStamm_FuellArtNr' => 'F5106',
                    'Required_Product_Name' => 'Produkt fuer PEText1 95106',
                    'VA_Mengekg' => 123.45,
                    'VA_BeginnSoll' => '2026-06-11 08:00:00',
                    'VA_BeginnIst' => null,
                    'VA_EndeSoll' => null,
                    'VA_EndeIst' => null,
                ],
                (object) [
                    'VA_ID' => 12006,
                    'VA_Auftragsnr' => '4711-06-F',
                    'VA_Status' => 2,
                    'MatStamm_MatNr' => '100106',
                    'MatStamm_MaktX' => 'Folgeauftrag Linie 6',
                    'MatStamm_FuellArtNr' => 'F1200',
                    'Required_Product_Name' => 'Rinderschinken fuer PEText1 91200',
                    'VA_Mengekg' => null,
                    'VA_BeginnSoll' => '2026-06-11 10:15:00',
                    'VA_BeginnIst' => null,
                    'VA_EndeSoll' => null,
                    'VA_EndeIst' => null,
                ],
            ];
        }
    };

    $orders = $repository->openOrdersForWorkplace(3506);

    expect($orders)->toHaveCount(2)
        ->and($orders[0])->toMatchArray([
            'va_id' => 11006,
            'va_auftragsnr' => '4711-06',
            'matstamm_fuellartnr' => 'F5106',
            'required_product_name' => 'Produkt fuer PEText1 95106',
            'va_menge_kg' => 123.45,
        ])
        ->and($orders[1])->toMatchArray([
            'va_id' => 12006,
            'va_auftragsnr' => '4711-06-F',
            'matstamm_fuellartnr' => 'F1200',
            'required_product_name' => 'Rinderschinken fuer PEText1 91200',
            'va_menge_kg' => null,
        ])
        ->and($repository->nextOpenOrderForWorkplace(3506))->toMatchArray([
            'va_id' => 11006,
            'va_auftragsnr' => '4711-06',
        ]);
});

it('falls back to the mock repository when the sql server query fails', function () {
    $repository = new class extends SqlServerProductionOrderRepository
    {
        protected function fetchRows(int $workplaceNumber, int $limit = 2): array
        {
            throw new QueryException(
                'coldstore_sqlsrv',
                $this->sql(),
                [$workplaceNumber],
                new PDOException('could not find driver'),
            );
        }

        protected function fallbackRepository(): MockProductionOrderRepository
        {
            return new MockProductionOrderRepository;
        }
    };

    $result = $repository->nextOpenOrderForWorkplace(3506);

    expect($result)->toMatchArray([
        'va_id' => 11006,
        'va_auftragsnr' => '4711-06',
        'matstamm_fuellartnr' => 'F5106',
    ])->and($repository->sourceMode())->toBe('mock');
});
