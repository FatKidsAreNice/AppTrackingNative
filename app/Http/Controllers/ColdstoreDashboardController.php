<?php

namespace App\Http\Controllers;

use App\Services\ColdstoreApiService;
use App\Services\ColdstoreJobRepository;
use App\Services\ColdstoreJobs\JobMatchingService;
use App\Services\ColdstoreJobs\LineWorkplaceMapper;
use Composer\InstalledVersions;
use Illuminate\Contracts\View\View;

class ColdstoreDashboardController extends Controller
{
    public function __construct(
        private ColdstoreApiService $coldstoreApiService,
        private ColdstoreJobRepository $coldstoreJobRepository,
        private JobMatchingService $jobMatchingService,
        private LineWorkplaceMapper $lineWorkplaceMapper,
    ) {}

    public function index(): View
    {
        $defaultLine = $this->lineWorkplaceMapper->defaultLine();

        return view('coldstore.dashboard', [
            'initialOverview' => $this->coldstoreApiService->fetchOverview(),
            'jobs' => $this->coldstoreJobRepository->all(),
            'initialJobs' => $this->jobMatchingService->payloadForLine($defaultLine),
            'jobLines' => $this->lineWorkplaceMapper->all(),
            'jobsEndpoint' => route('api.coldstore.jobs', absolute: false),
            'pollIntervalMs' => config('coldstore.poll_interval_seconds') * 1000,
        ]);
    }

    public function scanner(): View
    {
        return view('coldstore.scanner', [
            'barcodeEndpoint' => route('api.coldstore.barcodes.store', absolute: false),
            'cameraPluginInstalled' => InstalledVersions::isInstalled('nativephp/mobile-camera'),
            'remoteConfigured' => filled(config('coldstore.remote.base_url')),
            'scannerId' => config('coldstore.scanner.id'),
            'scanDirection' => config('coldstore.scanner.direction'),
        ]);
    }

    public function settings(): View
    {
        return view('coldstore.settings', [
            'overviewEndpoint' => route('api.coldstore.overview', absolute: false),
            'barcodeEndpoint' => route('api.coldstore.barcodes.store', absolute: false),
            'remoteBaseUrl' => config('coldstore.remote.base_url'),
            'remoteOverviewPath' => config('coldstore.remote.overview_path'),
            'remoteBarcodePath' => config('coldstore.remote.barcode_path'),
            'pollIntervalSeconds' => config('coldstore.poll_interval_seconds'),
            'scannerId' => config('coldstore.scanner.id'),
            'scanDirection' => config('coldstore.scanner.direction'),
            'cameraPluginInstalled' => InstalledVersions::isInstalled('nativephp/mobile-camera'),
        ]);
    }
}
