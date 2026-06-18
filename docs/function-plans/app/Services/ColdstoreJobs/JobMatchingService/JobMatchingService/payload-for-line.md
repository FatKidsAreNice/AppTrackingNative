# payloadForLine

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `payloadForLine(int $selectedLine): array`

## Kurzbeschreibung
Baut das Jobs-API-Payload für eine ausgewählte Linie aus Arbeitsplatz, Auftragsdaten und passenden UIDs auf.

## Zweck im System
Unterstützt den Coldstore-Jobs-Endpunkt als zentrale Aggregationsfunktion.

## Ablaufplan
1. Liest die Ziel-Linie.
2. Ermittelt den Arbeitsplatz über den Linien-Mapping-Service.
3. Lädt bis zu zwei offene Aufträge.
4. Normalisiert den aktuellen und optional den Folgeauftrag.
5. Sucht passende Inventar-UIDs für beide Aufträge.
6. Gibt ein vollständiges Payload für die Jobs-UI zurück.

## Eingaben
- `int $selectedLine`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `$this->lineWorkplaceMapper->workplaceNumberForLine()`
- `$this->productionOrderRepository->openOrdersForWorkplace()`
- `matchingInventory()`
- `normalizeOrder()`
- `sourceMode()`

## Fachliche Regeln
Die Matching-Logik bleibt an `ColdstoreInventoryRepository` gebunden.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Matching-Regeln oder das Jobs-Payload fachlich erweitert werden.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
