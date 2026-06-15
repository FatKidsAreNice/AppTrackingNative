<?php

namespace App\Http\Controllers;

use App\Services\ColdstoreApiService;
use App\Services\ColdstoreJobRepository;
use App\Services\ColdstoreJobs\JobMatchingService;
use App\Services\ColdstoreJobs\LineWorkplaceMapper;
use App\Support\ColdstoreAppSurfaceResolver;
use Composer\InstalledVersions;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ColdstoreDashboardController extends Controller
{
    public function __construct(
        private ColdstoreApiService $coldstoreApiService,
        private ColdstoreJobRepository $coldstoreJobRepository,
        private JobMatchingService $jobMatchingService,
        private LineWorkplaceMapper $lineWorkplaceMapper,
        private ColdstoreAppSurfaceResolver $coldstoreAppSurfaceResolver,
    ) {}

    public function index(Request $request): View
    {
        $defaultLine = $this->lineWorkplaceMapper->defaultLine();
        $jobsDataSource = (string) config('coldstore.jobs.data_source', 'local');
        $appSurface = $this->coldstoreAppSurfaceResolver->resolve($request);
        $jobsApi = [
            'dataSource' => $jobsDataSource,
            'baseUrl' => $this->normalizedJobsRemoteApiBaseUrl(),
            'jobsPath' => (string) config('coldstore.jobs.jobs_path', '/api/coldstore/jobs'),
        ];

        return view('coldstore.dashboard', [
            'initialOverview' => $this->coldstoreApiService->fetchOverview(),
            'jobs' => $this->coldstoreJobRepository->all(),
            'initialJobs' => $jobsDataSource === 'remote_api'
                ? $this->remoteApiInitialJobsPayload($defaultLine)
                : $this->jobMatchingService->payloadForLine($defaultLine),
            'appSurface' => $appSurface,
            'jobLines' => $this->lineWorkplaceMapper->all(),
            'jobsApi' => $jobsApi,
            'pollIntervalMs' => config('coldstore.poll_interval_seconds') * 1000,
        ]);
    }

    /**
     * @return array{
     *     selected_line: int,
     *     arbeitsplatz_nr: int,
     *     order: null,
     *     next_order: null,
     *     matching_uids: array<int, mixed>,
     *     next_matching_uids: array<int, mixed>,
     *     meta: array{
     *         source_mode: string,
     *         loading: bool
     *     }
     * }
     */
    private function remoteApiInitialJobsPayload(int $defaultLine): array
    {
        return [
            'selected_line' => $defaultLine,
            'arbeitsplatz_nr' => $this->lineWorkplaceMapper->workplaceNumberForLine($defaultLine),
            'order' => null,
            'next_order' => null,
            'matching_uids' => [],
            'next_matching_uids' => [],
            'meta' => [
                'source_mode' => 'remote_api',
                'loading' => true,
            ],
        ];
    }

    private function normalizedJobsRemoteApiBaseUrl(): ?string
    {
        $baseUrl = trim((string) config('coldstore.jobs.remote_api_base_url', ''));

        if ($baseUrl === '') {
            return null;
        }

        return rtrim($baseUrl, '/');
    }

    public function scanner(Request $request): View
    {
        return view('coldstore.scanner', [
            'appSurface' => $this->coldstoreAppSurfaceResolver->resolve($request),
            'barcodeEndpoint' => route('api.coldstore.barcodes.store', absolute: false),
            'cameraPluginInstalled' => InstalledVersions::isInstalled('nativephp/mobile-camera'),
            'remoteConfigured' => filled(config('coldstore.remote.base_url')),
            'scannerId' => config('coldstore.scanner.id'),
            'scanDirection' => config('coldstore.scanner.direction'),
        ]);
    }

    public function settings(Request $request): View
    {
        return view('coldstore.settings', [
            'appSurface' => $this->coldstoreAppSurfaceResolver->resolve($request),
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
