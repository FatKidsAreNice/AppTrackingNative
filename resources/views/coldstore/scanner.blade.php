@extends('layouts.app')

@section('title', 'Barcode Scanner')

@section('content')
    <div
        class="coldstore-page coldstore-page--narrow"
        data-coldstore-scanner
        data-barcode-endpoint="{{ $barcodeEndpoint }}"
    >
        <section class="hero-banner">
            <div class="hero-banner__content">
                <div class="hero-banner__brand">
                    <img class="hero-banner__logo" src="{{ asset('logo_sauels.svg') }}" alt="Sauels Logo">
                    <div>
                        <p class="hero-banner__eyebrow">Sauels Coldstore Monitor</p>
                        <h1 class="hero-banner__title">Barcode Modul</h1>
                    </div>
                </div>
                <span class="status-pill {{ $scannerPluginInstalled ? 'status-pill--ok' : 'status-pill--warn' }}">
                    {{ $scannerPluginInstalled ? 'Scanner Plugin aktiv' : 'Scanner Plugin fehlt' }}
                </span>
            </div>
            <p class="hero-banner__subtitle">
                Barcode scannen und direkt per HTTP POST an den anderen PC senden.
            </p>
        </section>

        <section class="panel-grid panel-grid--single">
            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Senden</p>
                        <h2 class="panel-card__title">Scan erfassen</h2>
                    </div>
                </div>

                <div class="callout {{ $remoteConfigured ? 'callout--ok' : 'callout--warn' }}">
                    {{ $remoteConfigured ? 'Remote-POST ist konfiguriert.' : 'Remote-POST ist noch nicht konfiguriert. Das Senden liefert sonst bewusst einen Fehler.' }}
                </div>

                @if (! $scannerPluginInstalled)
                    <div class="callout callout--warn">
                        Kamera-Scanning über NativePHP braucht im aktuellen Build noch das Paket <code>nativephp/mobile-scanner</code>.
                        Das Formular unten funktioniert trotzdem für manuelle Eingabe oder einen Hardware-Scanner, der als Tastatur arbeitet.
                    </div>
                @endif

                <div class="scanner-actions">
                    <button class="hero-banner__button" type="button" data-open-native-scan>Mit Kamera scannen</button>
                    <p class="panel-card__muted" data-scan-status>Bereit für den nächsten Scan.</p>
                </div>

                <form class="scanner-form" data-barcode-form>
                    <label class="field">
                        <span class="field__label">Barcode ID</span>
                        <input class="panel-input" name="barcode_id" type="text" autocomplete="off" placeholder="Barcode eingeben oder einscannen" required data-barcode-input>
                    </label>
                    <label class="field">
                        <span class="field__label">Scanner ID</span>
                        <input class="panel-input" name="scanner_id" type="text" value="{{ $scannerId }}" data-scanner-id-input>
                    </label>
                    <label class="field">
                        <span class="field__label">Richtung</span>
                        <select class="panel-input" name="direction" data-direction-input>
                            <option value="entry" @selected($scanDirection === 'entry')>entry</option>
                            <option value="exit" @selected($scanDirection === 'exit')>exit</option>
                        </select>
                    </label>
                    <button class="hero-banner__button hero-banner__button--full" type="submit">An anderen PC senden</button>
                </form>
            </article>

            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Zuletzt gesendet</p>
                        <h2 class="panel-card__title">Letzter Scan</h2>
                    </div>
                </div>
                <dl class="detail-grid" data-last-scan></dl>
            </article>

            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">History</p>
                        <h2 class="panel-card__title">Lokale Liste</h2>
                    </div>
                </div>
                <div class="track-list" data-scan-history></div>
            </article>
        </section>
    </div>

    <script>
        window.coldstoreScannerConfig = {!! json_encode([
            'scannerPluginInstalled' => $scannerPluginInstalled,
            'remoteConfigured' => $remoteConfigured,
            'scannerId' => $scannerId,
            'scanDirection' => $scanDirection,
        ]) !!};
    </script>
@endsection
