<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ColdstoreApiService
{
    public function fetchOverview(): array
    {
        if (! $this->hasRemoteBaseUrl()) {
            return $this->normalizeOverview($this->sampleOverview(), 'demo');
        }

        try {
            return $this->normalizeOverview(
                $this->requestJson('GET', (string) config('coldstore.remote.overview_path')),
                'remote',
            );
        } catch (Throwable $throwable) {
            if (! config('coldstore.demo_fallback')) {
                throw new RuntimeException('Die Coldstore-Overview konnte vom anderen PC nicht geladen werden.', 0, $throwable);
            }

            $overview = $this->normalizeOverview($this->sampleOverview(), 'demo');
            $overview['meta']['remote_error'] = $throwable->getMessage();
            $overview['overview']['status_text'] = 'Remote-API nicht erreichbar, Demo-Daten aktiv.';

            return $overview;
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function forwardBarcode(array $payload): array
    {
        if (! $this->hasRemoteBaseUrl()) {
            throw new RuntimeException('Kein Remote-Endpunkt für Barcode-POST konfiguriert.');
        }

        $scan = [
            'barcode_id' => trim((string) $payload['barcode_id']),
            'scanner_id' => (string) $payload['scanner_id'],
            'direction' => (string) $payload['direction'],
            'scanned_at' => Carbon::parse($payload['scanned_at'] ?? now())->toIso8601String(),
            'source' => 'nativephp-mobile',
        ];

        try {
            $response = $this->requestJson('POST', (string) config('coldstore.remote.barcode_path'), $scan);
        } catch (Throwable $throwable) {
            throw new RuntimeException('Der Barcode konnte nicht an den anderen PC gesendet werden.', 0, $throwable);
        }

        return [
            'message' => 'Barcode erfolgreich an den anderen PC gesendet.',
            'scan' => $scan,
            'remote_response' => $response,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{status: int, body: array<string, mixed>}
     */
    public function assignTrackMarriage(array $payload): array
    {
        if (! $this->hasRemoteBaseUrl()) {
            throw new RuntimeException('Kein Remote-Endpunkt fuer Track-Assignment konfiguriert.');
        }

        $assignmentPayload = [
            'track_id' => (int) $payload['track_id'],
            'uid' => trim((string) $payload['uid']),
            'mode' => 'manual_overview_assignment',
        ];

        try {
            $response = $this->client()->post(
                '/'.ltrim((string) config('coldstore.remote.assignment_path'), '/'),
                $assignmentPayload,
            );
        } catch (Throwable $throwable) {
            throw new RuntimeException('Die UID-Zuordnung konnte nicht an den anderen PC gesendet werden.', 0, $throwable);
        }

        $decoded = $this->decodeRemoteJsonResponse($response);

        return [
            'status' => $response->status(),
            'body' => $decoded,
        ];
    }

    private function hasRemoteBaseUrl(): bool
    {
        return filled(config('coldstore.remote.base_url'));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function normalizeOverview(array $payload, string $sourceMode): array
    {
        $lookupMode = (string) ($payload['lookup_mode'] ?? data_get($payload, 'meta.lookup_mode', 'track_id'));
        $trackStamp = (float) ($payload['stamp_sec'] ?? 0);
        $bevStamp = (float) ($payload['bev_stamp_sec'] ?? 0);
        $syncDelta = $trackStamp > 0 && $bevStamp > 0 ? abs($trackStamp - $bevStamp) : null;

        $tracks = collect($payload['tracks'] ?? [])
            ->filter(fn (mixed $track): bool => is_array($track))
            ->map(fn (array $track): array => $this->normalizeTrack($track, $lookupMode))
            ->sortBy('track_id')
            ->values()
            ->all();

        $highlightedRacks = collect($payload['highlighted_racks'] ?? [])
            ->filter(fn (mixed $rack): bool => is_array($rack))
            ->map(fn (array $rack): array => $this->normalizeRack($rack))
            ->values()
            ->all();

        $selectedTrackId = (int) ($payload['selected_track_id'] ?? 0);

        if ($selectedTrackId <= 0 && count($tracks) > 0) {
            $selectedTrackId = (int) $tracks[0]['track_id'];
        }

        $highlightedTrackIds = collect($highlightedRacks)
            ->pluck('track_id')
            ->filter(fn (mixed $trackId): bool => is_numeric($trackId) && (int) $trackId > 0)
            ->map(fn (mixed $trackId): int => (int) $trackId)
            ->all();

        $tracks = array_map(function (array $track) use ($selectedTrackId, $highlightedTrackIds): array {
            $track['selected'] = $track['track_id'] === $selectedTrackId;
            $track['highlighted'] = in_array($track['track_id'], $highlightedTrackIds, true) || $track['selected'];

            return $track;
        }, $tracks);

        $backgroundUrl = $payload['overview_image_url']
            ?? $payload['bev_image_url']
            ?? null;

        $backgroundBase64 = $payload['overview_image_base64']
            ?? $payload['bev_image_base64']
            ?? null;

        return [
            'meta' => [
                'source_mode' => $sourceMode,
                'updated_at' => now()->toIso8601String(),
                'remote_label' => $this->remoteLabel(),
                'lookup_mode' => $lookupMode,
                'frame_id' => (string) ($payload['frame_id'] ?? 'coldstore-map'),
                'track_stamp_sec' => $trackStamp,
                'remote_error' => data_get($payload, 'meta.remote_error'),
            ],
            'overview' => [
                'title' => (string) ($payload['overview']['title'] ?? 'Coldstore Overview'),
                'subtitle' => (string) ($payload['overview']['subtitle'] ?? 'Live-Tracking und BEV-Sync'),
                'status_text' => (string) ($payload['overview']['status_text'] ?? $this->statusText($sourceMode, $syncDelta)),
                'track_count' => count($tracks),
                'moving_count' => collect($tracks)->where('motion_state', 'moving')->count(),
                'highlighted_rack_count' => count($highlightedRacks),
                'selected_track_id' => $selectedTrackId,
                'sync_delta_sec' => $syncDelta,
                'sync_state' => $syncDelta === null ? 'unknown' : ($syncDelta > 0.75 ? 'stale' : 'ok'),
            ],
            'map' => [
                'roi_min' => array_values($payload['map_roi_min'] ?? [-14.5, -15.0]),
                'roi_max' => array_values($payload['map_roi_max'] ?? [9.0, 6.0]),
                'rotation_deg' => (float) ($payload['map_rotation_deg'] ?? 0),
                'background_url' => $backgroundUrl,
                'background_base64' => $backgroundBase64,
                'show_background' => filled($backgroundUrl) || filled($backgroundBase64),
                'overlay_max_time_delta_sec' => (float) ($payload['bev_overlay_max_time_delta_sec'] ?? 0.75),
            ],
            'tracks' => $tracks,
            'highlighted_racks' => $highlightedRacks,
            'coldstore' => [
                'name' => (string) data_get($payload, 'coldstore.name', 'Kühlhaus Nord'),
                'summary' => (string) data_get($payload, 'coldstore.summary', 'Live-Overview vom anderen PC'),
                'sections' => $this->normalizeSections($payload['coldstore']['sections'] ?? [], $highlightedRacks),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $track
     * @return array<string, mixed>
     */
    private function normalizeTrack(array $track, string $lookupMode): array
    {
        $trackId = (int) ($track['track_id'] ?? 0);
        $barcodeId = trim((string) ($track['barcode_id'] ?? ''));
        $lastSeenAgeSec = isset($track['last_seen_age_sec'])
            ? (float) $track['last_seen_age_sec']
            : max(0, (float) ($track['stamp_sec'] ?? 0) - (float) ($track['last_stamp_sec'] ?? 0));
        $eligibilityBlockers = collect($track['eligibility_blockers'] ?? [])
            ->filter(fn (mixed $blocker): bool => is_string($blocker) && trim($blocker) !== '')
            ->values()
            ->all();

        return [
            'track_id' => $trackId,
            'display_id' => $lookupMode === 'barcode_id' && $barcodeId !== '' ? $barcodeId : 'T'.$trackId,
            'barcode_id' => $barcodeId,
            'class_name' => (string) ($track['class_name'] ?? $track['class_label'] ?? '-'),
            'class_label' => (string) ($track['class_label'] ?? $track['class_name'] ?? '-'),
            'state' => (string) ($track['state'] ?? '-'),
            'motion_state' => (string) ($track['motion_state'] ?? '-'),
            'identity_state' => (string) ($track['identity_state'] ?? 'unknown'),
            'identity_confidence' => isset($track['identity_confidence']) ? (float) $track['identity_confidence'] : null,
            'marriage_state' => (string) ($track['marriage_state'] ?? 'unknown'),
            'is_marriage_eligible' => (bool) ($track['is_marriage_eligible'] ?? false),
            'eligibility_reason' => (string) ($track['eligibility_reason'] ?? 'unknown'),
            'eligibility_blockers' => $eligibilityBlockers,
            'last_seen_age_sec' => $lastSeenAgeSec,
            'zone_label' => trim((string) ($track['zone_label'] ?? 'Unbekannte Zone')),
            'position_label' => trim((string) ($track['position_label'] ?? sprintf('x=%.2f y=%.2f', (float) ($track['x'] ?? 0), (float) ($track['y'] ?? 0)))),
            'source_track_id' => isset($track['source_track_id']) ? (int) $track['source_track_id'] : null,
            'confidence' => round((float) ($track['confidence'] ?? 0), 3),
            'x' => (float) ($track['x'] ?? 0),
            'y' => (float) ($track['y'] ?? 0),
            'z' => (float) ($track['z'] ?? 0),
            'yaw' => (float) ($track['yaw'] ?? 0),
            'length' => (float) ($track['length'] ?? 0),
            'width' => (float) ($track['width'] ?? 0),
            'height' => (float) ($track['height'] ?? 0),
            'vx' => (float) ($track['vx'] ?? 0),
            'vy' => (float) ($track['vy'] ?? 0),
            'vz' => (float) ($track['vz'] ?? 0),
            'age' => (int) ($track['age'] ?? 0),
            'hit_count' => (int) ($track['hit_count'] ?? 0),
            'missed_updates' => (int) ($track['missed_updates'] ?? 0),
            'source_missed_count' => (int) ($track['source_missed_count'] ?? 0),
            'lost_transition_count' => (int) ($track['lost_transition_count'] ?? 0),
            'occluded_transition_count' => (int) ($track['occluded_transition_count'] ?? 0),
            'reappeared_count' => (int) ($track['reappeared_count'] ?? 0),
            'last_seen_sec' => (float) ($track['last_seen_sec'] ?? 0),
            'last_motion_state_change_sec' => (float) ($track['last_motion_state_change_sec'] ?? 0),
            'last_stamp_sec' => (float) ($track['last_stamp_sec'] ?? 0),
            'selected' => false,
            'highlighted' => false,
        ];
    }

    /**
     * @param  array<string, mixed>  $rack
     * @return array<string, mixed>
     */
    private function normalizeRack(array $rack): array
    {
        return [
            'id' => (string) ($rack['id'] ?? Str::uuid()->toString()),
            'label' => (string) ($rack['label'] ?? 'Schrank'),
            'zone' => (string) ($rack['zone'] ?? 'Kühlhaus'),
            'status' => (string) ($rack['status'] ?? 'beobachtet'),
            'x' => (float) ($rack['x'] ?? 0),
            'y' => (float) ($rack['y'] ?? 0),
            'track_id' => isset($rack['track_id']) ? (int) $rack['track_id'] : null,
            'note' => (string) ($rack['note'] ?? ''),
        ];
    }

    /**
     * @param  array<int, mixed>  $sections
     * @param  array<int, array<string, mixed>>  $highlightedRacks
     * @return array<int, array<string, mixed>>
     */
    private function normalizeSections(array $sections, array $highlightedRacks): array
    {
        $normalizedSections = collect($sections)
            ->filter(fn (mixed $section): bool => is_array($section))
            ->map(fn (array $section): array => [
                'name' => (string) ($section['name'] ?? 'Bereich'),
                'occupancy' => (string) ($section['occupancy'] ?? '-'),
                'status' => (string) ($section['status'] ?? 'live'),
                'note' => (string) ($section['note'] ?? ''),
            ])
            ->values();

        if ($normalizedSections->isNotEmpty()) {
            return $normalizedSections->all();
        }

        $derivedSections = collect($highlightedRacks)
            ->groupBy('zone')
            ->map(function (Collection $zoneRacks, string $zone): array {
                return [
                    'name' => $zone,
                    'occupancy' => sprintf('%d markiert', $zoneRacks->count()),
                    'status' => 'live',
                    'note' => $zoneRacks->pluck('label')->join(', '),
                ];
            })
            ->values();

        if ($derivedSections->isNotEmpty()) {
            return $derivedSections->all();
        }

        return [[
            'name' => 'Live-Tracking',
            'occupancy' => 'Keine Bereichsdaten vom KI-PC',
            'status' => 'live',
            'note' => 'Tracks und BEV-Sync laufen bereits, Bereichszuschnitt kann später ergänzt werden.',
        ]];
    }

    private function statusText(string $sourceMode, ?float $syncDelta): string
    {
        $prefix = $sourceMode === 'remote' ? 'Live vom anderen PC' : 'Demo-Daten';

        if ($syncDelta === null) {
            return $prefix.' - warte auf Track/BEV-Sync';
        }

        return sprintf('%s - BEV/Track Delta %.3fs', $prefix, $syncDelta);
    }

    private function remoteLabel(): string
    {
        $baseUrl = (string) config('coldstore.remote.base_url');

        if ($baseUrl === '') {
            return 'Demo';
        }

        return parse_url($baseUrl, PHP_URL_HOST) ?: $baseUrl;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function requestJson(string $method, string $path, array $payload = []): array
    {
        $path = '/'.ltrim($path, '/');

        $response = match (strtoupper($method)) {
            'POST' => $this->client()->post($path, $payload),
            default => $this->client()->get($path),
        };

        $decoded = $response->throw()->json();

        if (! is_array($decoded)) {
            throw new RuntimeException('Die Remote-API hat kein JSON-Objekt geliefert.');
        }

        return $decoded;
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->baseUrl(rtrim((string) config('coldstore.remote.base_url'), '/'))
            ->timeout((int) config('coldstore.remote.timeout_seconds', 4));
    }

    /**
     * @return array<string, mixed>
     */
    private function sampleOverview(): array
    {
        $trackStamp = now()->floatDiffInSeconds(now()->startOfDay()) + 1000;
        $bevStamp = $trackStamp - 0.18;
        $demoTrackBarcodes = config('coldstore.demo_track_barcodes', []);

        return [
            'frame_id' => 'coldstore_bev',
            'stamp_sec' => $trackStamp,
            'bev_stamp_sec' => $bevStamp,
            'lookup_mode' => 'track_id',
            'map_roi_min' => [-14.5, -15.0],
            'map_roi_max' => [9.0, 6.0],
            'map_rotation_deg' => 0,
            'overview_image_url' => null,
            'bev_image_url' => null,
            'tracks' => [
                [
                    'track_id' => 101,
                    'barcode_id' => (string) ($demoTrackBarcodes[101] ?? ''),
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'moving',
                    'identity_state' => 'direct',
                    'identity_confidence' => 1.0,
                    'marriage_state' => 'known_existing',
                    'is_marriage_eligible' => false,
                    'eligibility_reason' => 'known_existing',
                    'eligibility_blockers' => ['known_existing'],
                    'zone_label' => 'Bestand Nord',
                    'position_label' => 'x=-9.20 y=2.40',
                    'source_track_id' => 1001,
                    'confidence' => 0.98,
                    'x' => -9.2,
                    'y' => 2.4,
                    'z' => 0.0,
                    'yaw' => 0.62,
                    'length' => 1.2,
                    'width' => 0.8,
                    'height' => 2.3,
                    'vx' => 0.12,
                    'vy' => 0.04,
                    'vz' => 0.0,
                    'age' => 12,
                    'hit_count' => 12,
                    'missed_updates' => 0,
                    'source_missed_count' => 0,
                    'lost_transition_count' => 0,
                    'occluded_transition_count' => 1,
                    'reappeared_count' => 0,
                    'last_seen_sec' => $trackStamp,
                    'last_motion_state_change_sec' => $trackStamp - 15,
                    'last_stamp_sec' => $trackStamp,
                ],
                [
                    'track_id' => 204,
                    'barcode_id' => (string) ($demoTrackBarcodes[204] ?? ''),
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'static',
                    'identity_state' => 'direct',
                    'identity_confidence' => 1.0,
                    'marriage_state' => 'assigned',
                    'is_marriage_eligible' => false,
                    'eligibility_reason' => 'track_already_assigned',
                    'eligibility_blockers' => ['track_already_assigned'],
                    'zone_label' => 'Reihe 4',
                    'position_label' => 'x=-2.70 y=-3.10',
                    'source_track_id' => 1204,
                    'confidence' => 0.88,
                    'x' => -2.7,
                    'y' => -3.1,
                    'z' => 0.0,
                    'yaw' => 1.25,
                    'length' => 1.4,
                    'width' => 0.9,
                    'height' => 2.2,
                    'vx' => 0.0,
                    'vy' => 0.0,
                    'vz' => 0.0,
                    'age' => 23,
                    'hit_count' => 23,
                    'missed_updates' => 1,
                    'source_missed_count' => 2,
                    'lost_transition_count' => 1,
                    'occluded_transition_count' => 4,
                    'reappeared_count' => 1,
                    'last_seen_sec' => $trackStamp - 1.5,
                    'last_motion_state_change_sec' => $trackStamp - 42,
                    'last_stamp_sec' => $trackStamp - 1.5,
                ],
            ],
            'selected_track_id' => 101,
            'highlighted_racks' => [],
            'coldstore' => [
                'name' => 'Kühlhaus Sauels',
                'summary' => 'Demo-Ansicht bis die Remote-API erreichbar ist.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeRemoteJsonResponse(Response $response): array
    {
        $decoded = $response->json();

        if (! is_array($decoded)) {
            throw new RuntimeException('Die Remote-API hat kein JSON-Objekt geliefert.');
        }

        return $decoded;
    }
}
