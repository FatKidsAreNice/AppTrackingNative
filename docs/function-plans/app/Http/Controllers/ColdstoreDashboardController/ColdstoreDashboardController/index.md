# index

## Ort
- Datei: `app/Http/Controllers/ColdstoreDashboardController.php`
- Klasse/Modul: `App\Http\Controllers\ColdstoreDashboardController`
- Signatur: `index(Request $request): View`

## Kurzbeschreibung
Rendert das Coldstore-Dashboard und stellt die Startdaten für Overview, Jobs und Scanner-Navigation bereit.

## Zweck im System
Ist der zentrale Einstiegspunkt der Coldstore-Hauptoberfläche.

## Ablaufplan
1. Ermittelt Standardlinie, Jobs-Datenquelle und App-Oberfläche.
2. Baut die Jobs-API-Konfiguration für das Frontend auf.
3. Lädt die initiale Overview.
4. Lädt entweder lokale Jobs-Daten oder ein Remote-Loading-Payload.
5. Rendert die Dashboard-View mit allen Startdaten.

## Eingaben
- `Request $request`
- `config(...)`: Coldstore- oder Laufzeitkonfiguration
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: Blade-View mit den für die Seite benötigten Startdaten.

## Verwendete Abhängigkeiten
- `$this->coldstoreApiService->fetchOverview()`
- `$this->coldstoreAppSurfaceResolver->resolve()`
- `$this->coldstoreJobRepository->all()`
- `$this->jobMatchingService->payloadForLine()`
- `$this->lineWorkplaceMapper->all()`
- `$this->lineWorkplaceMapper->defaultLine()`
- `config()`
- `normalizedJobsRemoteApiBaseUrl()`
- `remoteApiInitialJobsPayload()`
- `route()`
- `view()`

## Fachliche Regeln
Bei `remote_api` startet die Jobs-Ansicht bewusst mit einem Lade-Payload statt sofortigen Jobdaten.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
