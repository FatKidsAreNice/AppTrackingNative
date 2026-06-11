<?php

return [
    'poll_interval_seconds' => (int) env('COLDSTORE_POLL_INTERVAL_SECONDS', 5),
    'demo_fallback' => (bool) env('COLDSTORE_DEMO_FALLBACK', true),
    'scanner' => [
        'id' => env('COLDSTORE_SCANNER_ID', 'coldstore-entry-01'),
        'direction' => env('COLDSTORE_SCAN_DIRECTION', 'entry'),
    ],
    'remote' => [
        'base_url' => env('COLDSTORE_REMOTE_BASE_URL', 'http://10.10.121.30:8000'),
        'overview_path' => env('COLDSTORE_REMOTE_OVERVIEW_PATH', '/overview'),
        'barcode_path' => env('COLDSTORE_REMOTE_BARCODE_PATH', '/barcode-scan'),
        'timeout_seconds' => (int) env('COLDSTORE_REMOTE_TIMEOUT_SECONDS', 4),
    ],
    'jobs' => [
        'default_selected_line' => (int) env('COLDSTORE_JOBS_DEFAULT_LINE', 6),
        'production_orders' => [
            'driver' => env('COLDSTORE_JOBS_PRODUCTION_ORDER_DRIVER', 'mock'),
            'sqlsrv_connection' => env('COLDSTORE_JOBS_SQLSRV_CONNECTION', 'sqlsrv'),
        ],
        'inventory' => [
            'driver' => env('COLDSTORE_JOBS_INVENTORY_DRIVER', 'mock'),
        ],
    ],
];
