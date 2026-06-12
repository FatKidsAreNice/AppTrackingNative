<?php

namespace App\Services\ColdstoreJobs;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDOException;
use stdClass;
use Throwable;

class EtikInterfaceLookupRepository
{
    /**
     * @return array{
     *     uid: string,
     *     etikinterface_id: ?int,
     *     etikinterface_pe_text1: ?string
     * }|null
     */
    public function lookupByUid(string $uid): ?array
    {
        return null;
    }

    public function productNameForRequiredPeText1(string $requiredPeText1): ?string
    {
        $trimmedRequiredPeText1 = trim($requiredPeText1);

        if ($trimmedRequiredPeText1 === '') {
            return null;
        }

        try {
            $row = $this->fetchProductNameRow($trimmedRequiredPeText1);
        } catch (Throwable $throwable) {
            if ($throwable instanceof QueryException || $throwable instanceof PDOException) {
                return null;
            }

            throw $throwable;
        }

        if (! $row instanceof stdClass) {
            return null;
        }

        $productName = trim((string) ($row->MatStamm_MaktX ?? ''));

        return $productName === '' ? null : $productName;
    }

    public function productNameLookupSql(): string
    {
        return <<<'SQL'
SELECT TOP (1)
    m.MatStamm_MaktX
FROM MatStamm m
WHERE CASE
        WHEN LEFT(LTRIM(RTRIM(m.MatStamm_FuellArtNr)), 1) = 'F'
            THEN '9' + SUBSTRING(LTRIM(RTRIM(m.MatStamm_FuellArtNr)), 2, LEN(LTRIM(RTRIM(m.MatStamm_FuellArtNr))) - 1)
        ELSE LTRIM(RTRIM(m.MatStamm_FuellArtNr))
    END = ?
ORDER BY
    m.MatStamm_MatNr ASC
SQL;
    }

    protected function connection(): ConnectionInterface
    {
        return DB::connection((string) config('coldstore.jobs.production_orders.sqlsrv_connection', 'coldstore_sqlsrv'));
    }

    protected function fetchProductNameRow(string $requiredPeText1): mixed
    {
        return $this->connection()->selectOne($this->productNameLookupSql(), [$requiredPeText1]);
    }
}
