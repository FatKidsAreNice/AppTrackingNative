# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Leitet einen kombinierten Source-Mode aus Produktions- und Inventarquelle ab.

## Zweck im System
Kennzeichnet für API und UI, aus welchen Datenquellen das Jobs-Payload stammt.

## Ablaufplan
1. Fragt die Source-Modes der beteiligten Repositories ab.
2. Gibt bei gleicher Quelle nur einen Wert zurück.
3. Kombiniert unterschiedliche Quellen mit `+`.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `$this->coldstoreInventoryRepository->sourceMode()`
- `$this->productionOrderRepository->sourceMode()`

## Fachliche Regeln
Unterschiedliche Datenquellen werden im Ergebnis sichtbar zusammengeführt.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Matching-Regeln oder das Jobs-Payload fachlich erweitert werden.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
