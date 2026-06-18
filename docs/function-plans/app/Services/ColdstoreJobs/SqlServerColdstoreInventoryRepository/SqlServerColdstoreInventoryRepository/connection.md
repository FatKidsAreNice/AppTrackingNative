# connection

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `connection(): ConnectionInterface`

## Kurzbeschreibung
Öffnet die für das Coldstore-Inventar konfigurierte SQL-Server-Verbindung.

## Zweck im System
Die Methode bündelt die Verbindungswahl für alle Inventar-Reads und sorgt dafür, dass SQL-Abfrage, Fallback-Flow und Tests denselben Zugangspfad verwenden.

## Ablaufplan
1. Prüft, ob ein Connection-Name über den Konstruktor gesetzt wurde.
2. Fällt sonst auf `coldstore.jobs.inventory.sqlsrv_connection` zurück.
3. Öffnet die Verbindung über `DB::connection()`.

## Eingaben
- Kein direkter Parameter; genutzt werden der interne Konstruktorwert und die Coldstore-Konfiguration.

## Ausgaben
- Eine `ConnectionInterface`-Instanz für Inventarabfragen.

## Verwendete Abhängigkeiten
- `DB::connection()`
- `config()`

## Fachliche Regeln
Ein explizit injizierter Connection-Name überschreibt die Standardkonfiguration.

## Fehlerfälle / Fallbacks
- Fehler beim Verbindungsaufbau werden nicht hier abgefangen, sondern erst in den aufrufenden SQL-Methoden bewertet.

## Relevanz für Erweiterungen
- Anpassen, wenn das Inventar künftig über mehrere SQL-Verbindungen oder eine andere Datenquelle verteilt wird.

## Nicht zuständig für
- Nicht zuständig für das Mapping von Inventarzeilen.
- Nicht zuständig für Mock-Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen verschieben die komplette Inventar-Lesekante dieser Klasse.
