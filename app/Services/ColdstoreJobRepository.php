<?php

namespace App\Services;

class ColdstoreJobRepository
{
    /**
     * @return array<int, array{uid: string, destination: string, priority: int}>
     */
    public function all(): array
    {
        return [
            [
                'uid' => '1',
                'destination' => 'Linie 1',
                'priority' => 1,
            ],
            [
                'uid' => '2',
                'destination' => 'Linie 2',
                'priority' => 2,
            ],
            [
                'uid' => '3',
                'destination' => 'Linie 3',
                'priority' => 3,
            ],
            [
                'uid' => '4',
                'destination' => 'Linie 4',
                'priority' => 4,
            ],
        ];
    }
}
