# statusText

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `statusText(string $sourceMode, ?float $syncDelta): string`

## Kurzbeschreibung
Erzeugt einen sprechenden Status-Text für die Overview aus Datenquelle und Sync-Zustand.

## Zweck im System
Verdichtet technische Metadaten zu einem gut lesbaren UI-Hinweis.

## Ablaufplan
1. Prüft den Source-Mode.
2. Bewertet die Sync-Differenz zwischen Tracking und BEV.
3. Gibt den passenden Status-Text zurück.

## Eingaben
- `string $sourceMode`
- `?float $syncDelta`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Ein unbekannter oder stark verzögerter Sync-Zustand führt zu einem Warntext.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

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
