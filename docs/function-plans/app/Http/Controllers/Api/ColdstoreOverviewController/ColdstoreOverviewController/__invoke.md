# __invoke

## Ort
- Datei: `app/Http/Controllers/Api/ColdstoreOverviewController.php`
- Klasse/Modul: `App\Http\Controllers\Api\ColdstoreOverviewController`
- Signatur: `__invoke(ColdstoreApiService $coldstoreApiService): JsonResponse`

## Kurzbeschreibung
Liefert die normalisierte Coldstore-Overview als JSON-Antwort zurück.

## Zweck im System
Bedient den Overview-Endpunkt für Karte, Track-Liste und Track-Details.

## Ablaufplan
1. Ruft `ColdstoreApiService::fetchOverview()` auf.
2. Erhält daraus das normalisierte Overview-Payload.
3. Gibt die Daten unverändert als JSON an das Frontend zurück.

## Eingaben
- `ColdstoreApiService $coldstoreApiService`

## Ausgaben
- Rückgabe: JSON-Response für einen HTTP-Endpunkt.

## Verwendete Abhängigkeiten
- `response()->json()`
- `view()`

## Fachliche Regeln
Die Normalisierung der Remote-Daten liegt vollständig im `ColdstoreApiService`.

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
