# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/MockColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockColdstoreInventoryRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Kennzeichnet diese Inventarquelle immer als Mock-Betrieb.

## Zweck im System
Die Methode macht für UI und API transparent, dass die Inventardaten aus einer statischen Demoquelle und nicht aus SQL oder einem Remote-System stammen.

## Ablaufplan
1. Gibt ohne weitere Prüfung den String `mock` zurück.

## Eingaben
- Keine.

## Ausgaben
- Der feste Source-Mode `mock`.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten.

## Fachliche Regeln
Der Source-Mode ist hier fest verdrahtet und hängt nicht von Laufzeitfehlern oder Fallbacks ab.

## Fehlerfälle / Fallbacks
- Keine; die Methode liefert immer denselben Wert.

## Relevanz für Erweiterungen
- Anpassen, wenn diese Demoquelle später zwischen mehreren Mock-Modi unterscheiden soll.

## Nicht zuständig für
- Nicht zuständig für das Laden oder Mappen einzelner Inventardaten.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- stabil
- Die Methode ist klein, aber wichtig für die Herkunftskennzeichnung.
