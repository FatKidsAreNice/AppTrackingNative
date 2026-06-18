# __construct

## Ort
- Datei: `app/Http/Controllers/ColdstoreDashboardController.php`
- Klasse/Modul: `App\Http\Controllers\ColdstoreDashboardController`
- Signatur: `__construct( private ColdstoreApiService $coldstoreApiService, private ColdstoreJobRepository $coldstoreJobRepository, private JobMatchingService $jobMatchingService, private LineWorkplaceMapper $lineWorkplaceMapper, private ColdstoreAppSurfaceResolver $coldstoreAppSurfaceResolver, )`

## Kurzbeschreibung
Nimmt die zentralen Services und Resolver entgegen, die Dashboard, Jobs, Overview und Scanner versorgen.

## Zweck im System
Verdrahtet den Controller mit den Coldstore-Abhängigkeiten für die verschiedenen Ansichten.

## Ablaufplan
1. Erhält Services für Overview, Jobs, Linien und Oberflächenauflösung.
2. Speichert sie über Constructor Property Promotion im Controller.
3. Stellt sie den View-Methoden des Controllers zur Verfügung.

## Eingaben
- `private ColdstoreApiService $coldstoreApiService`
- `private ColdstoreJobRepository $coldstoreJobRepository`
- `private JobMatchingService $jobMatchingService`
- `private LineWorkplaceMapper $lineWorkplaceMapper`
- `private ColdstoreAppSurfaceResolver $coldstoreAppSurfaceResolver`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
