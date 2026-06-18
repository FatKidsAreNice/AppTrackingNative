<?php

namespace App\Services\ColdstoreJobs;

class KskCsvLookup
{
    public function __construct(private ?string $csvPath = null) {}

    /**
     * @var array<string, float>|null
     */
    private ?array $cachedLookup = null;

    public function percentForRequiredPeText1(string $requiredPeText1): ?float
    {
        $normalizedMaterialNumber = trim($requiredPeText1);

        if ($normalizedMaterialNumber === '') {
            return null;
        }

        return $this->loadLookup()[$normalizedMaterialNumber] ?? null;
    }

    /**
     * @return array<string, float>
     */
    private function loadLookup(): array
    {
        if ($this->cachedLookup !== null) {
            return $this->cachedLookup;
        }

        $csvPath = trim((string) ($this->csvPath ?? config('coldstore.jobs.ksk_csv_path', '')));

        if ($csvPath === '' || ! is_file($csvPath) || ! is_readable($csvPath)) {
            return $this->cachedLookup = [];
        }

        $handle = fopen($csvPath, 'rb');

        if ($handle === false) {
            return $this->cachedLookup = [];
        }

        $delimiter = $this->detectDelimiter($csvPath);
        $lookup = [];
        $articleColumnIndex = 1;
        $percentColumnIndex = 2;
        $headerWasDetected = false;

        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (! is_array($row) || $row === []) {
                    continue;
                }

                if (! $headerWasDetected) {
                    $detectedArticleColumnIndex = $this->detectArticleColumnIndex($row);
                    $detectedPercentColumnIndex = $this->detectPercentColumnIndex($row);

                    if ($detectedArticleColumnIndex !== null && $detectedPercentColumnIndex !== null) {
                        $articleColumnIndex = $detectedArticleColumnIndex;
                        $percentColumnIndex = $detectedPercentColumnIndex;
                        $headerWasDetected = true;

                        continue;
                    }

                    $headerWasDetected = true;
                }

                $materialNumber = trim((string) ($row[$articleColumnIndex] ?? ''));
                $percent = $this->parsePercent($row[$percentColumnIndex] ?? null);

                if ($materialNumber === '' || $percent === null) {
                    continue;
                }

                $lookup[$materialNumber] = $percent;
            }
        } finally {
            fclose($handle);
        }

        return $this->cachedLookup = $lookup;
    }

    private function detectDelimiter(string $csvPath): string
    {
        $handle = fopen($csvPath, 'rb');

        if ($handle === false) {
            return ';';
        }

        try {
            while (($line = fgets($handle)) !== false) {
                $trimmedLine = trim($line);

                if ($trimmedLine === '') {
                    continue;
                }

                $delimiters = [';', ',', "\t"];
                $selectedDelimiter = ';';
                $highestCount = -1;

                foreach ($delimiters as $delimiter) {
                    $count = substr_count($trimmedLine, $delimiter);

                    if ($count > $highestCount) {
                        $highestCount = $count;
                        $selectedDelimiter = $delimiter;
                    }
                }

                return $selectedDelimiter;
            }
        } finally {
            fclose($handle);
        }

        return ';';
    }

    private function detectArticleColumnIndex(array $row): ?int
    {
        foreach ($row as $index => $value) {
            $normalizedHeader = $this->normalizeHeader((string) $value);

            if ($normalizedHeader === '') {
                continue;
            }

            if (
                str_contains($normalizedHeader, 'artnr')
                || str_contains($normalizedHeader, 'artikelnr')
                || str_contains($normalizedHeader, 'materialnr')
            ) {
                return $index;
            }
        }

        return null;
    }

    private function detectPercentColumnIndex(array $row): ?int
    {
        foreach ($row as $index => $value) {
            $normalizedHeader = $this->normalizeHeader((string) $value);

            if ($normalizedHeader === '') {
                continue;
            }

            if (str_contains($normalizedHeader, 'ksk')) {
                return $index;
            }
        }

        return null;
    }

    private function normalizeHeader(string $value): string
    {
        $lowercaseValue = strtolower(trim($value));

        return preg_replace('/[^a-z0-9]+/', '', $lowercaseValue) ?? '';
    }

    private function parsePercent(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $normalizedValue = trim((string) $value);

        if ($normalizedValue === '') {
            return null;
        }

        $normalizedValue = str_replace(["\xc2\xa0", ' '], '', $normalizedValue);
        $normalizedValue = str_replace('%', '', $normalizedValue);
        $normalizedValue = str_replace(',', '.', $normalizedValue);

        if (! is_numeric($normalizedValue)) {
            return null;
        }

        return (float) $normalizedValue;
    }
}
