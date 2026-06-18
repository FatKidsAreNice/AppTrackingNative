# mapContentRow

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `mapContentRow(stdClass $row): array`

## Kurzbeschreibung
Normalisiert eine SQL-Zeile aus Chargen-, Lager- und EtikInterface-Daten in das erwartete Inventarformat.

## Zweck im System
Die Methode entkoppelt SQL-Spaltennamen vom stabilen Array-Schema, das Jobs-Flow und Matching-Logik weiterverwenden.

## Ablaufplan
1. Trimmt UID- und PEText1-Felder.
2. Konvertiert Gewichts- und Lager-IDs in numerische Werte.
3. Normalisiert optionale Lagernamen über `nullableTrim()`.
4. Formt `last_booking` in einen Bool um.

## Eingaben
- `stdClass $row`

## Ausgaben
- Ein normalisiertes Inventar-Array mit UID-, Material-, Lager- und Buchungsinformationen.

## Verwendete Abhängigkeiten
- `nullableTrim()`

## Fachliche Regeln
Nur das normalisierte Coldstore-Inventarformat darf die Repository-Grenze verlassen.

## Fehlerfälle / Fallbacks
- Erwartet eine bereits gefundene SQL-Zeile; Fehlerbehandlung findet vorher statt.

## Relevanz für Erweiterungen
- Anpassen, wenn neue SQL-Spalten aufgenommen oder API-Schlüssel des Inventars geändert werden.

## Nicht zuständig für
- Nicht zuständig für die Datenbankabfrage selbst.
- Nicht zuständig für Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen wirken direkt auf das Format, das nach außen an Matching und Jobs fließt.
