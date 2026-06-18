# fetchRows

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `fetchRows(int $workplaceNumber, int $limit = 2): array`

## Kurzbeschreibung
Führt die SQL-Abfrage für offene Aufträge eines Arbeitsplatzes gegen die konfigurierte Connection aus.

## Zweck im System
Die Methode isoliert Query-Ausführung und Parameterbindung vom darüberliegenden Fehler- und Mapping-Flow.

## Ablaufplan
1. Holt die SQL-Zeichenkette aus `sql()`.
2. Bindet die Arbeitsplatznummer als Parameter.
3. Führt `select()` gegen die konfigurierte Connection aus.
4. Gibt die Rohzeilen zurück.

## Eingaben
- `int $workplaceNumber`
- `int $limit = 2`

## Ausgaben
- Eine Liste roher SQL-Zeilen als `stdClass`-Objekte.

## Verwendete Abhängigkeiten
- `connection()`
- `sql()`

## Fachliche Regeln
Der Parameter `$limit` begrenzt die SQL-Abfrage selbst aktuell nicht; die Query ist auf `TOP (2)` festgelegt und die endgültige Begrenzung erfolgt im PHP-Flow darüber.

## Fehlerfälle / Fallbacks
- Datenbankfehler werden hier nicht abgefangen, sondern an `openOrdersForWorkplace()` weitergereicht.

## Relevanz für Erweiterungen
- Anpassen, wenn das Limit direkt in die Query integriert oder weitere Filter eingeführt werden.

## Nicht zuständig für
- Nicht zuständig für das Mapping der SQL-Zeilen.
- Nicht zuständig für Mock-Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Methode ist die direkte Datenbankkante für offene Produktionsaufträge.
