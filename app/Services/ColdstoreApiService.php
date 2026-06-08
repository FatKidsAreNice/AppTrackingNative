<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
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

        return [
            'meta' => [
                'source_mode' => $sourceMode,
                'updated_at' => now()->toIso8601String(),
                'remote_label' => $this->remoteLabel(),
                'lookup_mode' => $lookupMode,
                'frame_id' => (string) ($payload['frame_id'] ?? 'coldstore-map'),
                'remote_error' => data_get($payload, 'meta.remote_error'),
            ],
            'overview' => [
                'title' => (string) ($payload['overview']['title'] ?? 'Coldstore Track Overview'),
                'subtitle' => (string) ($payload['overview']['subtitle'] ?? 'Kühlhaus-Overview mit Live-Tracks und BEV-Sync'),
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
                'background_url' => $payload['bev_image_url'] ?? null,
                'background_base64' => $payload['bev_image_base64'] ?? null,
                'show_background' => filled($payload['bev_image_url'] ?? null) || filled($payload['bev_image_base64'] ?? null),
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

        return [
            'track_id' => $trackId,
            'display_id' => $lookupMode === 'barcode_id' && $barcodeId !== '' ? $barcodeId : 'T'.$trackId,
            'barcode_id' => $barcodeId,
            'class_name' => (string) ($track['class_name'] ?? '-'),
            'state' => (string) ($track['state'] ?? '-'),
            'motion_state' => (string) ($track['motion_state'] ?? '-'),
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

        return [
            'frame_id' => 'coldstore_bev',
            'stamp_sec' => $trackStamp,
            'bev_stamp_sec' => $bevStamp,
            'lookup_mode' => 'track_id',
            'map_roi_min' => [-14.5, -15.0],
            'map_roi_max' => [9.0, 6.0],
            'bev_image_url' => null,
            'tracks' => [
                [
                    'track_id' => 101,
                    'barcode_id' => '',
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'moving',
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
                    'barcode_id' => '',
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'static',
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
}
