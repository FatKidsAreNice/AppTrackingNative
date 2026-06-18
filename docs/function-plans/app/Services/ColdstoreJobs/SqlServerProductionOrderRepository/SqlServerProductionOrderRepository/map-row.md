# mapRow

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `mapRow(stdClass $row): array`

## Kurzbeschreibung
Mappt eine SQL-Auftragszeile in das stabile Payload-Format des Coldstore-Jobs-Flows.

## Zweck im System
Die Methode entkoppelt die SQL-Spaltennamen aus `VA` und `MatStamm` von den JSON- und UI-Schlüsseln, die im Dashboard weiterverwendet werden.

## Ablaufplan
1. Konvertiert IDs, Status und Mengen in passende Grundtypen.
2. Trimmt Textfelder wie Auftragsnummer, Materialnummer und Füllartnummer.
3. Normalisiert optionale Felder über `nullableTrim()`.
4. Gibt das fertige Auftragsarray zurück.

## Eingaben
- `stdClass $row`

## Ausgaben
- Ein normalisiertes Auftragsarray für Dashboard und Jobs-API.

## Verwendete Abhängigkeiten
- `nullableTrim()`

## Fachliche Regeln
Nur die normalisierte Struktur wird an den restlichen Jobs-Flow weitergereicht.

## Fehlerfälle / Fallbacks
- Erwartet eine bereits erfolgreich geladene SQL-Zeile; Fehlerbehandlung passiert vorher.

## Relevanz für Erweiterungen
- Anpassen, wenn neue Auftragsfelder oder geänderte Payload-Schlüssel eingeführt werden.

## Nicht zuständig für
- Nicht zuständig für die SQL-Abfrage selbst.
- Nicht zuständig für Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen wirken direkt auf das Format, das Jobs-API und UI konsumieren.
