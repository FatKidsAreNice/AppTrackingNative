<?php

namespace App\Services\ColdstoreJobs;

abstract class ProductionOrderRepository
{
    /**
     * @return array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     required_product_name: ?string,
     *     va_menge_kg: ?float,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }|null
     */
    abstract public function nextOpenOrderForWorkplace(int $workplaceNumber): ?array;

    /**
     * @return array<int, array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     required_product_name: ?string,
     *     va_menge_kg: ?float,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }>
     */
    public function openOrdersForWorkplace(int $workplaceNumber, int $limit = 2): array
    {
        $order = $this->nextOpenOrderForWorkplace($workplaceNumber);

        return $order === null ? [] : [$order];
    }

    abstract public function sourceMode(): string;
}
