<?php

use App\Services\ColdstoreJobs\ColdstoreInventoryRepository;
use App\Services\ColdstoreJobs\JobMatchingService;
use App\Services\ColdstoreJobs\LineWorkplaceMapper;
use App\Services\ColdstoreJobs\ProductionOrderRepository;

function makeJobMatchingService(?array $orderOverride = null, ?array $inventoryOverride = null): JobMatchingService
{
    return new JobMatchingService(
        new LineWorkplaceMapper,
        new class($orderOverride) extends ProductionOrderRepository
        {
            public function __construct(private ?array $orderOverride) {}

            public function nextOpenOrderForWorkplace(int $workplaceNumber): ?array
            {
                if ($this->orderOverride !== null) {
                    return $this->orderOverride;
                }

                return match ($workplaceNumber) {
                    3502 => [
                        'va_id' => 11002,
                        'va_auftragsnr' => '4711-02',
                        'va_status' => 2,
                        'matstamm_matnr' => '100002',
                        'matstamm_maktx' => 'Kochschinken Linie 2',
                        'matstamm_fuellartnr' => ' F7777 ',
                        'va_beginn_soll' => '2026-06-11T07:30:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ],
                    3503 => null,
                    3504 => [
                        'va_id' => 11004,
                        'va_auftragsnr' => '4711-04',
                        'va_status' => 2,
                        'matstamm_matnr' => '100004',
                        'matstamm_maktx' => 'Salami Linie 4',
                        'matstamm_fuellartnr' => 'F8888',
                        'va_beginn_soll' => '2026-06-11T08:30:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ],
                    3505 => [
                        'va_id' => 11005,
                        'va_auftragsnr' => '4711-05',
                        'va_status' => 2,
                        'matstamm_matnr' => '100005',
                        'matstamm_maktx' => 'Putenbrust Linie 5',
                        'matstamm_fuellartnr' => 'F1234',
                        'va_beginn_soll' => '2026-06-11T09:00:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ],
                    default => [
                        'va_id' => 11006,
                        'va_auftragsnr' => '4711-06',
                        'va_status' => 2,
                        'matstamm_matnr' => '100006',
                        'matstamm_maktx' => 'Beispielauftrag Linie 6',
                        'matstamm_fuellartnr' => 'F5106',
                        'va_beginn_soll' => '2026-06-11T08:00:00',
                        'va_beginn_ist' => null,
                        'va_ende_soll' => null,
                        'va_ende_ist' => null,
                    ],
                };
            }

            public function sourceMode(): string
            {
                return 'test';
            }
        },
        new class($inventoryOverride) extends ColdstoreInventoryRepository
        {
            public function __construct(private ?array $inventoryOverride) {}

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

            public function sourceMode(): string
            {
                return 'test';
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
        ->and($payload['order']['required_pe_text1'])->toBe('95106')
        ->and($payload['matching_uids'])->toHaveCount(1)
        ->and($payload['matching_uids'][0])->toMatchArray([
            'uid' => 'UID-L6-A',
            'track_id' => 101,
            'etikinterface_pe_text1' => '95106',
            'has_track_assignment' => true,
        ]);
});

it('returns no matching uid when the order has no current coldstore hit', function () {
    $payload = makeJobMatchingService()->payloadForLine(2);

    expect($payload['order']['required_pe_text1'])->toBe('97777')
        ->and($payload['matching_uids'])->toBe([]);
});

it('returns no order when the selected line has no open production order', function () {
    $payload = makeJobMatchingService()->payloadForLine(3);

    expect($payload['arbeitsplatz_nr'])->toBe(3503)
        ->and($payload['order'])->toBeNull()
        ->and($payload['matching_uids'])->toBe([]);
});

it('returns multiple matching uids when the coldstore inventory contains more than one hit', function () {
    $payload = makeJobMatchingService()->payloadForLine(4);

    expect($payload['order']['required_pe_text1'])->toBe('98888')
        ->and($payload['matching_uids'])->toHaveCount(2)
        ->and(collect($payload['matching_uids'])->pluck('uid')->all())->toBe(['UID-L4-A', 'UID-L4-B']);
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
