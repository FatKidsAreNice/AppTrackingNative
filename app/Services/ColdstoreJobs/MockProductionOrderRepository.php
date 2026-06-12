<?php

namespace App\Services\ColdstoreJobs;

class MockProductionOrderRepository extends ProductionOrderRepository
{
    /**
     * @return array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }|null
     */
    public function nextOpenOrderForWorkplace(int $workplaceNumber): ?array
    {
        return $this->openOrdersForWorkplace($workplaceNumber, 1)[0] ?? null;
    }

    /**
     * @return array<int, array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }>
     */
    public function openOrdersForWorkplace(int $workplaceNumber, int $limit = 2): array
    {
        return array_slice($this->orders()[$workplaceNumber] ?? [], 0, $limit);
    }

    public function sourceMode(): string
    {
        return 'mock';
    }

    /**
     * @return array<int, array<int, array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }>>
     */
    private function orders(): array
    {
        return [
            3501 => [[
                'va_id' => 11001,
                'va_auftragsnr' => '4711-01',
                'va_status' => 2,
                'matstamm_matnr' => '100001',
                'matstamm_maktx' => 'Rinderschinken Linie 1',
                'matstamm_fuellartnr' => 'F1200',
                'va_beginn_soll' => '2026-06-11T07:15:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T09:00:00',
                'va_ende_ist' => null,
            ]],
            3502 => [[
                'va_id' => 11002,
                'va_auftragsnr' => '4711-02',
                'va_status' => 2,
                'matstamm_matnr' => '100002',
                'matstamm_maktx' => 'Kochschinken Linie 2',
                'matstamm_fuellartnr' => ' F7777 ',
                'va_beginn_soll' => '2026-06-11T07:30:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T09:30:00',
                'va_ende_ist' => null,
            ]],
            3504 => [[
                'va_id' => 11004,
                'va_auftragsnr' => '4711-04',
                'va_status' => 2,
                'matstamm_matnr' => '100004',
                'matstamm_maktx' => 'Salami Linie 4',
                'matstamm_fuellartnr' => 'F8888',
                'va_beginn_soll' => '2026-06-11T08:30:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T10:00:00',
                'va_ende_ist' => null,
            ]],
            3505 => [[
                'va_id' => 11005,
                'va_auftragsnr' => '4711-05',
                'va_status' => 2,
                'matstamm_matnr' => '100005',
                'matstamm_maktx' => 'Putenbrust Linie 5',
                'matstamm_fuellartnr' => 'F1234',
                'va_beginn_soll' => '2026-06-11T09:00:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T10:30:00',
                'va_ende_ist' => null,
            ]],
            3506 => [
                [
                    'va_id' => 11006,
                    'va_auftragsnr' => '4711-06',
                    'va_status' => 2,
                    'matstamm_matnr' => '100006',
                    'matstamm_maktx' => 'Beispielauftrag Linie 6',
                    'matstamm_fuellartnr' => 'F5106',
                    'va_beginn_soll' => '2026-06-11T08:00:00',
                    'va_beginn_ist' => null,
                    'va_ende_soll' => '2026-06-11T10:00:00',
                    'va_ende_ist' => null,
                ],
                [
                    'va_id' => 12006,
                    'va_auftragsnr' => '4711-06-F',
                    'va_status' => 2,
                    'matstamm_matnr' => '100106',
                    'matstamm_maktx' => 'Folgeauftrag Linie 6',
                    'matstamm_fuellartnr' => 'F1200',
                    'va_beginn_soll' => '2026-06-11T10:15:00',
                    'va_beginn_ist' => null,
                    'va_ende_soll' => '2026-06-11T12:00:00',
                    'va_ende_ist' => null,
                ],
            ],
            3507 => [[
                'va_id' => 11007,
                'va_auftragsnr' => '4711-07',
                'va_status' => 2,
                'matstamm_matnr' => '100007',
                'matstamm_maktx' => 'Aufschnitt Linie 7',
                'matstamm_fuellartnr' => 'F7300',
                'va_beginn_soll' => '2026-06-11T10:15:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T12:00:00',
                'va_ende_ist' => null,
            ]],
            3508 => [[
                'va_id' => 11008,
                'va_auftragsnr' => '4711-08',
                'va_status' => 2,
                'matstamm_matnr' => '100008',
                'matstamm_maktx' => 'Fleischwurst Linie 8',
                'matstamm_fuellartnr' => 'F8001',
                'va_beginn_soll' => '2026-06-11T10:45:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T12:15:00',
                'va_ende_ist' => null,
            ]],
            3509 => [[
                'va_id' => 11009,
                'va_auftragsnr' => '4711-09',
                'va_status' => 2,
                'matstamm_matnr' => '100009',
                'matstamm_maktx' => 'Bratenaufschnitt Linie 9',
                'matstamm_fuellartnr' => 'F9001',
                'va_beginn_soll' => '2026-06-11T11:15:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T13:00:00',
                'va_ende_ist' => null,
            ]],
            3510 => [[
                'va_id' => 11010,
                'va_auftragsnr' => '4711-10',
                'va_status' => 2,
                'matstamm_matnr' => '100010',
                'matstamm_maktx' => 'Gekochter Schinken Linie 10',
                'matstamm_fuellartnr' => 'F1001',
                'va_beginn_soll' => '2026-06-11T11:45:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T13:30:00',
                'va_ende_ist' => null,
            ]],
            3520 => [[
                'va_id' => 11020,
                'va_auftragsnr' => '4711-20',
                'va_status' => 2,
                'matstamm_matnr' => '100020',
                'matstamm_maktx' => 'Sonderauftrag Linie 20',
                'matstamm_fuellartnr' => 'F2001',
                'va_beginn_soll' => '2026-06-11T12:00:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T14:00:00',
                'va_ende_ist' => null,
            ]],
            3530 => [[
                'va_id' => 11030,
                'va_auftragsnr' => '4711-30',
                'va_status' => 2,
                'matstamm_matnr' => '100030',
                'matstamm_maktx' => 'Sonderauftrag Linie 30',
                'matstamm_fuellartnr' => 'F3001',
                'va_beginn_soll' => '2026-06-11T12:30:00',
                'va_beginn_ist' => null,
                'va_ende_soll' => '2026-06-11T14:30:00',
                'va_ende_ist' => null,
            ]],
        ];
    }
}
