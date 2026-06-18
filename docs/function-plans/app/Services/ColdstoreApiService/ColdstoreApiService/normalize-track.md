# normalizeTrack

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `normalizeTrack(array $track, string $lookupMode): array`

## Kurzbeschreibung
Normalisiert ein einzelnes Track-Objekt inklusive Anzeige-, Status- und Marriage-Feldern.

## Zweck im System
Stellt sicher, dass Liste, Karte und Detailansicht auf dasselbe Track-Schema zugreifen.

## Ablaufplan
1. Liest Track-ID, Barcode und Anzeigeinformationen.
2. Berechnet Fallbacks für Zone, Position und Last-Seen-Werte.
3. Übernimmt Status-, Motion-, Identity- und Marriage-Felder.
4. Setzt sichere Defaults für fehlende Verheiratungsdaten.
5. Gibt ein konsistentes Track-Array zurück.

## Eingaben
- `array $track`
- `string $lookupMode`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Fehlende Marriage-Felder werden sicher auf `unknown` und nicht verheiratbar gesetzt.

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
