# connection

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `connection(): ConnectionInterface`

## Kurzbeschreibung
Öffnet die für Produktionsaufträge konfigurierte SQL-Server-Verbindung.

## Zweck im System
Die Methode bündelt den Konfigurationszugriff für alle Auftragsqueries und sorgt dafür, dass Leseoperationen, Fallbacks und Tests denselben Zugangspfad verwenden.

## Ablaufplan
1. Prüft, ob ein Connection-Name über den Konstruktor gesetzt wurde.
2. Fällt sonst auf `coldstore.jobs.production_orders.sqlsrv_connection` zurück.
3. Öffnet die Verbindung über `DB::connection()`.

## Eingaben
- Keine direkten Parameter; verwendet werden der interne Konstruktorwert und die Coldstore-Konfiguration.

## Ausgaben
- Eine `ConnectionInterface`-Instanz für SQL-Auftragsabfragen.

## Verwendete Abhängigkeiten
- `DB::connection()`
- `config()`

## Fachliche Regeln
Ein im Konstruktor gesetzter Connection-Name überschreibt die Standardkonfiguration.

## Fehlerfälle / Fallbacks
- Fehler beim Verbindungsaufbau werden hier nicht abgefangen, sondern im Query-Flow der aufrufenden Methoden bewertet.

## Relevanz für Erweiterungen
- Anpassen, wenn Produktionsaufträge künftig über mehrere Verbindungen oder andere Datenquellen verteilt werden.

## Nicht zuständig für
- Nicht zuständig für das Mapping der Auftragsdaten.
- Nicht zuständig für Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Methode verschiebt bei Änderungen die komplette Datenbankkante der Auftragsquelle.
