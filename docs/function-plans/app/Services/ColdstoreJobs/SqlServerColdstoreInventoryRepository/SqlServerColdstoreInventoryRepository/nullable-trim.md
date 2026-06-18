# nullableTrim

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `nullableTrim(mixed $value): ?string`

## Kurzbeschreibung
Trimmt optionale SQL-Feldwerte und lässt echte `null`-Werte unverändert.

## Zweck im System
Die Methode vereinheitlicht optionale Lagernamen und ähnliche Textfelder, bevor sie in das normalisierte Inventar-Array einfließen.

## Ablaufplan
1. Prüft den Eingabewert auf `null`.
2. Wandelt sonst den Wert in String um.
3. Gibt den getrimmten Inhalt zurück.

## Eingaben
- `mixed $value`

## Ausgaben
- `null` oder der getrimmte Stringwert.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten.

## Fachliche Regeln
Leere Strings werden hier nicht aktiv zu `null` umgewandelt; nur echte `null`-Werte bleiben `null`.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle sichtbar.

## Relevanz für Erweiterungen
- Anpassen, wenn optionale Inventarfelder künftig strenger normalisiert werden sollen.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen.
- Nicht zuständig für Domain-Entscheidungen rund um Fallback oder Eligibility.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- stabil
- Kleine Hilfsmethode mit Einfluss auf Textnormalisierung im Inventarpayload.
