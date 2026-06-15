<?php

namespace App\Services\ColdstoreJobs;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use stdClass;
use Throwable;

class SqlServerColdstoreInventoryRepository extends ColdstoreInventoryRepository
{
    private bool $usedFallback = false;

    public function __construct(private ?string $connectionName = null) {}

    public static function driverAvailable(): bool
    {
        return extension_loaded('pdo_sqlsrv')
            || extension_loaded('sqlsrv')
            || in_array('sqlsrv', PDO::getAvailableDrivers(), true);
    }

    public function allCurrent(): array
    {
        $this->usedFallback = true;

        return $this->fallbackRepository()->allCurrent();
    }

    public function findCurrentContentByUid(string $uid): ?array
    {
        try {
            $row = $this->fetchContentRow($uid);
        } catch (Throwable $throwable) {
            if (! $this->shouldFallbackToMock($throwable)) {
                throw $throwable;
            }

            $this->usedFallback = true;

            return $this->fallbackRepository()->findCurrentContentByUid($uid);
        }

        if (! $row instanceof stdClass) {
            return null;
        }

        return $this->mapContentRow($row);
    }

    public function sourceMode(): string
    {
        return $this->usedFallback ? 'mock' : 'sqlsrv';
    }

    public function sql(): string
    {
        return <<<'SQL'
SELECT TOP (1)
    cg.ChargenStueck_UID,
    cg.ChargenGewicht_Netto,
    cg.Lager_ID_Von,
    lager_von.Lager_Name AS Lager_Von_Name,
    cg.Lager_ID_Nach,
    lager_nach.Lager_Name AS Lager_Nach_Name,
    cg.last_booking,
    ei.EtikInterface_PEText1
FROM ChargenGewicht cg
LEFT JOIN Lager lager_von
    ON lager_von.Lager_ID = cg.Lager_ID_Von
LEFT JOIN Lager lager_nach
    ON lager_nach.Lager_ID = cg.Lager_ID_Nach
LEFT JOIN EtikInterface ei
    ON ei.EtikInterface_ID = cg.ChargenStueck_UID
WHERE cg.ChargenStueck_UID = ?
  AND cg.last_booking = 1
ORDER BY
    cg.ChargenStueck_UID ASC
SQL;
    }

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
     * }
     */
    public function mapContentRow(stdClass $row): array
    {
        return [
            'uid' => trim((string) $row->ChargenStueck_UID),
            'material_pe_text1' => trim((string) ($row->EtikInterface_PEText1 ?? '')),
            'net_weight_kg' => isset($row->ChargenGewicht_Netto) ? (float) $row->ChargenGewicht_Netto : null,
            'lager_von_id' => isset($row->Lager_ID_Von) ? (int) $row->Lager_ID_Von : null,
            'lager_von_name' => $this->nullableTrim($row->Lager_Von_Name ?? null),
            'lager_nach_id' => isset($row->Lager_ID_Nach) ? (int) $row->Lager_ID_Nach : null,
            'lager_nach_name' => $this->nullableTrim($row->Lager_Nach_Name ?? null),
            'last_booking' => (bool) ($row->last_booking ?? false),
        ];
    }

    protected function connection(): ConnectionInterface
    {
        return DB::connection($this->connectionName ?? (string) config('coldstore.jobs.inventory.sqlsrv_connection', 'coldstore_sqlsrv'));
    }

    protected function fallbackRepository(): ColdstoreInventoryRepository
    {
        return app(MockColdstoreInventoryRepository::class);
    }

    protected function fetchContentRow(string $uid): mixed
    {
        return $this->connection()->selectOne($this->sql(), [trim($uid)]);
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
