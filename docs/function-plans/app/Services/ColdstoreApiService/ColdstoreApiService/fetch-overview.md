# fetchOverview

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `fetchOverview(): array`

## Kurzbeschreibung
Lädt die Coldstore-Overview vom Remote-System oder fällt kontrolliert auf Demo-Daten zurück.

## Zweck im System
Ist der zentrale Einstieg für alle Overview-, Karten- und Track-Daten der App.

## Ablaufplan
1. Prüft, ob eine Remote-Basis-URL konfiguriert ist.
2. Ruft im Remote-Fall das Overview-Ende ab.
3. Normalisiert die Antwort für das Frontend.
4. Fällt bei Fehlern optional auf Demo-Daten zurück.
5. Ergänzt bei Fallback einen Remote-Fehlerhinweis im Payload.

## Eingaben
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- `config()`
- `hasRemoteBaseUrl()`
- `normalizeOverview()`
- `requestJson()`
- `sampleOverview()`
- `view()`

## Fachliche Regeln
Wenn `demo_fallback` aktiv ist, bleibt die Overview trotz Remote-Ausfall benutzbar.

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
