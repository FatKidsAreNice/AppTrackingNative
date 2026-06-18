# hasRemoteBaseUrl

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `hasRemoteBaseUrl(): bool`

## Kurzbeschreibung
Prüft, ob eine nutzbare Remote-Basis-URL für die Coldstore-Integration konfiguriert ist.

## Zweck im System
Dient als technische Schaltstelle zwischen Demo-, lokalem und Remote-Verhalten.

## Ablaufplan
1. Liest die konfigurierte Remote-Basis-URL.
2. Bewertet, ob sie als befüllt gilt.
3. Gibt `true` oder `false` zurück.

## Eingaben
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: boolesche Entscheidung.

## Verwendete Abhängigkeiten
- `config()`

## Fachliche Regeln
Leere oder fehlende Base-URLs gelten als nicht remote-fähig.

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
