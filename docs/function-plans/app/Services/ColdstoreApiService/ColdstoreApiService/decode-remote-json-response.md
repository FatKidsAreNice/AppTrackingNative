# decodeRemoteJsonResponse

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `decodeRemoteJsonResponse(Response $response): array`

## Kurzbeschreibung
Dekodiert eine Remote-HTTP-Antwort in ein Array und schützt gegen ungültige JSON-Strukturen.

## Zweck im System
Bildet die Sicherheitskante zwischen HTTP-Antwort und interner Datenverarbeitung.

## Ablaufplan
1. Liest den Response-Body.
2. Dekodiert den Body als JSON-Array.
3. Verwirft unerwartete Strukturen mit einem Fehler.
4. Gibt das validierte Array zurück.

## Eingaben
- `Response $response`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Nur echte JSON-Arrays werden als gültige Remote-Antwort akzeptiert.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.

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
