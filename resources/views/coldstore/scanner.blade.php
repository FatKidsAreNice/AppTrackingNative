@extends('layouts.app')

@section('title', 'Barcode Scanner')

@section('content')
    <div
        class="coldstore-page coldstore-page--narrow"
        data-coldstore-scanner
        data-barcode-endpoint="{{ $barcodeEndpoint }}"
        data-track-marriage-endpoint="{{ $trackMarriageEndpoint }}"
        data-scanner-mode="{{ $marriageContext['mode'] ?? 'scan' }}"
        @if($marriageContext)
            data-track-id="{{ $marriageContext['track_id'] }}"
        @endif
    >
        <section class="hero-banner">
            <div class="hero-banner__content">
                <div class="hero-banner__brand">
                    <img class="hero-banner__logo" src="/logo_sauels.svg" alt="Sauels Logo">
                    <div>
                        <p class="hero-banner__eyebrow">Sauels Coldstore Monitor</p>
                        <h1 class="hero-banner__title">Barcode Modul</h1>
                    </div>
                </div>
                <span class="status-pill {{ $cameraPluginInstalled ? 'status-pill--ok' : 'status-pill--warn' }}">
                    {{ $cameraPluginInstalled ? 'Kamera-Plugin aktiv' : 'Manueller Fallback aktiv' }}
                </span>
            </div>
            <p class="hero-banner__subtitle">
                Kamera-Test mit dem freien NativePHP-Kamera-Plugin plus manuelle Barcode-Eingabe als Fallback.
            </p>
        </section>

        <section class="panel-grid panel-grid--single">
            @if ($marriageContext)
                <article class="panel-card">
                    <div class="panel-card__header">
                        <div>
                            <p class="panel-card__eyebrow">UID Zuweisung</p>
                            <h2 class="panel-card__title">Ausgewählter Track</h2>
                        </div>
                        <span class="status-pill status-pill--ok">Modus: UID zuweisen</span>
                    </div>
                    <dl class="detail-grid">
                        <dt>Track</dt>
                        <dd>{{ $marriageContext['track_label'] }}</dd>
                        <dt>Track ID</dt>
                        <dd>{{ $marriageContext['track_id'] }}</dd>
                        <dt>Zone</dt>
                        <dd>{{ $marriageContext['zone_label'] }}</dd>
                        <dt>Position</dt>
                        <dd>{{ $marriageContext['position_label'] }}</dd>
                    </dl>
                    <a class="track-marriage-button-secondary" href="{{ $marriageContext['overview_url'] }}">Zurück zur Overview</a>
                </article>
            @endif

            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Kamera-Test</p>
                        <h2 class="panel-card__title">Fotoaufnahme prüfen</h2>
                    </div>
                </div>

                <div class="callout {{ $remoteConfigured ? 'callout--ok' : 'callout--warn' }}">
                    {{ $remoteConfigured ? 'Remote-POST ist konfiguriert.' : 'Remote-POST ist noch nicht konfiguriert. Das Senden liefert sonst bewusst einen Fehler.' }}
                </div>

                <div class="scanner-actions">
                    <button class="hero-banner__button" type="button" data-open-native-camera>Kamera öffnen</button>
                    <p class="panel-card__muted" data-camera-status>Bereit für den Kamera-Test.</p>
                </div>

                <div class="camera-preview" data-camera-preview hidden>
                    <img class="camera-preview__image" alt="Zuletzt aufgenommenes Testfoto" data-camera-preview-image>
                </div>
                <p class="panel-card__muted" data-camera-path>Noch kein Foto aufgenommen.</p>
            </article>

            <article class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <p class="panel-card__eyebrow">Senden</p>
                        <h2 class="panel-card__title">Barcode manuell erfassen</h2>
                    </div>
                </div>

                <form class="scanner-form" data-barcode-form>
                    <label class="field">
                        <span class="field__label">Barcode ID</span>
                        <input class="panel-input" name="barcode_id" type="text" autocomplete="off" placeholder="Barcode eingeben oder per Hardware-Scanner erfassen" required data-barcode-input>
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
                    <button class="hero-banner__button hero-banner__button--full" type="submit">
                        {{ $marriageContext ? 'UID dem Track zuordnen' : 'An anderen PC senden' }}
                    </button>
                </form>
                <p class="panel-card__muted" data-scan-status>Bereit für den nächsten manuellen Scan.</p>
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
            'cameraPluginInstalled' => $cameraPluginInstalled,
            'remoteConfigured' => $remoteConfigured,
            'scannerId' => $scannerId,
            'scanDirection' => $scanDirection,
            'marriageContext' => $marriageContext,
        ]) !!};
    </script>
@endsection
