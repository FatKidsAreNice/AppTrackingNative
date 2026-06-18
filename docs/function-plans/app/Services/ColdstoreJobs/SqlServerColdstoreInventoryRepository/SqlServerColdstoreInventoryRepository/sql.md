# sql

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `sql(): string`

## Kurzbeschreibung
Liefert die SQL-Abfrage, die den letzten bekannten Buchungsstand einer UID aus Chargen- und Lagerdaten liest.

## Zweck im System
Die Methode definiert die produktive Inventarlese-Regel für einen einzelnen Schrank beziehungsweise eine UID.

## Ablaufplan
1. Selektiert die Chargenstück-UID samt Netto-Gewicht und Lagerwechseln.
2. Verknüpft Quell- und Ziellager per `LEFT JOIN`.
3. Holt optional `EtikInterface_PEText1` für die UID dazu.
4. Beschränkt die Suche auf `last_booking = 1` und genau eine UID.

## Eingaben
- Keine direkten Parameter; die Parameterbindung erfolgt später in `fetchContentRow()`.

## Ausgaben
- Eine SQL-Zeichenkette für die Einzelabfrage auf Inventarinhalte.

## Verwendete Abhängigkeiten
- Keine direkten Laufzeitabhängigkeiten; die Methode liefert nur den Query-String.

## Fachliche Regeln
Es wird bewusst nur der letzte Buchungsstand der angefragten UID berücksichtigt.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; Ausführung und Fehlerbewertung passieren erst beim Query-Aufruf.

## Relevanz für Erweiterungen
- Anpassen, wenn zusätzliche Lagerfelder, andere Filter oder eine geänderte Inventardefinition nötig werden.

## Nicht zuständig für
- Nicht zuständig für die Query-Ausführung.
- Nicht zuständig für das Mapping der Ergebniszeile.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Die fachliche SQL-Selektion bestimmt unmittelbar, welcher Inventarstand als „aktuell“ gilt.
