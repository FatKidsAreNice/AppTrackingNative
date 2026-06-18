# requestJson

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `requestJson(string $method, string $path, array $payload = []): array`

## Kurzbeschreibung
Führt einen HTTP-Request gegen das Remote-System aus und dekodiert die JSON-Antwort.

## Zweck im System
Kapselt die gemeinsame Remote-Kommunikation für Overview, Barcode-Forwarding und Marriage-Proxy.

## Ablaufplan
1. Baut den Request-Pfad relativ zur Remote-Basis-URL auf.
2. Führt den HTTP-Request mit Methode und Payload aus.
3. Dekodiert die Remote-Antwort als Array.
4. Gibt die Daten oder eine Exception an den Aufrufer weiter.

## Eingaben
- `string $method`
- `string $path`
- `array $payload = []`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- `client()`

## Fachliche Regeln
Alle Remote-Endpunkte dieses Services laufen durch denselben JSON-Dekodierpfad.

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
