<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('defines local as the default jobs data source in config', function () {
    expect(file_get_contents(config_path('coldstore.php')))
        ->toContain("env('COLDSTORE_JOBS_DATA_SOURCE', 'local')")
        ->and(file_get_contents(config_path('coldstore.php')))
        ->toContain("env('COLDSTORE_APP_SURFACE', 'desktop')");
});

it('renders the dashboard without the hero header', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('maximum-scale=1, user-scalable=no, viewport-fit=cover', false)
        ->assertDontSee('Sauels Coldstore Monitor')
        ->assertDontSee('Track Overview')
        ->assertDontSee('Jetzt aktualisieren')
        ->assertDontSee('Kühlhaus-Overview mit Live-Tracks und BEV-Sync');
});

it('renders the compact jobs overview for the mobile workflow', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard', ['surface' => 'mobile']));

    $response->assertSuccessful()
        ->assertSee('coldstore-surface-mobile', false)
        ->assertSee('class="coldstore-shell "', false)
        ->assertDontSee('class="coldstore-shell nativephp-safe-area"', false)
        ->assertSee('panel-card panel-card--jobs', false)
        ->assertSee('Overview')
        ->assertSee('Aufträge')
        ->assertSee('Jobs')
        ->assertSee('Kochkammer')
        ->assertSee('Linie 1')
        ->assertSee('Linie 6')
        ->assertDontSee('Ausgewählte Linie')
        ->assertDontSee('Arbeitsplatz')
        ->assertDontSee('Datenquelle')
        ->assertDontSee('MatStamm MatNr')
        ->assertDontSee('MatStamm_FuellArtNr')
        ->assertSee('Produkt fuer PEText1 95106')
        ->assertSee('Rinderschinken fuer PEText1 91200')
        ->assertDontSee('VA_Status 2')
        ->assertDontSee('Required_PEText1')
        ->assertDontSee('Menge')
        ->assertDontSee('123,45 kg')
        ->assertSee('data-open-job-detail="current"', false)
        ->assertSee('data-open-job-detail="next"', false)
        ->assertSee('data-jobs-panel', false)
        ->assertSee('data-jobs-line-selector', false)
        ->assertDontSee('data-job-detail-panel', false)
        ->assertDontSee('data-close-job-detail', false)
        ->assertDontSee('← Zurück zu Aufträgen', false)
        ->assertSee('"barcode_id":"32171700"', false)
        ->assertSee('"barcode_id":"32167948"', false)
        ->assertSee('"matching_uids":[{"uid":"32171700"', false)
        ->assertSee('"next_matching_uids":[{"uid":"32167948"', false)
        ->assertSee('"dataSource":"local"', false)
        ->assertSee('"jobsPath":"\\/api\\/coldstore\\/jobs"', false)
        ->assertSee('data-select-dashboard-screen="overview"', false)
        ->assertSee('data-select-dashboard-screen="jobs"', false)
        ->assertDontSee('Bewegung aktiv')
        ->assertDontSee('track-map--rotated', false)
        ->assertSee('Kühlhaus')
        ->assertSee('BEV-Quelle');
});

it('automatically uses the mobile surface for nativephp requests', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this
        ->withHeader('User-Agent', 'NativePHP/1.0')
        ->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('coldstore-surface-mobile', false)
        ->assertDontSee('class="coldstore-shell nativephp-safe-area"', false);
});

it('uses the emphasized required pe text suffix formatting across the jobs ui renderers', function () {
    $css = file_get_contents(resource_path('css/app.css'));
    $js = file_get_contents(resource_path('js/coldstore-app.js'));
    $blade = file_get_contents(resource_path('views/coldstore/dashboard.blade.php'));

    expect($css)
        ->toContain('.required-pe-text1__suffix')
        ->toContain('font-size: 1.2em;')
        ->toContain('font-weight: 800;')
        ->and($js)
        ->toContain('<dt>Material</dt>')
        ->toContain('<strong class="required-pe-text1__suffix">')
        ->toContain('<dd>${formatRequiredPeText1(order.required_pe_text1)}</dd>')
        ->toContain('<dd>${formatRequiredPeText1(detail.order?.required_pe_text1)}</dd>')
        ->and($blade)
        ->toContain('<strong class="required-pe-text1__suffix">');
});

it('uses a jobs view state that replaces the overview with the selected detail view', function () {
    $css = file_get_contents(resource_path('css/app.css'));
    $js = file_get_contents(resource_path('js/coldstore-app.js'));
    $blade = file_get_contents(resource_path('views/coldstore/dashboard.blade.php'));

    expect($js)
        ->toContain("jobsView: 'overview'")
        ->toContain('selectedOrderKind: null')
        ->toContain('highlightedTrackId: null')
        ->toContain('highlightedTrackUid: null')
        ->toContain('overviewHighlightMessage: null')
        ->toContain('jobOrder.innerHTML = renderJobDetailPanel(detail);')
        ->toContain('jobOrder.innerHTML = renderJobOverview();')
        ->toContain("state.jobsView = 'detail';")
        ->toContain('state.selectedOrderKind = button.dataset.openJobDetail ?? null;')
        ->toContain('scrollJobsDetailIntoView();')
        ->toContain("const shouldShowLineSelector = state.jobsView === 'overview';")
        ->toContain('linePicker.hidden = !shouldShowLineSelector;')
        ->toContain('setLinePickerOpen(false);')
        ->toContain("const detail = root.querySelector('[data-jobs-detail]');")
        ->toContain("const panel = root.querySelector('[data-jobs-panel]');")
        ->toContain('target.scrollIntoView({')
        ->toContain('data-jobs-view="overview"')
        ->toContain('data-jobs-view="detail"')
        ->toContain('data-jobs-detail')
        ->toContain('← Zurück zu Aufträgen')
        ->toContain('${renderMatchingUids(detail.matchingUids, detail.order?.va_menge_kg)}')
        ->toContain('function openOverviewForUid(uid)')
        ->toContain("state.activeDashboardScreen = 'overview';")
        ->toContain('findTrackByUid(normalizedUid)')
        ->toContain('data-open-overview-uid')
        ->toContain('job-order-card__match-button')
        ->toContain('const CABINET_SCRAP_RATE = 0.10;')
        ->toContain('function buildCabinetWeightBreakdown(netWeightKg, orderWeightKg)')
        ->toContain('const scrapWeightKg = normalizedNetWeightKg * CABINET_SCRAP_RATE;')
        ->toContain('const availableWeightKg = normalizedNetWeightKg - scrapWeightKg;')
        ->toContain('const remainingAfterOrderKg = availableWeightKg - normalizedOrderWeightKg;')
        ->toContain('<strong>UID: ${escapeHtml(matchingUid.uid)}</strong>')
        ->toContain('<small>Netto: ${escapeHtml(formatCabinetWeight(weightBreakdown.netWeightKg))}</small>')
        ->toContain('<small>Verschnitt ca. 10 %: ${escapeHtml(formatCabinetWeight(weightBreakdown.scrapWeightKg))}</small>')
        ->toContain('<small>Verfuegbar: ${escapeHtml(formatCabinetWeight(weightBreakdown.availableWeightKg))}</small>')
        ->toContain('<small>Auftragsmenge: ${escapeHtml(formatCabinetWeight(weightBreakdown.orderWeightKg))}</small>')
        ->toContain('<small>Rest nach Auftrag: ${escapeHtml(formatCabinetWeight(weightBreakdown.remainingAfterOrderKg))}</small>')
        ->toContain('<small>Fehlt: ${escapeHtml(formatCabinetWeight(weightBreakdown.missingWeightKg))}</small>')
        ->toContain('<small>Nach: ${escapeHtml(matchingUid.cabinet_content?.lager_nach_name ?? \'unbekannt\')}</small>')
        ->toContain('renderMatchingUids(detail.matchingUids, detail.order?.va_menge_kg)')
        ->toContain('weightBreakdown.orderWeightKg === null ? \'\'')
        ->toContain("const mapElement = root.querySelector('[data-coldstore-overview-map]');")
        ->toContain('data-coldstore-track-id="${track.track_id}"')
        ->toContain('data-coldstore-track-uid="${escapeHtml(track.barcode_id || \'\')}"')
        ->toContain("const TRACK_UID_PRESENCE_STORAGE_KEY = 'coldstore-track-uid-presence';")
        ->toContain("const TRACK_MARRIAGE_CONTEXT_STORAGE_KEY = 'coldstore-track-marriage-context';")
        ->toContain("const TRACK_OVERVIEW_FEEDBACK_STORAGE_KEY = 'coldstore-overview-feedback';")
        ->toContain('function readTrackUidPresence()')
        ->toContain('function writePendingMarriageContext(value)')
        ->toContain('function writeOverviewFeedback(value)')
        ->toContain('function adoptPendingMarriageContext()')
        ->toContain('persistedTrackUidPresence[barcodeId] = currentSeenAt;')
        ->toContain('state.trackSessionSeenAt[String(matchingTrack.track_id)] = seenAt;')
        ->toContain('scannerRoute')
        ->toContain('function navigateToScannerForMarriage(track)')
        ->toContain('seen_at: state.trackSessionSeenAt[String(track.track_id)] ?? Date.now()')
        ->toContain("targetUrl.searchParams.set('mode', 'marriage');")
        ->toContain('window.location.assign(targetUrl.toString())')
        ->toContain('const pendingOverviewFeedback = readOverviewFeedback();')
        ->toContain('state.overviewHighlightMessage = pendingOverviewFeedback;')
        ->toContain('writeOverviewFeedback(scanStatus.textContent);')
        ->toContain('window.location.assign(marriageContext.overview_url);')
        ->toContain('track-row--${tone}')
        ->toContain('track-node--${trackStatusTone(track)}')
        ->toContain('humanizeEligibilityReason(selectedTrack.eligibility_reason)')
        ->toContain('mapElement?.scrollIntoView({')
        ->toContain("highlightedElement?.classList.add('track-node--pulse');")
        ->toContain('data-track-uid="${escapeHtml(track.barcode_id || \'\')}"')
        ->toContain('track-row--highlighted')
        ->not->toContain('<small>Gewicht: ${escapeHtml(formatCabinetWeight(matchingUid.cabinet_content?.net_weight_kg ?? null))}</small>')
        ->not->toContain('NaN kg')
        ->not->toContain('root.querySelector(`[data-select-track="${matchingTrack.track_id}"]`)')
        ->not->toContain('<small>Material: ${escapeHtml(matchingUid.cabinet_content?.material_pe_text1 ?? matchingUid.etikinterface_pe_text1 ?? \'—\')}</small>')
        ->not->toContain('<small>Von: ${escapeHtml(matchingUid.cabinet_content?.lager_von_name ?? \'unbekannt\')}</small>')
        ->not->toContain('<small>Status: ${escapeHtml(matchingUid.matches_required_material ? \'passt zum Auftrag\' : \'passt nicht zum Auftrag\')}</small>')
        ->and($css)
        ->toContain('.coldstore-surface-mobile .panel-card--jobs')
        ->toContain('align-self: stretch;')
        ->toContain('.coldstore-surface-mobile .job-order-card__detail')
        ->toContain('align-content: start;')
        ->toContain('.coldstore-surface-mobile {')
        ->toContain('overscroll-behavior: none;')
        ->toContain('.coldstore-surface-mobile [data-jobs-detail]')
        ->toContain('scroll-margin-top: 0.75rem;')
        ->toContain('.job-order-card__match-button')
        ->toContain('.track-row--highlighted')
        ->toContain('.track-row--assigned')
        ->toContain('.track-row--existing')
        ->toContain('.track-row--eligible')
        ->toContain('.track-row--blocked')
        ->toContain('.track-node--highlighted')
        ->toContain('.track-node--assigned')
        ->toContain('.track-marriage-button-secondary')
        ->toContain('.track-node--pulse')
        ->toContain('@keyframes coldstore-track-pulse')
        ->toContain('[data-jobs-line-selector][hidden]')
        ->toContain('display: none !important;')
        ->and($blade)
        ->toContain('data-coldstore-overview-map')
        ->toContain('data-jobs-panel')
        ->toContain('data-jobs-line-selector')
        ->toContain('data-scanner-route')
        ->not->toContain('data-job-detail-panel hidden');
});

it('does not render the old inline track marriage module on the dashboard', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('data-scanner-route', false)
        ->assertDontSee('UID Hochzeit')
        ->assertDontSee('Track Status')
        ->assertDontSee('data-track-marriage-feedback', false)
        ->assertDontSee('data-track-marriage-form', false)
        ->assertDontSee('data-track-marriage-dialog', false)
        ->assertDontSee('UID Scan / Eingabe');
});

it('renders demo overview tracks with stable uid to track assignments for overview highlighting', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard', ['surface' => 'mobile']));

    $response->assertSuccessful()
        ->assertSee('"track_id":101', false)
        ->assertSee('"barcode_id":"32171700"', false)
        ->assertSee('"track_id":204', false)
        ->assertSee('"barcode_id":"32167948"', false);
});

it('renders a neutral loading jobs state for remote api mode', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'remote_api');
    Config::set('coldstore.jobs.remote_api_base_url', 'http://10.10.123.66:8000');
    Config::set('coldstore.jobs.production_orders.source', 'sqlsrv');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('"dataSource":"remote_api"', false)
        ->assertSee('"baseUrl":"http:\\/\\/10.10.123.66:8000"', false)
        ->assertSee('"jobsPath":"\\/api\\/coldstore\\/jobs"', false)
        ->assertSee('Jobs werden geladen ...')
        ->assertDontSee('"matching_uids":[{"uid":"32171700"', false)
        ->assertDontSee('4711-06');
});

it('falls back to the order article name and shows no next order hint when needed', function () {
    Config::set('coldstore.remote.base_url', null);
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.default_selected_line', 2);
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('Kochschinken Linie 2')
        ->assertSee('Kein freigegebener Folgeauftrag vorhanden')
        ->assertDontSee('VA_Status 2')
        ->assertDontSee('Required_PEText1')
        ->assertDontSee('Menge');
});

it('renders the scanner module', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->get(route('coldstore.scanner'));

    $response->assertSuccessful()
        ->assertSee('Barcode Modul')
        ->assertSee('Scanner ID')
        ->assertSee('Richtung')
        ->assertSee('data-track-marriage-endpoint', false);
});

it('renders marriage context on the scanner page when a track is selected from overview', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->get(route('coldstore.scanner', [
        'mode' => 'marriage',
        'track_id' => 1,
        'track_label' => 'T1',
        'zone_label' => 'Reihe 4',
        'position_label' => 'x=-1.05 y=1.15',
    ]));

    $response->assertSuccessful()
        ->assertSee('Ausgewaehlter Track')
        ->assertSee('Modus: UID zuweisen')
        ->assertSee('T1')
        ->assertSee('Reihe 4')
        ->assertSee('x=-1.05 y=1.15')
        ->assertSee('UID dem Track zuordnen')
        ->assertSee('data-track-marriage-endpoint', false);
});

it('renders the settings page', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');

    $response = $this->get(route('coldstore.settings'));

    $response->assertSuccessful()
        ->assertSee('Integration Status')
        ->assertSee('http://coldstore.test')
        ->assertSee('Scan-Richtung');
});

it('embeds map rotation config for the frontend dashboard', function () {
    Config::set('coldstore.remote.base_url', 'http://coldstore.test');
    Config::set('coldstore.remote.overview_path', '/overview');
    Config::set('coldstore.jobs.data_source', 'local');
    Config::set('coldstore.jobs.production_orders.source', 'mock');

    Http::fake([
        'http://coldstore.test/overview' => Http::response([
            'frame_id' => 'remote_frame',
            'stamp_sec' => 1050.0,
            'bev_stamp_sec' => 1049.8,
            'lookup_mode' => 'track_id',
            'map_rotation_deg' => 22.0,
            'overview_image_url' => 'http://coldstore.test/overview-image',
            'tracks' => [
                [
                    'track_id' => 7,
                    'barcode_id' => '',
                    'class_name' => 'rack_side',
                    'state' => 'confirmed',
                    'motion_state' => 'static',
                    'confidence' => 0.91,
                    'x' => 1.2,
                    'y' => 2.5,
                    'z' => 0,
                ],
            ],
            'highlighted_racks' => [],
            'coldstore' => [
                'name' => 'Remote Haus',
                'summary' => 'Live vom KI-PC',
            ],
        ]),
    ]);

    $response = $this->get(route('coldstore.dashboard'));

    $response->assertSuccessful()
        ->assertSee('"rotation_deg":22', false)
        ->assertSee('"track_stamp_sec":1050', false);
});
