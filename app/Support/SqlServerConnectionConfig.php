<?php

namespace App\Support;

class SqlServerConnectionConfig
{
    public static function normalizeEncryptOption(mixed $value, string $default = 'yes'): string
    {
        if ($value === null) {
            return $default;
        }

        if (is_bool($value)) {
            return $value ? 'yes' : 'no';
        }

        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            '1', 'true', 'yes', 'on' => 'yes',
            '0', 'false', 'no', 'off' => 'no',
            'mandatory', 'optional', 'strict' => $normalized,
            default => $default,
        };
    }

    public static function normalizeCharset(?string $value, string $default = 'UTF-8'): string
    {
        $normalized = strtoupper(trim((string) $value));

        if ($normalized === '') {
            return $default;
        }

        return match ($normalized) {
            'UTF8', 'UTF-8' => 'UTF-8',
            default => $value,
        };
    }
}
