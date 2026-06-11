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
     *     va_beginn_soll: string,
     *     va_beginn_ist: ?string,
     *     va_ende_soll: ?string,
     *     va_ende_ist: ?string
     * }|null
     */
    public function nextOpenOrderForWorkplace(int $workplaceNumber): ?array
    {
        try {
            $row = $this->fetchRow($workplaceNumber);
        } catch (Throwable $throwable) {
            if (! $this->shouldFallbackToMock($throwable)) {
                throw $throwable;
            }

            $this->usedFallback = true;

            return $this->fallbackRepository()->nextOpenOrderForWorkplace($workplaceNumber);
        }

        if (! $row instanceof stdClass) {
            return null;
        }

        return $this->mapRow($row);
    }

    public function sourceMode(): string
    {
        return $this->usedFallback ? 'mock' : 'sqlsrv';
    }

    public function sql(): string
    {
        return <<<'SQL'
SELECT TOP (1)
    v.VA_ID,
    v.VA_Auftragsnr,
    v.VA_Status,
    v.MatStamm_MatNr,
    v.MatStamm_MaktX,
    v.Arbeitsplatz_Nr,
    v.VA_BeginnSoll,
    v.VA_BeginnIst,
    v.VA_EndeSoll,
    v.VA_EndeIst,
    m.MatStamm_FuellArtNr,
    CASE
        WHEN LEFT(LTRIM(RTRIM(m.MatStamm_FuellArtNr)), 1) = 'F'
            THEN '9' + SUBSTRING(LTRIM(RTRIM(m.MatStamm_FuellArtNr)), 2, LEN(LTRIM(RTRIM(m.MatStamm_FuellArtNr))) - 1)
        ELSE LTRIM(RTRIM(m.MatStamm_FuellArtNr))
    END AS Required_PEText1
FROM VA v
INNER JOIN MatStamm m
    ON m.MatStamm_MatNr = v.MatStamm_MatNr
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

    protected function fetchRow(int $workplaceNumber): mixed
    {
        return $this->connection()->selectOne($this->sql(), [$workplaceNumber]);
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
