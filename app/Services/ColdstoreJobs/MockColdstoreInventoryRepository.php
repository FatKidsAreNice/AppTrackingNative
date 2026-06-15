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
                'uid' => $this->demoUidForTrack(204, 'UID-L1-A'),
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
                'uid' => $this->demoUidForTrack(101, 'UID-L6-A'),
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

    public function findCurrentContentByUid(string $uid): ?array
    {
        $cabinetContents = [
            $this->demoUidForTrack(101, 'UID-L6-A') => [
                'uid' => $this->demoUidForTrack(101, 'UID-L6-A'),
                'material_pe_text1' => '95106',
                'net_weight_kg' => 123.45,
                'lager_von_id' => 12,
                'lager_von_name' => 'Produktion',
                'lager_nach_id' => 34,
                'lager_nach_name' => 'Kühlhaus',
                'last_booking' => true,
            ],
            $this->demoUidForTrack(204, 'UID-L1-A') => [
                'uid' => $this->demoUidForTrack(204, 'UID-L1-A'),
                'material_pe_text1' => '91200',
                'net_weight_kg' => 98.7,
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
        ];

        return $cabinetContents[trim($uid)] ?? null;
    }

    private function demoUidForTrack(int $trackId, string $fallbackUid): string
    {
        return (string) (config('coldstore.demo_track_barcodes', [])[$trackId] ?? $fallbackUid);
    }

    public function sourceMode(): string
    {
        return 'mock';
    }
}
