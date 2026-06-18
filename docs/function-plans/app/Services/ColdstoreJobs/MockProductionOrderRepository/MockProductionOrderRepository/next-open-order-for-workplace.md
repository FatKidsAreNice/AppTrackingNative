# nextOpenOrderForWorkplace

## Ort
- Datei: `app/Services/ColdstoreJobs/MockProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockProductionOrderRepository`
- Signatur: `nextOpenOrderForWorkplace(int $workplaceNumber): ?array`

## Kurzbeschreibung
Beschreibt die Schnittstelle für den nächsten offenen Auftrag eines Arbeitsplatzes.

## Zweck im System
Definiert den kleinsten gemeinsamen Nenner für Auftragsquellen im Jobs-Flow.

## Ablaufplan
1. Nimmt die Arbeitsplatznummer entgegen.
2. Erwartet von der Implementierung den nächsten offenen Auftrag oder `null`.

## Eingaben
- `int $workplaceNumber`

## Ausgaben
- Rückgabe: optionaler Wert oder `null`.

## Verwendete Abhängigkeiten
- `openOrdersForWorkplace()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

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
