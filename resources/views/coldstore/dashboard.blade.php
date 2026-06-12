@extends('layouts.app')

@section('title', 'Coldstore Overview')

@section('content')
    <div
        class="coldstore-page"
        data-coldstore-dashboard
        data-poll-interval="{{ $pollIntervalMs }}"
        data-overview-endpoint="{{ route('api.coldstore.overview', absolute: false) }}"
    >
        <section class="hero-banner">
            <div class="hero-banner__content">
                <div class="hero-banner__brand">
                    <img class="hero-banner__logo" src="/logo_sauels.svg" alt="Sauels Logo">
                    <div>
                        <p class="hero-banner__eyebrow">Sauels Coldstore Monitor</p>
                        <h1 class="hero-banner__title">Track Overview</h1>
                    </div>
                </div>
                <div class="hero-banner__meta">
                    <span class="status-pill" data-source-pill>{{ $initialOverview['meta']['source_mode'] }}</span>
                    <button class="hero-banner__button" type="button" data-refresh-overview>Jetzt aktualisieren</button>
                </div>
            </div>
            <p class="hero-banner__subtitle" data-overview-subtitle>{{ $initialOverview['overview']['subtitle'] }}</p>
        </section>

        <section class="dashboard-screen" data-dashboard-screen="overview">
            <section class="stats-grid">
                <article class="info-card">
                    <p class="info-card__label">Sichtbare Tracks</p>
                    <p class="info-card__value" data-track-count>{{ $initialOverview['overview']['track_count'] }}</p>
                </article>
                <article class="info-card">
                    <p class="info-card__label">BEV-Quelle</p>
                    <p class="info-card__value info-card__value--small" data-bev-source>
                        {{ $initialOverview['map']['background_url'] ? 'Live-Bild aktiv' : 'Nur Track-Overlay' }}
                    </p>
                </article>
            </section>

            <section class="panel-grid">
                <article class="panel-card panel-card--map">
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Kühlhaus</p>
                            <h2 class="panel-card__title" data-coldstore-name>{{ $initialOverview['coldstore']['name'] }}</h2>
                        </div>
                        <p class="panel-card__muted" data-updated-at>{{ $initialOverview['meta']['updated_at'] }}</p>
                    </div>
                    <p class="panel-card__description" data-coldstore-summary>{{ $initialOverview['coldstore']['summary'] }}</p>
                    <div class="map-frame">
                        <div class="bev-map-header panel-card__header">
                            <div>
                                <p class="panel-card__eyebrow">BEV</p>
                                <h3 class="panel-card__title">BEV Track Positions</h3>
                            </div>
                        </div>
                        <div class="bev-stage">
                            <span class="bev-location-label">Kochkammer</span>
                            <span class="bev-lines-label">Linien</span>
                            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="track-map" data-track-map></svg>
                        </div>
                    </div>
                </article>

                <article class="panel-card">
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Tracks</p>
                            <h2 class="panel-card__title">Live-Liste</h2>
                        </div>
                        <input class="panel-input" type="search" placeholder="Track ID / Barcode suchen" data-track-filter>
                    </div>
                    <div class="track-list" data-track-list></div>
                </article>

                <article class="panel-card">
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Track Details</p>
                            <h2 class="panel-card__title" data-detail-title>Keine Auswahl</h2>
                        </div>
                    </div>
                    <dl class="detail-grid" data-track-detail></dl>
                </article>

                <article class="panel-card panel-card--wide">
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Kühlhaus-Status</p>
                            <h2 class="panel-card__title">Bereiche</h2>
                        </div>
                    </div>
                    <div class="section-grid" data-section-list></div>
                </article>
            </section>
        </section>

        <section class="dashboard-screen" data-dashboard-screen="jobs" hidden>
            <section class="jobs-panel">
                <article class="panel-card">
                    @php($initialOrder = $initialJobs['order'])
                    @php($initialNextOrder = $initialJobs['next_order'] ?? null)
                    @php($initialNextMatchingUids = $initialJobs['next_matching_uids'] ?? [])
                    @php($isJobsRemoteApiLoading = (bool) data_get($initialJobs, 'meta.loading', false))
                    @php($formatRequiredPeText1 = function (?string $value): string {
                        $normalizedValue = trim((string) $value);

                        if ($normalizedValue === '') {
                            return '<span class="required-pe-text1 required-pe-text1--empty">&mdash;</span>';
                        }

                        if (strlen($normalizedValue) <= 3) {
                            return '<span class="required-pe-text1">'.e($normalizedValue).'</span>';
                        }

                        return '<span class="required-pe-text1">'.e(substr($normalizedValue, 0, -3)).'<strong>'.e(substr($normalizedValue, -3)).'</strong></span>';
                    })
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Jobs</p>
                            <h2 class="panel-card__title">Vorbereitete Aufträge</h2>
                        </div>
                        <div class="line-picker" data-line-picker>
                            <button class="line-picker__toggle" type="button" data-toggle-line-picker aria-expanded="false">
                                <span class="line-picker__label">Linie</span>
                                <strong data-selected-line-label>Linie {{ $initialJobs['selected_line'] }}</strong>
                            </button>
                            <div class="line-picker__menu" data-line-picker-menu hidden>
                                @foreach ($jobLines as $line)
                                    <button
                                        class="line-picker__option {{ $line['line'] === $initialJobs['selected_line'] ? 'line-picker__option--active' : '' }}"
                                        type="button"
                                        data-select-line="{{ $line['line'] }}"
                                    >
                                        <span>{{ $line['label'] }}</span>
                                        <small>AP {{ $line['workplace_number'] }}</small>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="jobs-panel__meta" data-job-summary hidden aria-hidden="true">
                        <article class="jobs-stat">
                            <span class="jobs-stat__label"></span>
                            <strong class="jobs-stat__value" data-job-selected-line></strong>
                        </article>
                        <article class="jobs-stat">
                            <span class="jobs-stat__label"></span>
                            <strong class="jobs-stat__value" data-job-workplace></strong>
                        </article>
                        <article class="jobs-stat">
                            <span class="jobs-stat__label"></span>
                            <strong class="jobs-stat__value" data-job-source></strong>
                        </article>
                    </div>

                    <div class="job-order-card" data-job-order>
                        @if ($isJobsRemoteApiLoading)
                            <div class="job-order-card__empty">
                                <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                                <p>Jobs werden geladen ...</p>
                            </div>
                        @elseif ($initialOrder)
                            <section class="job-order-card__section">
                                <div class="job-order-card__header">
                                    <div>
                                        <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                                        <h3 class="panel-card__title">{{ $initialOrder['va_auftragsnr'] }}</h3>
                                    </div>
                                    <span class="status-pill status-pill--ok">VA_Status {{ $initialOrder['va_status'] }}</span>
                                </div>
                                <dl class="detail-grid detail-grid--compact">
                                    <dt>Produktname</dt>
                                    <dd>{{ $initialOrder['required_product_name'] ?? $initialOrder['matstamm_maktx'] }}</dd>
                                    <dt>Required_PEText1</dt>
                                    <dd>{!! $formatRequiredPeText1($initialOrder['required_pe_text1'] ?? null) !!}</dd>
                                    <dt>Menge</dt>
                                    <dd>{{ $initialOrder['va_menge_kg'] !== null ? number_format((float) $initialOrder['va_menge_kg'], 2, ',', '.').' kg' : 'unbekannt' }}</dd>
                                    <dt>Beginn Soll</dt>
                                    <dd>{{ $initialOrder['va_beginn_soll'] }}</dd>
                                </dl>
                            </section>

                            @if ($initialNextOrder)
                                <section class="job-order-card__section">
                                    <div class="job-order-card__header">
                                        <div>
                                            <p class="panel-card__eyebrow">Folgeauftrag</p>
                                            <h3 class="panel-card__title">{{ $initialNextOrder['va_auftragsnr'] }}</h3>
                                        </div>
                                        <span class="status-pill status-pill--ok">VA_Status {{ $initialNextOrder['va_status'] }}</span>
                                    </div>
                                    <dl class="detail-grid detail-grid--compact">
                                        <dt>Produktname</dt>
                                        <dd>{{ $initialNextOrder['required_product_name'] ?? $initialNextOrder['matstamm_maktx'] }}</dd>
                                        <dt>Required_PEText1</dt>
                                        <dd>{!! $formatRequiredPeText1($initialNextOrder['required_pe_text1'] ?? null) !!}</dd>
                                        <dt>Menge</dt>
                                        <dd>{{ $initialNextOrder['va_menge_kg'] !== null ? number_format((float) $initialNextOrder['va_menge_kg'], 2, ',', '.').' kg' : 'unbekannt' }}</dd>
                                        <dt>Beginn Soll</dt>
                                        <dd>{{ $initialNextOrder['va_beginn_soll'] }}</dd>
                                    </dl>
                                    @if (count($initialNextMatchingUids) > 0)
                                        <div class="job-order-card__matches">
                                            @foreach ($initialNextMatchingUids as $matchingUid)
                                                <span>
                                                    <strong>{{ $matchingUid['uid'] }}</strong>
                                                    <small>PEText1 {{ $matchingUid['etikinterface_pe_text1'] }}</small>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="panel-card__muted job-order-card__note">Keine passende UID fuer den Folgeauftrag gefunden.</p>
                                    @endif
                                </section>
                            @else
                                <section class="job-order-card__section job-order-card__empty">
                                    <p class="panel-card__eyebrow">Folgeauftrag</p>
                                    <p>Kein freigegebener Folgeauftrag vorhanden</p>
                                </section>
                            @endif
                        @else
                            <div class="job-order-card__empty">
                                <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                                <p>Für diese Linie liegt aktuell kein offener Auftrag mit VA_Status 2 vor.</p>
                            </div>
                        @endif
                    </div>

                    <div class="jobs-list" data-jobs-list>
                        @if ($isJobsRemoteApiLoading)
                            <article class="job-row job-row--empty">
                                <span>
                                    <strong>Jobs werden geladen ...</strong>
                                    <small>Die Auftragsdaten werden vom Backend abgerufen.</small>
                                </span>
                            </article>
                        @else
                            @forelse ($initialJobs['matching_uids'] as $matchingUid)
                                <button
                                    class="job-row {{ $matchingUid['has_track_assignment'] ? '' : 'job-row--muted' }}"
                                    type="button"
                                    data-select-job-uid="{{ $matchingUid['uid'] }}"
                                >
                                    <span>
                                        <strong>{{ $matchingUid['uid'] }}</strong>
                                        <small>PEText1 {{ $matchingUid['etikinterface_pe_text1'] }}</small>
                                    </span>
                                    <span>
                                        <strong>{{ $matchingUid['track_id'] ? 'Track '.$matchingUid['track_id'] : 'Kein Track' }}</strong>
                                        <small>{{ $matchingUid['state'] }}</small>
                                    </span>
                                </button>
                            @empty
                                <article class="job-row job-row--empty">
                                    <span>
                                        <strong>Keine passende UID</strong>
                                        <small>Im aktuellen Kühlhausbestand wurde noch kein Treffer gefunden.</small>
                                    </span>
                                </article>
                            @endforelse
                        @endif
                    </div>

                    <p class="panel-card__muted jobs-panel__status" data-job-status>
                        @if ($isJobsRemoteApiLoading)
                            Jobs werden geladen ...
                        @elseif (! $initialOrder)
                            Für diese Linie liegt aktuell kein offener Auftrag mit VA_Status 2 vor.
                        @elseif (count($initialJobs['matching_uids']) === 0)
                            Kein passender UID-Bestand im Kühlhaus gefunden.
                        @else
                            {{ count($initialJobs['matching_uids']) }} passende UID(s) im Kühlhaus gefunden.
                        @endif
                    </p>
                </article>
            </section>
        </section>

        <nav class="dashboard-switcher" aria-label="Dashboard Ansicht">
            <button class="dashboard-switcher__item dashboard-switcher__item--active" type="button" data-select-dashboard-screen="overview">
                Overview
            </button>
            <button class="dashboard-switcher__item" type="button" data-select-dashboard-screen="jobs">
                Aufträge
            </button>
        </nav>
    </div>

    <script>
        window.coldstoreDashboardConfig = @json($initialOverview);
        window.coldstoreDashboardJobs = @json($jobs);
        window.coldstoreDashboardInitialJobs = @json($initialJobs);
        window.coldstoreDashboardJobLines = @json($jobLines);
        window.coldstoreDashboardJobsApi = @json($jobsApi);
    </script>
@endsection
