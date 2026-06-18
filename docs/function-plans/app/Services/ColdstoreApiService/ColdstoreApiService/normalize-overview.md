# normalizeOverview

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `normalizeOverview(array $payload, string $sourceMode): array`

## Kurzbeschreibung
Normalisiert Remote- oder Demo-Overview-Daten in das feste Frontend-Format.

## Zweck im System
Schafft ein stabiles Datenmodell für Dashboard, Karte, Track-Liste und Detailansicht.

## Ablaufplan
1. Liest Meta-, Sync- und Lookup-Informationen aus dem Rohpayload.
2. Normalisiert Tracks, hervorgehobene Schränke und Bereiche.
3. Sichert eine gültige `selected_track_id`.
4. Wählt Hintergrundbild und Kartenparameter.
5. Gibt das vollständige Overview-Payload zurück.

## Eingaben
- `array $payload`
- `string $sourceMode`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `normalizeRack()`
- `normalizeSections()`
- `normalizeTrack()`
- `remoteLabel()`
- `statusText()`

## Fachliche Regeln
Track- und Marriage-Felder werden in ein konsistentes Frontend-Format überführt.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Remote-Payloads, Endpunkte oder Fallback-Verhalten erweitert werden.

## Nicht zuständig für
- Nicht zuständig für das direkte UI-Rendering im Browser.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
