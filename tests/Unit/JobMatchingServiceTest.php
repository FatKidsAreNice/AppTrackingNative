<?php

use App\Services\ColdstoreJobs\ColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\EtikInterfaceLookupRepository;
use App\Services\ColdstoreJobs\JobMatchingService;
use App\Services\ColdstoreJobs\LineWorkplaceMapper;
use App\Services\ColdstoreJobs\ProductionOrderRepository;

function makeJobMatchingService(?array $orderOverride = null, ?array $inventoryOverride = null, ?array $lookupOverride = null, ?array $cabinetContentOverride = null): JobMatchingService
{
    return new JobMatchingService(
        new LineWorkplaceMapper,
        new class($orderOverride) extends ProductionOrderRepository
        {
            public function __construct(private ?array $orderOverride) {}

            public function nextOpenOrderForWorkplace(int $workplaceNumber): ?array
            {
                return $this->openOrdersForWorkplace($workplaceNumber, 1)[0] ?? null;
            }

            public function openOrdersForWorkplace(int $workplaceNumber, int $limit = 2): array
            {
                if ($this->orderOverride !== null) {
                    $orders = array_is_list($this->orderOverride) ? $this->orderOverride : [$this->orderOverride];

                    return array_slice($orders, 0, $limit);
                }

                $order = match ($workplaceNumber) {
                    3502 => [[
                        'va_id' => 11002,
                        'va_auftragsnr' => '4711-02',
                        'va_status' => 2,
                        'matstamm_matnr' => '100002',
                        'matstamm_maktx' => 'Kochschinken Linie 2',
                        'matstamm_fuellartnr' => ' F7777 ',
                        'required_product_name' => null,
                        'va_menge_kg' => null,
                        'va_beginn_soll' => '2026-06-11T07:30:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ]],
                    3503 => [],
                    3504 => [[
                        'va_id' => 11004,
                        'va_auftragsnr' => '4711-04',
                        'va_status' => 2,
                        'matstamm_matnr' => '100004',
                        'matstamm_maktx' => 'Salami Linie 4',
                        'matstamm_fuellartnr' => 'F8888',
                        'required_product_name' => 'Salami fuer PEText1 98888',
                        'va_menge_kg' => 96.25,
                        'va_beginn_soll' => '2026-06-11T08:30:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ]],
                    3505 => [[
                        'va_id' => 11005,
                        'va_auftragsnr' => '4711-05',
                        'va_status' => 2,
                        'matstamm_matnr' => '100005',
                        'matstamm_maktx' => 'Putenbrust Linie 5',
                        'matstamm_fuellartnr' => 'F1234',
                        'required_product_name' => 'Putenbrust fuer PEText1 91234',
                        'va_menge_kg' => 82.0,
                        'va_beginn_soll' => '2026-06-11T09:00:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ]],
                    default => [[
                        'va_id' => 11006,
                        'va_auftragsnr' => '4711-06',
                        'va_status' => 2,
                        'matstamm_matnr' => '100006',
                        'matstamm_maktx' => 'Beispielauftrag Linie 6',
                        'matstamm_fuellartnr' => 'F5106',
                        'required_product_name' => 'Produkt fuer PEText1 95106',
                        'va_menge_kg' => 123.45,
                        'va_beginn_soll' => '2026-06-11T08:00:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ]],
                };

                return array_slice($order, 0, $limit);
            }

            public function sourceMode(): string
            {
                return 'test';
            }
        },
        new class($inventoryOverride, $cabinetContentOverride) extends ColdstoreInventoryRepository
        {
            public function __construct(private ?array $inventoryOverride, private ?array $cabinetContentOverride) {}

            public function allCurrent(): array
            {
                if ($this->inventoryOverride !== null) {
                    return $this->inventoryOverride;
                }

                return [
                    [
                        'uid' => 'UID-L6-A',
                        'track_id' => 101,
                        'etikinterface_id' => 90006,
                        'etikinterface_pe_text1' => '95106',
                        'fuellartnr' => 'F5106',
                        'position' => ['x' => -9.2, 'y' => 2.4],
                        'state' => 'in_coldstore',
                        'scanned_at' => '2026-06-11T07:40:00',
                        'last_seen_at' => '2026-06-11T08:02:00',
                    ],
                    [
                        'uid' => 'UID-L4-A',
                        'track_id' => 204,
                        'etikinterface_id' => 90041,
                        'etikinterface_pe_text1' => '98888',
                        'fuellartnr' => 'F8888',
                        'position' => ['x' => -2.7, 'y' => -3.1],
                        'state' => 'reserved',
                        'scanned_at' => '2026-06-11T08:05:00',
                        'last_seen_at' => '2026-06-11T08:32:00',
                    ],
                    [
                        'uid' => 'UID-L4-B',
                        'track_id' => null,
                        'etikinterface_id' => 90042,
                        'etikinterface_pe_text1' => '98888',
                        'fuellartnr' => 'F8888',
                        'position' => null,
                        'state' => 'in_coldstore',
                        'scanned_at' => '2026-06-11T08:06:00',
                        'last_seen_at' => '2026-06-11T08:33:00',
                    ],
                    [
                        'uid' => 'UID-L5-A',
                        'track_id' => null,
                        'etikinterface_id' => 90051,
                        'etikinterface_pe_text1' => '91234',
                        'fuellartnr' => 'F1234',
                        'position' => null,
                        'state' => 'in_coldstore',
                        'scanned_at' => '2026-06-11T08:10:00',
                        'last_seen_at' => '2026-06-11T08:40:00',
                    ],
                ];
            }

            public function findCurrentContentByUid(string $uid): ?array
            {
                if ($this->cabinetContentOverride !== null) {
                    return $this->cabinetContentOverride[trim($uid)] ?? null;
                }

                return [
                    'UID-L6-A' => [
                        'uid' => 'UID-L6-A',
                        'material_pe_text1' => '95106',
                        'net_weight_kg' => 123.45,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-L4-A' => [
                        'uid' => 'UID-L4-A',
                        'material_pe_text1' => '98888',
                        'net_weight_kg' => 96.25,
                        'lager_von_id' => 21,
                        'lager_von_name' => 'Reifung',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-L4-B' => [
                        'uid' => 'UID-L4-B',
                        'material_pe_text1' => '98888',
                        'net_weight_kg' => 87.1,
                        'lager_von_id' => 21,
                        'lager_von_name' => 'Reifung',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-L5-A' => [
                        'uid' => 'UID-L5-A',
                        'material_pe_text1' => '91234',
                        'net_weight_kg' => 82.0,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-CURRENT' => [
                        'uid' => 'UID-CURRENT',
                        'material_pe_text1' => '95106',
                        'net_weight_kg' => 123.45,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-NEXT' => [
                        'uid' => 'UID-NEXT',
                        'material_pe_text1' => '91200',
                        'net_weight_kg' => 98.7,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                    'UID-TRIM' => [
                        'uid' => 'UID-TRIM',
                        'material_pe_text1' => '95106',
                        'net_weight_kg' => 44.4,
                        'lager_von_id' => 12,
                        'lager_von_name' => 'Produktion',
                        'lager_nach_id' => 34,
                        'lager_nach_name' => 'Kühlhaus',
                        'last_booking' => true,
                    ],
                ][trim($uid)] ?? null;
            }

            public function sourceMode(): string
            {
                return 'test';
            }
        },
        new class($lookupOverride) extends EtikInterfaceLookupRepository
        {
            public function __construct(private ?array $lookupOverride) {}

            public function productNameForRequiredPeText1(string $requiredPeText1): ?string
            {
                return $this->lookupOverride[$requiredPeText1] ?? null;
            }
        },
    );
}

it('normalizes fuellart numbers from f to 9 using trimmed strings', function () {
    $service = makeJobMatchingService();

    expect($service->requiredPeText1ForFuellArtNr(' F5106 '))->toBe('95106')
        ->and($service->requiredPeText1ForFuellArtNr('95106'))->toBe('95106');
});

it('returns a matching uid with track assignment for the default mock line', function () {
    $payload = makeJobMatchingService()->payloadForLine(6);

    expect($payload['arbeitsplatz_nr'])->toBe(3506)
        ->and($payload['order']['matstamm_fuellartnr'])->toBe('F5106')
        ->and($payload['order']['required_product_name'])->toBe('Produkt fuer PEText1 95106')
        ->and($payload['order']['va_menge_kg'])->toBe(123.45)
        ->and($payload['order']['required_pe_text1'])->toBe('95106')
        ->and($payload['next_order'])->toBeNull()
        ->and($payload['next_matching_uids'])->toBe([])
        ->and($payload['matching_uids'])->toHaveCount(1)
        ->and($payload['matching_uids'][0])->toMatchArray([
            'uid' => 'UID-L6-A',
            'track_id' => 101,
            'etikinterface_pe_text1' => '95106',
            'has_track_assignment' => true,
            'matches_required_material' => true,
        ]);
});

it('returns a current order and a following order with separate inventory matches', function () {
    $payload = makeJobMatchingService(
        [
            [
                'va_id' => 1,
                'va_auftragsnr' => 'CURRENT',
                'va_status' => 2,
                'matstamm_matnr' => '100001',
                'matstamm_maktx' => 'Current product',
                'matstamm_fuellartnr' => 'F5106',
                'required_product_name' => 'Resolved current product',
                'va_menge_kg' => 123.45,
                'va_beginn_soll' => '2026-06-11T08:00:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => null,
                'va_ende_ist' => null,
            ],
            [
                'va_id' => 2,
                'va_auftragsnr' => 'NEXT',
                'va_status' => 2,
                'matstamm_matnr' => '100002',
                'matstamm_maktx' => 'Next product',
                'matstamm_fuellartnr' => 'F1200',
                'required_product_name' => 'Resolved next product',
                'va_menge_kg' => 98.7,
                'va_beginn_soll' => '2026-06-11T10:00:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => null,
                'va_ende_ist' => null,
            ],
        ],
        [
            [
                'uid' => 'UID-CURRENT',
                'track_id' => 10,
                'etikinterface_id' => 100,
                'etikinterface_pe_text1' => '95106',
                'fuellartnr' => 'F5106',
                'position' => null,
                'state' => 'in_coldstore',
                'scanned_at' => '2026-06-11T08:10:00',
                'last_seen_at' => '2026-06-11T08:10:00',
            ],
            [
                'uid' => 'UID-NEXT',
                'track_id' => 20,
                'etikinterface_id' => 200,
                'etikinterface_pe_text1' => '91200',
                'fuellartnr' => 'F1200',
                'position' => null,
                'state' => 'reserved',
                'scanned_at' => '2026-06-11T08:20:00',
                'last_seen_at' => '2026-06-11T08:20:00',
            ],
        ],
    )->payloadForLine(6);

    expect($payload['order']['va_auftragsnr'])->toBe('CURRENT')
        ->and($payload['order']['required_product_name'])->toBe('Resolved current product')
        ->and($payload['order']['va_menge_kg'])->toBe(123.45)
        ->and($payload['next_order']['va_auftragsnr'])->toBe('NEXT')
        ->and($payload['next_order']['required_product_name'])->toBe('Resolved next product')
        ->and($payload['next_order']['va_menge_kg'])->toBe(98.7)
        ->and($payload['matching_uids'])->toHaveCount(1)
        ->and($payload['matching_uids'][0]['uid'])->toBe('UID-CURRENT')
        ->and($payload['next_matching_uids'])->toHaveCount(1)
        ->and($payload['next_matching_uids'][0]['uid'])->toBe('UID-NEXT');
});

it('returns no matching uid when the order has no current coldstore hit', function () {
    $payload = makeJobMatchingService()->payloadForLine(2);

    expect($payload['order']['required_pe_text1'])->toBe('97777')
        ->and($payload['order']['required_product_name'])->toBeNull()
        ->and($payload['matching_uids'])->toBe([]);
});

it('resolves the required product name via lookup when the order does not provide one', function () {
    $payload = makeJobMatchingService(
        [
            'va_id' => 1,
            'va_auftragsnr' => '4711',
            'va_status' => 2,
            'matstamm_matnr' => '123456',
            'matstamm_maktx' => 'Auftragsartikel',
            'matstamm_fuellartnr' => 'F5106',
            'required_product_name' => null,
            'va_menge_kg' => 11.0,
            'va_beginn_soll' => '2026-06-11T08:00:00',
            'va_beginn_ist' => null,
            'va_ende_soll' => null,
            'va_ende_ist' => null,
        ],
        null,
        ['95106' => 'Lookup Produkt'],
    )->payloadForLine(6);

    expect($payload['order']['required_product_name'])->toBe('Lookup Produkt');
});

it('returns no order when the selected line has no open production order', function () {
    $payload = makeJobMatchingService()->payloadForLine(3);

    expect($payload['arbeitsplatz_nr'])->toBe(3503)
        ->and($payload['order'])->toBeNull()
        ->and($payload['next_order'])->toBeNull()
        ->and($payload['matching_uids'])->toBe([]);
});

it('returns multiple matching uids when the coldstore inventory contains more than one hit', function () {
    $payload = makeJobMatchingService()->payloadForLine(4);

    expect($payload['order']['required_pe_text1'])->toBe('98888')
        ->and($payload['matching_uids'])->toHaveCount(2)
        ->and(collect($payload['matching_uids'])->pluck('uid')->all())->toBe(['UID-L4-A', 'UID-L4-B']);
});

it('compares the required pe text1 against the cabinet content material exactly', function () {
    $service = makeJobMatchingService();

    expect($service->cabinetContentMatchesRequiredPeText1('95106', [
        'uid' => '32171700',
        'material_pe_text1' => '95106',
        'net_weight_kg' => 123.45,
        'lager_von_id' => 12,
        'lager_von_name' => 'Produktion',
        'lager_nach_id' => 34,
        'lager_nach_name' => 'Kühlhaus',
        'last_booking' => true,
    ]))->toBeTrue();
});

it('does not match different cabinet content material values', function () {
    $service = makeJobMatchingService();

    expect($service->cabinetContentMatchesRequiredPeText1('95106', [
        'uid' => '32171700',
        'material_pe_text1' => '95101',
        'net_weight_kg' => 123.45,
        'lager_von_id' => 12,
        'lager_von_name' => 'Produktion',
        'lager_nach_id' => 34,
        'lager_nach_name' => 'Kühlhaus',
        'last_booking' => true,
    ]))->toBeFalse();
});

it('returns no hit when the cabinet content cannot be loaded for an inventory uid', function () {
    $payload = makeJobMatchingService(
        null,
        [[
            'uid' => 'UID-MISSING',
            'track_id' => 101,
            'etikinterface_id' => 90006,
            'etikinterface_pe_text1' => '95106',
            'fuellartnr' => 'F5106',
            'position' => ['x' => -9.2, 'y' => 2.4],
            'state' => 'in_coldstore',
            'scanned_at' => '2026-06-11T07:40:00',
            'last_seen_at' => '2026-06-11T08:02:00',
        ]],
        null,
        [],
    )->payloadForLine(6);

    expect($payload['matching_uids'])->toBe([]);
});

it('keeps matching uids without track assignments visible', function () {
    $payload = makeJobMatchingService()->payloadForLine(5);

    expect($payload['matching_uids'])->toHaveCount(1)
        ->and($payload['matching_uids'][0])->toMatchArray([
            'uid' => 'UID-L5-A',
            'track_id' => null,
            'has_track_assignment' => false,
        ]);
});

it('matches only against the coldstore inventory repository using trimmed string values', function () {
    $service = makeJobMatchingService(
        [
            'va_id' => 1,
            'va_auftragsnr' => '4711',
            'va_status' => 2,
            'matstamm_matnr' => '123456',
            'matstamm_maktx' => 'Testartikel',
            'matstamm_fuellartnr' => ' F5106 ',
            'required_product_name' => null,
            'va_menge_kg' => 44.4,
            'va_beginn_soll' => '2026-06-11T08:00:00',
            'va_beginn_ist' => null,
            'va_ende_soll' => null,
            'va_ende_ist' => null,
        ],
        [[
            'uid' => ' UID-TRIM ',
            'track_id' => 77,
            'etikinterface_id' => 88,
            'etikinterface_pe_text1' => ' 95106 ',
            'fuellartnr' => null,
            'position' => null,
            'state' => 'in_coldstore',
            'scanned_at' => '2026-06-11T08:10:00',
            'last_seen_at' => '2026-06-11T08:10:00',
        ]],
    );

    $payload = $service->payloadForLine(6);

    expect($payload['matching_uids'])->toHaveCount(1)
        ->and($payload['matching_uids'][0])->toMatchArray([
            'uid' => 'UID-TRIM',
            'track_id' => 77,
            'etikinterface_pe_text1' => '95106',
        ]);
});
