# forwardBarcode

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `forwardBarcode(array $payload): array`

## Kurzbeschreibung
Bereitet einen Barcode- oder Marriage-Scan für den Remote-PC auf und leitet ihn dorthin weiter.

## Zweck im System
Verbindet den lokalen Scanner-Endpunkt mit dem eigentlichen Remote-Barcode-Workflow.

## Ablaufplan
1. Prüft, ob ein Remote-Endpunkt konfiguriert ist.
2. Baut das Scan-Payload mit Barcode, Scanner-ID, Richtung und Zeit.
3. Ergänzt im Marriage-Modus zusätzlich `mode` und `track_id`.
4. Sendet den Scan an das Remote-System.
5. Gibt lokale Scan-Daten und Remote-Antwort zurück.

## Eingaben
- `array $payload`
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `Carbon::parse()`
- `config()`
- `hasRemoteBaseUrl()`
- `requestJson()`

## Fachliche Regeln
Marriage-Scans verwenden denselben Grund-Forward-Mechanismus, aber mit zusätzlichem Track-Kontext.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.
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
