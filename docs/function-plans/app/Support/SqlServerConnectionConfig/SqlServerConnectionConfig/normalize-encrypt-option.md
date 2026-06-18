# normalizeEncryptOption

## Ort
- Datei: `app/Support/SqlServerConnectionConfig.php`
- Klasse/Modul: `App\Support\SqlServerConnectionConfig`
- Signatur: `normalizeEncryptOption(mixed $value, string $default = 'yes'): string`

## Kurzbeschreibung
Normalisiert unterschiedliche Encrypt-Eingaben auf die vom SQL-Server-Treiber erwarteten Werte.

## Zweck im System
Schafft eine stabile Konfigurationskante zwischen `.env`-Werten und Treiberoptionen.

## Ablaufplan
1. Prüft Sonderfälle wie `null` und Booleans.
2. Normalisiert Stringwerte in Kleinbuchstaben.
3. Mappt bekannte Eingaben wie `true`, `false`, `yes`, `no`, `mandatory` oder `strict`.
4. Gibt bei unbekannten Werten den Fallback zurück.

## Eingaben
- `mixed $value`
- `string $default = 'yes'`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Boolesche und textuelle Eingaben werden auf die Treiberwerte `yes` oder `no` vereinheitlicht.

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
