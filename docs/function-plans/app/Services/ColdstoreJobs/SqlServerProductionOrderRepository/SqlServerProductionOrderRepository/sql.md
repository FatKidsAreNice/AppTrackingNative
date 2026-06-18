# sql

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `sql(): string`

## Kurzbeschreibung
Liefert die SQL-Abfrage für die frühesten offenen Produktionsaufträge eines Arbeitsplatzes inklusive Produkt- und `required_pe_text1`-Ableitung.

## Zweck im System
Die Methode definiert die zentrale fachliche Selektion der Aufträge, die im Jobs-Panel erscheinen dürfen.

## Ablaufplan
1. Selektiert offene Aufträge mit `VA_Status = 2`.
2. Verknüpft Stammdaten des eigentlichen Produkts.
3. Leitet per `OUTER APPLY` den `required_pe_text1`-Vergleichswert ab.
4. Holt optional den zugehörigen Produktnamen für diesen Schlüssel.
5. Sortiert nach geplantem Beginn und Auftrags-ID.

## Eingaben
- Keine direkten Parameter; die Parameterbindung erfolgt später in `fetchRows()`.

## Ausgaben
- Eine SQL-Zeichenkette für die Auswahl offener Produktionsaufträge.

## Verwendete Abhängigkeiten
- Keine direkten Laufzeitabhängigkeiten; die Methode liefert nur den Query-String.

## Fachliche Regeln
Die Query berücksichtigt nur offene Aufträge des angefragten Arbeitsplatzes.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; Ausführung und Fehlerbewertung passieren erst beim Query-Aufruf.

## Relevanz für Erweiterungen
- Anpassen, wenn Sortierung, Auftragsfilter oder die Ableitung von `required_pe_text1` fachlich geändert werden.

## Nicht zuständig für
- Nicht zuständig für die Query-Ausführung.
- Nicht zuständig für das Mapping der Ergebniszeilen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Query bestimmt unmittelbar, welche Aufträge die Coldstore-Oberfläche als relevant zeigt.
