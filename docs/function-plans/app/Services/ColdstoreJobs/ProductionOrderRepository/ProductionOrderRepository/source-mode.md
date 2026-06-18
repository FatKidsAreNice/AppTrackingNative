# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/ProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\ProductionOrderRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Beschreibt auf Abstraktionsebene, wie eine Auftragsquelle ihren aktuellen Herkunftsmodus meldet.

## Zweck im System
Die Basisklasse schafft eine einheitliche Schnittstelle, über die der Jobs-Flow erkennen kann, ob Produktionsaufträge aus Mock, SQL oder einem Fallback-Pfad stammen.

## Ablaufplan
1. Definiert den Rückgabetyp und die fachliche Erwartung.
2. Überlässt die konkrete Herkunftslogik der jeweiligen Unterklasse.

## Eingaben
- Keine expliziten Parameter; die Methode arbeitet über den Status der konkreten Implementierung.

## Ausgaben
- Ein String, der die aktive Herkunft der Auftragsdaten beschreibt.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten in der abstrakten Definition.

## Fachliche Regeln
Die Basisklasse trifft keine eigene Source-Entscheidung, sondern setzt nur den Vertrag.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle in der abstrakten Definition sichtbar.

## Relevanz für Erweiterungen
- Anpassen, wenn zusätzliche Auftragsquellen oder neue Source-Mode-Werte eingeführt werden.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen oder Mock-Datenpflege.
- Nicht zuständig für Fallback-Entscheidungen konkreter Auftragsquellen.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen wirken sich direkt auf Diagnose und Transparenz der aktiven Auftragsquelle aus.
