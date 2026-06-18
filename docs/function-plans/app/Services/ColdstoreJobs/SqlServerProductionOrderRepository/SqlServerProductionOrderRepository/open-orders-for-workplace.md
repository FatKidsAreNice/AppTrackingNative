# openOrdersForWorkplace

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `openOrdersForWorkplace(int $workplaceNumber, int $limit = 2): array`

## Kurzbeschreibung
Liefert mehrere offene Aufträge für einen Arbeitsplatz.

## Zweck im System
Versorgt den Jobs-Flow mit aktuellem und folgendem Auftrag in einem einheitlichen Format.

## Ablaufplan
1. Nutzt `nextOpenOrderForWorkplace()` oder eine konkrete Mehrfachabfrage.
2. Begrenzt die Anzahl auf das gewünschte Limit.
3. Gibt die Auftragsliste zurück.

## Eingaben
- `int $workplaceNumber`
- `int $limit = 2`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- `fallbackRepository()`
- `fetchRows()`
- `mapRow()`
- `shouldFallbackToMock()`

## Fachliche Regeln
Das Dashboard erwartet höchstens aktuellen und Folgeauftrag.

## Fehlerfälle / Fallbacks
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn sich SQL-Quelle, Row-Mapping oder Fallback-Regeln ändern.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
