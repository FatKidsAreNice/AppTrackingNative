# __invoke

## Ort
- Datei: `app/Http/Controllers/Api/ColdstoreJobsController.php`
- Klasse/Modul: `App\Http\Controllers\Api\ColdstoreJobsController`
- Signatur: `__invoke(ShowColdstoreJobsRequest $request, JobMatchingService $jobMatchingService): JsonResponse`

## Kurzbeschreibung
Liefert das Jobs-Payload für die angefragte Linie als JSON-Antwort zurück.

## Zweck im System
Bedient den Jobs-Endpunkt zwischen Linienauswahl und Dashboard-UI.

## Ablaufplan
1. Liest die validierte `selected_line` aus dem Request.
2. Übergibt die Linie an `JobMatchingService::payloadForLine()`.
3. Gibt das erzeugte Jobs-Payload als JSON aus.

## Eingaben
- `ShowColdstoreJobsRequest $request`
- `JobMatchingService $jobMatchingService`
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: JSON-Response für einen HTTP-Endpunkt.

## Verwendete Abhängigkeiten
- `response()->json()`

## Fachliche Regeln
Die Linienauswahl muss bereits durch `ShowColdstoreJobsRequest` freigegeben worden sein.

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
