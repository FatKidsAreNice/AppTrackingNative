<?php

namespace App\Services\ColdstoreJobs;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use stdClass;
use Throwable;

class SqlServerProductionOrderRepository extends ProductionOrderRepository
{
    private bool $usedFallback = false;

    public function __construct(private ?string $connectionName = null) {}

    public static function driverAvailable(): bool
    {
        return extension_loaded('pdo_sqlsrv')
            || extension_loaded('sqlsrv')
            || in_array('sqlsrv', PDO::getAvailableDrivers(), true);
    }

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
        try {
            $rows = $this->fetchRows($workplaceNumber, $limit);
        } catch (Throwable $throwable) {
            if (! $this->shouldFallbackToMock($throwable)) {
                throw $throwable;
            }

            $this->usedFallback = true;

            return $this->fallbackRepository()->openOrdersForWorkplace($workplaceNumber, $limit);
        }

        return collect($rows)
            ->filter(fn (mixed $row): bool => $row instanceof stdClass)
            ->take($limit)
            ->map(fn (stdClass $row): array => $this->mapRow($row))
            ->values()
            ->all();
    }

    public function sourceMode(): string
    {
        return $this->usedFallback ? 'mock' : 'sqlsrv';
    }

    public function sql(): string
    {
        return <<<'SQL'
SELECT TOP (2)
    v.VA_ID,
    v.VA_Auftragsnr,
    v.VA_Status,
    v.MatStamm_MatNr,
    order_m.MatStamm_MaktX AS MatStamm_MaktX,
    v.VA_Mengekg,
    v.Arbeitsplatz_Nr,
    v.VA_BeginnSoll,
    v.VA_BeginnIst,
    v.VA_EndeSoll,
    v.VA_EndeIst,
    order_m.MatStamm_FuellArtNr,
    required_key.Required_PEText1,
    required_lookup.Required_Product_Name
FROM VA v
INNER JOIN MatStamm order_m
    ON order_m.MatStamm_MatNr = v.MatStamm_MatNr
OUTER APPLY (
    SELECT
        CASE
            WHEN LEFT(LTRIM(RTRIM(order_m.MatStamm_FuellArtNr)), 1) = 'F'
                THEN '9' + SUBSTRING(LTRIM(RTRIM(order_m.MatStamm_FuellArtNr)), 2, LEN(LTRIM(RTRIM(order_m.MatStamm_FuellArtNr))) - 1)
            ELSE LTRIM(RTRIM(order_m.MatStamm_FuellArtNr))
        END AS Required_PEText1
) required_key
OUTER APPLY (
    SELECT TOP (1)
        required_m.MatStamm_MaktX AS Required_Product_Name
    FROM MatStamm required_m
    WHERE LTRIM(RTRIM(CAST(required_m.MatStamm_MatNr AS varchar(50)))) = required_key.Required_PEText1
    ORDER BY
        required_m.MatStamm_MatNr ASC
) required_lookup
WHERE v.VA_Status = 2
  AND v.Arbeitsplatz_Nr = ?
ORDER BY
    CASE WHEN v.VA_BeginnSoll IS NULL THEN 1 ELSE 0 END,
    v.VA_BeginnSoll ASC,
    v.VA_ID ASC
SQL;
    }

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
     * }
     */
    public function mapRow(stdClass $row): array
    {
        return [
            'va_id' => (int) $row->VA_ID,
            'va_auftragsnr' => trim((string) $row->VA_Auftragsnr),
            'va_status' => (int) $row->VA_Status,
            'matstamm_matnr' => trim((string) $row->MatStamm_MatNr),
            'matstamm_maktx' => trim((string) $row->MatStamm_MaktX),
            'matstamm_fuellartnr' => trim((string) $row->MatStamm_FuellArtNr),
            'required_product_name' => $this->nullableTrim($row->Required_Product_Name ?? null),
            'va_menge_kg' => isset($row->VA_Mengekg) ? (float) $row->VA_Mengekg : null,
            'va_beginn_soll' => trim((string) $row->VA_BeginnSoll),
            'va_beginn_ist' => $this->nullableTrim($row->VA_BeginnIst ?? null),
            'va_ende_soll' => $this->nullableTrim($row->VA_EndeSoll ?? null),
            'va_ende_ist' => $this->nullableTrim($row->VA_EndeIst ?? null),
        ];
    }

    public function requiredPeText1FromFuellArtNr(string $matstammFuellArtNr): string
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

    protected function connection(): ConnectionInterface
    {
        return DB::connection($this->connectionName ?? (string) config('coldstore.jobs.production_orders.sqlsrv_connection', 'coldstore_sqlsrv'));
    }

    protected function fallbackRepository(): ProductionOrderRepository
    {
        return app(MockProductionOrderRepository::class);
    }

    /**
     * @return array<int, stdClass>
     */
    protected function fetchRows(int $workplaceNumber, int $limit = 2): array
    {
        return $this->connection()->select($this->sql(), [$workplaceNumber]);
    }

    private function shouldFallbackToMock(Throwable $throwable): bool
    {
        return $throwable instanceof QueryException
            || $throwable instanceof PDOException;
    }

    private function nullableTrim(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim((string) $value);
    }
}
