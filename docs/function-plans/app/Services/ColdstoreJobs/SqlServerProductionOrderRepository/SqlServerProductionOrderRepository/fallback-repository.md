# fallbackRepository

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `fallbackRepository(): ProductionOrderRepository`

## Kurzbeschreibung
Erzeugt die Mock-Auftragsquelle als sicheren Ausweichpfad für SQL-nahe Fehler.

## Zweck im System
Die Methode hält das Jobs-Dashboard auch dann funktionsfähig, wenn Produktionsaufträge nicht sauber aus SQL gelesen werden können.

## Ablaufplan
1. Fragt den Service-Container nach `MockProductionOrderRepository`.
2. Gibt diese Mock-Implementierung an den Aufrufer zurück.

## Eingaben
- Keine direkten Parameter; genutzt wird der Service-Container.

## Ausgaben
- Ein `ProductionOrderRepository`, konkret die Mock-Implementierung.

## Verwendete Abhängigkeiten
- `app()`

## Fachliche Regeln
Der Fallback ist fest mit der Mock-Auftragsquelle gekoppelt.

## Fehlerfälle / Fallbacks
- Ist das Container-Binding defekt, scheitert auch die Fallback-Beschaffung.

## Relevanz für Erweiterungen
- Anpassen, wenn statt der Demo-Daten eine andere Ersatzquelle verwendet werden soll.

## Nicht zuständig für
- Nicht zuständig für die Entscheidung, wann ein Fallback erlaubt ist.
- Nicht zuständig für das Mapping einzelner SQL-Zeilen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Wahl bestimmt das sichtbare Verhalten des Jobs-Flows bei SQL-Störungen.
