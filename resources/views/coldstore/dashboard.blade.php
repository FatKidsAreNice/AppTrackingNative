@extends('layouts.app')

@section('title', 'Coldstore Overview')

@section('content')
    <div
        class="coldstore-page"
        data-coldstore-dashboard
        data-poll-interval="{{ $pollIntervalMs }}"
        data-overview-endpoint="{{ route('api.coldstore.overview', absolute: false) }}"
    >
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
                    <div class="map-frame" data-coldstore-overview-map>
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
            <section class="jobs-panel" data-jobs-panel>
                <article class="panel-card panel-card--jobs">
                    @php($initialOrder = $initialJobs['order'])
                    @php($initialNextOrder = $initialJobs['next_order'] ?? null)
                    @php($isJobsRemoteApiLoading = (bool) data_get($initialJobs, 'meta.loading', false))
                    @php($productName = fn (?array $order): string => $order['required_product_name'] ?? $order['matstamm_maktx'] ?? '—')
                    @php($formatOrderQuantity = fn ($value): string => $value !== null ? number_format((float) $value, 2, ',', '.').' kg' : 'unbekannt')
                    @php($formatRequiredPeText1 = function (?string $value): string {
                        $normalizedValue = trim((string) $value);

                        if ($normalizedValue === '') {
                            return '<span class="required-pe-text1 required-pe-text1--empty">&mdash;</span>';
                        }

                        if (strlen($normalizedValue) <= 3) {
                            return '<span class="required-pe-text1">'.e($normalizedValue).'</span>';
                        }

                        return '<span class="required-pe-text1">'.e(substr($normalizedValue, 0, -3)).'<strong class="required-pe-text1__suffix">'.e(substr($normalizedValue, -3)).'</strong></span>';
                    })
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">Jobs</p>
                            <h2 class="panel-card__title">Aufträge</h2>
                        </div>
                        <div class="line-picker" data-line-picker data-jobs-line-selector>
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

                    <div class="job-order-card" data-job-order>
                        @if ($isJobsRemoteApiLoading)
                            <div class="job-order-card__empty">
                                <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                                <p>Jobs werden geladen ...</p>
                            </div>
                        @elseif ($initialOrder)
                            <div class="job-order-card__overview" data-job-overview>
                                <button class="job-order-card__button" type="button" data-open-job-detail="current">
                                    <span class="job-order-card__eyebrow">Aktueller Auftrag</span>
                                    <strong class="job-order-card__number">{{ $initialOrder['va_auftragsnr'] }}</strong>
                                    <span class="job-order-card__product">{{ $productName($initialOrder) }}</span>
                                </button>

                                @if ($initialNextOrder)
                                    <button class="job-order-card__button" type="button" data-open-job-detail="next">
                                        <span class="job-order-card__eyebrow">Folgeauftrag</span>
                                        <strong class="job-order-card__number">{{ $initialNextOrder['va_auftragsnr'] }}</strong>
                                        <span class="job-order-card__product">{{ $productName($initialNextOrder) }}</span>
                                    </button>
                                @else
                                    <article class="job-order-card__button job-order-card__button--disabled" data-open-job-detail="next" aria-disabled="true">
                                        <span class="job-order-card__eyebrow">Folgeauftrag</span>
                                        <strong class="job-order-card__number">—</strong>
                                        <span class="job-order-card__product">Kein freigegebener Folgeauftrag vorhanden</span>
                                    </article>
                                @endif
                            </div>

                        @else
                            <div class="job-order-card__empty">
                                <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                                <p>Für diese Linie liegt aktuell kein offener Auftrag mit VA_Status 2 vor.</p>
                            </div>
                        @endif
                    </div>
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
