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

    abstract public function sourceMode(): string;
}
