<?php

namespace App\Services\ColdstoreJobs;

class LineWorkplaceMapper
{
    /**
     * @return array<int, array{line: int, label: string, workplace_number: int}>
     */
    public function all(): array
    {
        return collect($this->mapping())
            ->map(fn (int $workplaceNumber, int $line): array => [
                'line' => $line,
                'label' => 'Linie '.$line,
                'workplace_number' => $workplaceNumber,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int>
     */
    public function lines(): array
    {
        return array_keys($this->mapping());
    }

    public function defaultLine(): int
    {
        $configuredLine = (int) config('coldstore.jobs.default_selected_line', 6);

        if ($this->supportsLine($configuredLine)) {
            return $configuredLine;
        }

        return $this->lines()[0];
    }

    public function supportsLine(int $line): bool
    {
        return array_key_exists($line, $this->mapping());
    }

    public function workplaceNumberForLine(int $line): int
    {
        return $this->mapping()[$line];
    }

    public function labelForLine(int $line): string
    {
        return 'Linie '.$line;
    }

    /**
     * @return array<int, int>
     */
    private function mapping(): array
    {
        return [
            1 => 3501,
            2 => 3502,
            3 => 3503,
            4 => 3504,
            5 => 3505,
            6 => 3506,
            7 => 3507,
            8 => 3508,
            9 => 3509,
            10 => 3510,
            20 => 3520,
            30 => 3530,
        ];
    }
}
