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

        <section class="stats-grid">
            <article class="info-card">
                <p class="info-card__label">Sichtbare Tracks</p>
                <p class="info-card__value" data-track-count>{{ $initialOverview['overview']['track_count'] }}</p>
            </article>
            <article class="info-card">
                <p class="info-card__label">Sync-Status</p>
                <p class="info-card__value info-card__value--small" data-sync-state>{{ $initialOverview['overview']['status_text'] }}</p>
            </article>
            <article class="info-card">
                <p class="info-card__label">BEV-Quelle</p>
                <p class="info-card__value info-card__value--small" data-bev-source>
                    {{ $initialOverview['map']['background_url'] ? 'Live-Bild aktiv' : 'Nur Track-Overlay' }}
                </p>
            </article>
        </section>

        <section class="jobs-panel">
            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Jobs</p>
                        <h2 class="panel-card__title">Vorbereitete Auftäege</h2>
                    </div>
                    <p class="panel-card__muted">Nach Dringlichkeit sortiert</p>
                </div>
                <div class="jobs-list" data-jobs-list>
                    @foreach ($jobs as $job)
                        <button class="job-row" type="button" data-select-job="{{ $job['uid'] }}">
                            <span>
                                <strong>UID {{ $job['uid'] }}</strong>
                                <small>Ältester Job zuerst</small>
                            </span>
                            <span>
                                <strong>{{ $job['destination'] }}</strong>
                                <small>Priorität {{ $job['priority'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
                <p class="panel-card__muted jobs-panel__status" data-job-status>
                    Job-Auswahl verknuepft bei Treffer die bestehende Track-Markierung.
                </p>
            </article>
        </section>

        <section class="panel-grid">
            <article class="panel-card panel-card--map">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Kühlhaus</p>
                        <h2 class="panel-card__title">{{ $initialOverview['coldstore']['name'] }}</h2>
                    </div>
                    <p class="panel-card__muted" data-updated-at>{{ $initialOverview['meta']['updated_at'] }}</p>
                </div>
                <p class="panel-card__description">{{ $initialOverview['coldstore']['summary'] }}</p>
                <div class="map-frame">
                    <span class="bev-location-label">Kochkammer</span>
                    <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="track-map" data-track-map></svg>
                </div>
                <p class="panel-card__muted" data-status-text>{{ $initialOverview['overview']['status_text'] }}</p>
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
    </div>

    <script>
        window.coldstoreDashboardConfig = @json($initialOverview);
        window.coldstoreDashboardJobs = @json($jobs);
    </script>
@endsection
