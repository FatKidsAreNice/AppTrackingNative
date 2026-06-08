@extends('layouts.app')

@section('title', 'Coldstore Status')

@section('content')
    <div class="coldstore-page coldstore-page--narrow">
        <section class="hero-banner">
            <div class="hero-banner__content">
                <div class="hero-banner__brand">
                    <img class="hero-banner__logo" src="/logo_sauels.svg" alt="Sauels Logo">
                    <div>
                        <p class="hero-banner__eyebrow">Sauels Coldstore Monitor</p>
                        <h1 class="hero-banner__title">Integration Status</h1>
                    </div>
                </div>
                <span class="status-pill {{ $cameraPluginInstalled ? 'status-pill--ok' : 'status-pill--warn' }}">
                    {{ $cameraPluginInstalled ? 'Kamera bereit' : 'Kamera-Plugin offen' }}
                </span>
            </div>
            <p class="hero-banner__subtitle">Hier siehst du, wie die App aktuell an den anderen PC angebunden ist.</p>
        </section>

        <section class="panel-grid panel-grid--single">
            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Proxy</p>
                        <h2 class="panel-card__title">Laravel zu Fremd-PC</h2>
                    </div>
                </div>
                <dl class="detail-grid">
                    <dt>Overview API</dt>
                    <dd>{{ $overviewEndpoint }}</dd>
                    <dt>Barcode API</dt>
                    <dd>{{ $barcodeEndpoint }}</dd>
                    <dt>Remote Base URL</dt>
                    <dd>{{ $remoteBaseUrl ?: 'nicht gesetzt' }}</dd>
                    <dt>Remote Overview Path</dt>
                    <dd>{{ $remoteOverviewPath }}</dd>
                    <dt>Remote Barcode Path</dt>
                    <dd>{{ $remoteBarcodePath }}</dd>
                    <dt>Scanner ID</dt>
                    <dd>{{ $scannerId }}</dd>
                    <dt>Scan-Richtung</dt>
                    <dd>{{ $scanDirection }}</dd>
                    <dt>Polling</dt>
                    <dd>{{ $pollIntervalSeconds }} Sekunden</dd>
                </dl>
            </article>
        </section>
    </div>
@endsection
