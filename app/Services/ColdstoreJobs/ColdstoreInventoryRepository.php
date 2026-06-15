<?php

namespace App\Services\ColdstoreJobs;

abstract class ColdstoreInventoryRepository
{
    /**
     * @return array<int, array{
     *     uid: string,
     *     track_id: ?int,
     *     etikinterface_id: ?int,
     *     etikinterface_pe_text1: string,
     *     fuellartnr: ?string,
     *     position: array{x: float, y: float}|null,
     *     state: string,
     *     scanned_at: string,
     *     last_seen_at: string
     * }>
     */
    abstract public function allCurrent(): array;

    /**
     * @return array{
     *     uid: string,
     *     material_pe_text1: string,
     *     net_weight_kg: ?float,
     *     lager_von_id: ?int,
     *     lager_von_name: ?string,
     *     lager_nach_id: ?int,
     *     lager_nach_name: ?string,
     *     last_booking: bool
     * }|null
     */
    abstract public function findCurrentContentByUid(string $uid): ?array;

    abstract public function sourceMode(): string;
}
