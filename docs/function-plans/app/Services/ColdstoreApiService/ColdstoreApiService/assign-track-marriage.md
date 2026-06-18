# assignTrackMarriage

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `assignTrackMarriage(array $payload): array`

## Kurzbeschreibung
Leitet eine manuelle UID-zu-Track-Zuordnung an den dedizierten Remote-Assignment-Endpunkt weiter.

## Zweck im System
Stellt sicher, dass die finale Verheiratungsentscheidung remote mit Echtzeit-Recheck erfolgt.

## Ablaufplan
1. Prüft die Remote-Konfiguration.
2. Baut das Assignment-Payload mit `track_id`, `uid` und `manual_overview_assignment`.
3. Sendet die Anfrage an den konfigurierten Assignment-Pfad.
4. Dekodiert die Remote-Antwort.
5. Gibt Statuscode und Body für den API-Controller zurück.

## Eingaben
- `array $payload`
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `client()`
- `config()`
- `decodeRemoteJsonResponse()`
- `hasRemoteBaseUrl()`

## Fachliche Regeln
Laravel commitet die UID-Zuordnung nicht selbst, sondern nur über das Remote-System.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.

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
