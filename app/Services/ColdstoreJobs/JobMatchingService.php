<?php

namespace App\Services\ColdstoreJobs;

class JobMatchingService
{
    public function __construct(
        private LineWorkplaceMapper $lineWorkplaceMapper,
        private ProductionOrderRepository $productionOrderRepository,
        private ColdstoreInventoryRepository $coldstoreInventoryRepository,
        private EtikInterfaceLookupRepository $etikInterfaceLookupRepository,
    ) {}

    /**
     * @return array{
     *     selected_line: int,
     *     arbeitsplatz_nr: int,
     *     order: array{
     *         va_id: int,
     *         va_auftragsnr: string,
     *         va_status: int,
     *         matstamm_matnr: string,
     *         matstamm_maktx: string,
     *         matstamm_fuellartnr: string,
     *         required_product_name: ?string,
     *         va_menge_kg: ?float,
     *         required_pe_text1: string,
     *         va_beginn_soll: string,
     *         va_beginn_ist: ?string,
     *         va_ende_soll: ?string,
     *         va_ende_ist: ?string
     *     }|null,
     *     next_order: array{
     *         va_id: int,
     *         va_auftragsnr: string,
     *         va_status: int,
     *         matstamm_matnr: string,
     *         matstamm_maktx: string,
     *         matstamm_fuellartnr: string,
     *         required_product_name: ?string,
     *         va_menge_kg: ?float,
     *         required_pe_text1: string,
     *         va_beginn_soll: string,
     *         va_beginn_ist: ?string,
     *         va_ende_soll: ?string,
     *         va_ende_ist: ?string
     *     }|null,
     *     matching_uids: array<int, array{
     *         uid: string,
     *         track_id: ?int,
     *         etikinterface_id: ?int,
     *         etikinterface_pe_text1: string,
     *         fuellartnr: ?string,
     *         position: array{x: float, y: float}|null,
     *         state: string,
     *         has_track_assignment: bool
     *     }>,
     *     next_matching_uids: array<int, array{
     *         uid: string,
     *         track_id: ?int,
     *         etikinterface_id: ?int,
     *         etikinterface_pe_text1: string,
     *         fuellartnr: ?string,
     *         position: array{x: float, y: float}|null,
     *         state: string,
     *         has_track_assignment: bool
     *     }>,
     *     meta: array{
     *         source_mode: string
     *     }
     * }
     */
    public function payloadForLine(int $selectedLine): array
    {
        $workplaceNumber = $this->lineWorkplaceMapper->workplaceNumberForLine($selectedLine);
        $orders = $this->productionOrderRepository->openOrdersForWorkplace($workplaceNumber, 2);
        $order = $orders[0] ?? null;
        $nextOrder = $orders[1] ?? null;

        if ($order === null) {
            return [
                'selected_line' => $selectedLine,
                'arbeitsplatz_nr' => $workplaceNumber,
                'order' => null,
                'next_order' => null,
                'matching_uids' => [],
                'next_matching_uids' => [],
                'meta' => [
                    'source_mode' => $this->sourceMode(),
                ],
            ];
        }

        $normalizedOrder = $this->normalizeOrder($order);
        $normalizedNextOrder = $nextOrder === null ? null : $this->normalizeOrder($nextOrder);

        return [
            'selected_line' => $selectedLine,
            'arbeitsplatz_nr' => $workplaceNumber,
            'order' => $normalizedOrder,
            'next_order' => $normalizedNextOrder,
            'matching_uids' => $this->matchingInventory($normalizedOrder['required_pe_text1']),
            'next_matching_uids' => $normalizedNextOrder === null
                ? []
                : $this->matchingInventory($normalizedNextOrder['required_pe_text1']),
            'meta' => [
                'source_mode' => $this->sourceMode(),
            ],
        ];
    }

    /**
     * @param  array{
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
     * } $order
     * @return array{
     *     va_id: int,
     *     va_auftragsnr: string,
     *     va_status: int,
     *     matstamm_matnr: string,
     *     matstamm_maktx: string,
     *     matstamm_fuellartnr: string,
     *     required_product_name: ?string,
     *     va_menge_kg: ?float,
     *     required_pe_text1: string,
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }
     */
    private function normalizeOrder(array $order): array
    {
        $matstammFuellArtNr = trim((string) $order['matstamm_fuellartnr']);
        $requiredPeText1 = $this->requiredPeText1ForFuellArtNr($matstammFuellArtNr);
        $requiredProductName = filled($order['required_product_name'] ?? null)
            ? trim((string) $order['required_product_name'])
            : $this->etikInterfaceLookupRepository->productNameForRequiredPeText1($requiredPeText1);

        return [
            'va_id' => (int) $order['va_id'],
            'va_auftragsnr' => trim((string) $order['va_auftragsnr']),
            'va_status' => (int) $order['va_status'],
            'matstamm_matnr' => trim((string) $order['matstamm_matnr']),
            'matstamm_maktx' => trim((string) $order['matstamm_maktx']),
            'matstamm_fuellartnr' => $matstammFuellArtNr,
            'required_product_name' => filled($requiredProductName) ? trim((string) $requiredProductName) : null,
            'va_menge_kg' => isset($order['va_menge_kg']) ? (float) $order['va_menge_kg'] : null,
            'required_pe_text1' => $requiredPeText1,
            'va_beginn_soll' => trim((string) $order['va_beginn_soll']),
            'va_beginn_ist' => $order['va_beginn_ist'] ? trim((string) $order['va_beginn_ist']) : null,
            'va_ende_soll' => $order['va_ende_soll'] ? trim((string) $order['va_ende_soll']) : null,
            'va_ende_ist' => $order['va_ende_ist'] ? trim((string) $order['va_ende_ist']) : null,
        ];
    }

    public function requiredPeText1ForFuellArtNr(string $matstammFuellArtNr): string
    {
        $trimmedFuellArtNr = trim((string) $matstammFuellArtNr);

        if ($trimmedFuellArtNr === '') {
            return '';
        }

        if (str_starts_with($trimmedFuellArtNr, 'F')) {
            return '9'.substr($trimmedFuellArtNr, 1);
        }

        return $trimmedFuellArtNr;
    }

    /**
     * @return array<int, array{
     *     uid: string,
     *     track_id: ?int,
     *     etikinterface_id: ?int,
     *     etikinterface_pe_text1: string,
     *     fuellartnr: ?string,
     *     position: array{x: float, y: float}|null,
     *     state: string,
     *     has_track_assignment: bool
     * }>
     */
    private function matchingInventory(string $requiredPeText1): array
    {
        $trimmedRequiredPeText1 = trim((string) $requiredPeText1);

        return collect($this->coldstoreInventoryRepository->allCurrent())
            ->filter(function (array $inventoryItem) use ($trimmedRequiredPeText1): bool {
                return trim((string) $inventoryItem['etikinterface_pe_text1']) === $trimmedRequiredPeText1;
            })
            ->map(function (array $inventoryItem): array {
                return [
                    'uid' => trim((string) $inventoryItem['uid']),
                    'track_id' => isset($inventoryItem['track_id']) ? (int) $inventoryItem['track_id'] : null,
                    'etikinterface_id' => isset($inventoryItem['etikinterface_id']) ? (int) $inventoryItem['etikinterface_id'] : null,
                    'etikinterface_pe_text1' => trim((string) $inventoryItem['etikinterface_pe_text1']),
                    'fuellartnr' => filled($inventoryItem['fuellartnr'] ?? null) ? trim((string) $inventoryItem['fuellartnr']) : null,
                    'position' => is_array($inventoryItem['position'] ?? null)
                        ? [
                            'x' => (float) $inventoryItem['position']['x'],
                            'y' => (float) $inventoryItem['position']['y'],
                        ]
                        : null,
                    'state' => trim((string) $inventoryItem['state']),
                    'has_track_assignment' => isset($inventoryItem['track_id']) && $inventoryItem['track_id'] !== null,
                ];
            })
            ->values()
            ->all();
    }

    private function sourceMode(): string
    {
        $productionSource = $this->productionOrderRepository->sourceMode();
        $inventorySource = $this->coldstoreInventoryRepository->sourceMode();

        return $productionSource === $inventorySource
            ? $productionSource
            : $productionSource.'+'.$inventorySource;
    }
}
