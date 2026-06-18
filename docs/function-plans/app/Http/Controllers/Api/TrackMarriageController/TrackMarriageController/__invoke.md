# __invoke

## Ort
- Datei: `app/Http/Controllers/Api/TrackMarriageController.php`
- Klasse/Modul: `App\Http\Controllers\Api\TrackMarriageController`
- Signatur: `__invoke(StoreTrackMarriageRequest $request, ColdstoreApiService $coldstoreApiService): JsonResponse`

## Kurzbeschreibung
Nimmt eine manuelle UID-zu-Track-Zuordnung entgegen und gibt das Remote-Ergebnis als API-Antwort zurück.

## Zweck im System
Bedient den Marriage-Endpunkt für den Overview-zu-Scanner-Flow.

## Ablaufplan
1. Liest die validierten Zuordnungsdaten aus dem Request.
2. Übergibt sie an `ColdstoreApiService::assignTrackMarriage()`.
3. Fängt Remote-Ausfälle als `503 Service Unavailable` ab.
4. Gibt bei Erfolg den vom Remote-System zurückgemeldeten Status und Body weiter.

## Eingaben
- `StoreTrackMarriageRequest $request`
- `ColdstoreApiService $coldstoreApiService`
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: JSON-Response für einen HTTP-Endpunkt.

## Verwendete Abhängigkeiten
- `response()->json()`

## Fachliche Regeln
Laravel bestätigt den Commit nicht selbst, sondern übernimmt den Remote-Status.

## Fehlerfälle / Fallbacks
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
