# openOrdersForWorkplace

## Ort
- Datei: `app/Services/ColdstoreJobs/MockProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockProductionOrderRepository`
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
- `orders()`

## Fachliche Regeln
Das Dashboard erwartet höchstens aktuellen und Folgeauftrag.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
