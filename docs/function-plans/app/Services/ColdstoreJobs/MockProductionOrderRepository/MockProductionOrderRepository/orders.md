# orders

## Ort
- Datei: `app/Services/ColdstoreJobs/MockProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockProductionOrderRepository`
- Signatur: `orders(): array`

## Kurzbeschreibung
Enthält die vollständige statische Mock-Auftragsmatrix für alle unterstützten Arbeitsplätze.

## Zweck im System
Die Methode bildet den Kern der Demo-Auftragsquelle, aus der Dashboard und Jobs-Endpunkt ihre reproduzierbaren Beispielaufträge beziehen.

## Ablaufplan
1. Baut pro Arbeitsplatz eine Liste offener Aufträge auf.
2. Hinterlegt für einzelne Linien auch Folgeaufträge.
3. Gibt die gesamte Matrix als Array zurück.

## Eingaben
- Keine externen Parameter; die Daten sind vollständig im Code hinterlegt.

## Ausgaben
- Ein Array, das Arbeitsplatznummern auf Listen offener Demo-Aufträge abbildet.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Die Daten sind Demo-Fachdaten und absichtlich nicht mit Live-Systemen synchronisiert.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle; fehlende Arbeitsplätze werden erst in den aufrufenden Methoden als leere Liste behandelt.

## Relevanz für Erweiterungen
- Anpassen, wenn neue Demo-Linien, weitere Folgeaufträge oder zusätzliche Auftragsfelder benötigt werden.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen oder Remote-Zugriffe.
- Nicht zuständig für die Auswahl eines konkreten Arbeitsplatzes.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- vorsichtig ändern
- Änderungen verschieben sofort das sichtbare Demo-Verhalten des gesamten Jobs-Flows.
