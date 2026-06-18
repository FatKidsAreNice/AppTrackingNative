# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/MockProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockProductionOrderRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Kennzeichnet diese Auftragsquelle immer als Mock-Betrieb.

## Zweck im System
Die Methode signalisiert Dashboard, API und Diagnosehilfen, dass die aktuell angezeigten Produktionsaufträge aus fest hinterlegten Demo-Daten stammen.

## Ablaufplan
1. Gibt ohne weitere Prüfung den String `mock` zurück.

## Eingaben
- Keine.

## Ausgaben
- Der feste Source-Mode `mock`.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten.

## Fachliche Regeln
Diese Implementierung kennt keinen dynamischen Wechsel auf SQL oder andere Quellen.

## Fehlerfälle / Fallbacks
- Keine; die Rückgabe ist konstant.

## Relevanz für Erweiterungen
- Anpassen, wenn der Mock-Betrieb später in mehrere Demo-Modi aufgeteilt werden soll.

## Nicht zuständig für
- Nicht zuständig für das Laden oder Filtern einzelner Aufträge.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- stabil
- Die Methode ist klein, aber wichtig für eine klare Herkunftsanzeige.
