# nullableTrim

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `nullableTrim(mixed $value): ?string`

## Kurzbeschreibung
Trimmt optionale SQL-Spaltenwerte und gibt `null` nur dann zurück, wenn der Ursprungswert bereits `null` war.

## Zweck im System
Die Methode vereinheitlicht optionale Zeit- und Produktfelder, bevor sie in das normalisierte Jobs-Payload einfließen.

## Ablaufplan
1. Prüft den Eingabewert auf `null`.
2. Wandelt sonst in String um.
3. Gibt den getrimmten Wert zurück.

## Eingaben
- `mixed $value`

## Ausgaben
- `null` oder der getrimmte Stringwert.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten.

## Fachliche Regeln
Die Methode löscht keine leeren Strings aktiv weg, sondern trimmt nur.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle sichtbar.

## Relevanz für Erweiterungen
- Anpassen, wenn optionale Auftragsfelder künftig strenger normalisiert werden sollen.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen.
- Nicht zuständig für fachliche Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- stabil
- Kleine Hilfsmethode mit Einfluss auf die String-Normalisierung im Jobs-Payload.
