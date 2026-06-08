<?php

namespace App\Http\Controllers;

use App\Services\ColdstoreApiService;
use Composer\InstalledVersions;
use Illuminate\Contracts\View\View;

class ColdstoreDashboardController extends Controller
{
    public function __construct(private ColdstoreApiService $coldstoreApiService) {}

    public function index(): View
    {
        return view('coldstore.dashboard', [
            'initialOverview' => $this->coldstoreApiService->fetchOverview(),
            'pollIntervalMs' => config('coldstore.poll_interval_seconds') * 1000,
        ]);
    }

    public function scanner(): View
    {
        return view('coldstore.scanner', [
            'barcodeEndpoint' => route('api.coldstore.barcodes.store'),
            'scannerPluginInstalled' => InstalledVersions::isInstalled('nativephp/mobile-scanner'),
            'remoteConfigured' => filled(config('coldstore.remote.base_url')),
            'scannerId' => config('coldstore.scanner.id'),
            'scanDirection' => config('coldstore.scanner.direction'),
        ]);
    }

    public function settings(): View
    {
        return view('coldstore.settings', [
            'overviewEndpoint' => route('api.coldstore.overview'),
            'barcodeEndpoint' => route('api.coldstore.barcodes.store'),
            'remoteBaseUrl' => config('coldstore.remote.base_url'),
            'remoteOverviewPath' => config('coldstore.remote.overview_path'),
            'remoteBarcodePath' => config('coldstore.remote.barcode_path'),
            'pollIntervalSeconds' => config('coldstore.poll_interval_seconds'),
            'scannerId' => config('coldstore.scanner.id'),
            'scanDirection' => config('coldstore.scanner.direction'),
            'scannerPluginInstalled' => InstalledVersions::isInstalled('nativephp/mobile-scanner'),
        ]);
    }
}
