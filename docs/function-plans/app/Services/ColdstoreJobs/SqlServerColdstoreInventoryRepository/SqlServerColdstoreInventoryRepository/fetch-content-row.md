# fetchContentRow

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `fetchContentRow(string $uid): mixed`

## Kurzbeschreibung
Führt die eigentliche Einzelabfrage für eine UID gegen die konfigurierte SQL-Connection aus.

## Zweck im System
Die Methode trennt Query-Ausführung von Fehlerbehandlung und Mapping, damit `findCurrentContentByUid()` sich auf Fallback- und Domain-Logik konzentrieren kann.

## Ablaufplan
1. Trimmt die übergebene UID.
2. Holt die SQL-Zeichenkette aus `sql()`.
3. Führt `selectOne()` gegen die konfigurierte Connection aus.
4. Gibt die gefundene Zeile oder `null` zurück.

## Eingaben
- `string $uid`

## Ausgaben
- Die rohe Datenbankzeile oder `null`, abhängig vom Query-Ergebnis.

## Verwendete Abhängigkeiten
- `connection()`
- `sql()`

## Fachliche Regeln
Die Query arbeitet immer mit einer getrimmten UID.

## Fehlerfälle / Fallbacks
- Datenbankfehler werden hier nicht abgefangen, sondern an den aufrufenden Flow weitergegeben.

## Relevanz für Erweiterungen
- Anpassen, wenn UID-Lookup, Parameterbindung oder Einzelabfrageverhalten erweitert werden.

## Nicht zuständig für
- Nicht zuständig für das Mapping der SQL-Zeile.
- Nicht zuständig für die Entscheidung über Mock-Fallbacks.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Methode ist die direkte Datenbankkante für UID-basierte Inventarleseoperationen.
