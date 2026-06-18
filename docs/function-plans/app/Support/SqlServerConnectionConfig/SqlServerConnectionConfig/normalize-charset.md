# normalizeCharset

## Ort
- Datei: `app/Support/SqlServerConnectionConfig.php`
- Klasse/Modul: `App\Support\SqlServerConnectionConfig`
- Signatur: `normalizeCharset(?string $value, string $default = 'UTF-8'): string`

## Kurzbeschreibung
Normalisiert Zeichensatz-Angaben für den SQL-Server-Treiber.

## Zweck im System
Verhindert abweichende Schreibweisen wie `UTF8` gegen die Zielkonfiguration `UTF-8`.

## Ablaufplan
1. Normalisiert den Eingabewert in Großbuchstaben.
2. Verwendet bei leerer Eingabe den Fallback.
3. Mappt `UTF8` und `UTF-8` auf `UTF-8`.
4. Gibt sonst den Originalwert zurück.

## Eingaben
- `?string $value`
- `string $default = 'UTF-8'`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Leere Eingaben fallen auf den konfigurierten Standard zurück.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn sich SQL-Quelle, Row-Mapping oder Fallback-Regeln ändern.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/SqlServerConnectionConfigTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
