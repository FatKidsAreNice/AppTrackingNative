<?php

namespace App\Services\ColdstoreJobs;

class MockColdstoreInventoryRepository extends ColdstoreInventoryRepository
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
    public function allCurrent(): array
    {
        return [
            [
                'uid' => 'UID-L1-A',
                'track_id' => 204,
                'etikinterface_id' => 90001,
                'etikinterface_pe_text1' => '91200',
                'fuellartnr' => 'F1200',
                'position' => ['x' => -2.7, 'y' => -3.1],
                'state' => 'in_coldstore',
                'scanned_at' => '2026-06-11T06:55:00',
                'last_seen_at' => '2026-06-11T07:05:00',
            ],
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
            [
                'uid' => 'UID-OTHER-1',
                'track_id' => null,
                'etikinterface_id' => 90099,
                'etikinterface_pe_text1' => '93456',
                'fuellartnr' => 'F3456',
                'position' => null,
                'state' => 'moved_out',
                'scanned_at' => '2026-06-11T05:10:00',
                'last_seen_at' => '2026-06-11T05:20:00',
            ],
        ];
    }

    public function sourceMode(): string
    {
        return 'mock';
    }
}
