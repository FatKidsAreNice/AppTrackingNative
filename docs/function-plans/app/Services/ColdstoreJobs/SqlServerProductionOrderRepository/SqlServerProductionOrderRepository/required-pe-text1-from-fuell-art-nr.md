# requiredPeText1FromFuellArtNr

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `requiredPeText1FromFuellArtNr(string $matstammFuellArtNr): string`

## Kurzbeschreibung
Normalisiert eine Füllartnummer in denselben `required_pe_text1`-Schlüssel, den auch die SQL-Abfrage fachlich verwendet.

## Zweck im System
Die Methode hält PHP-seitige Vergleiche und die SQL-seitige Ableitung derselben Fachregel konsistent.

## Ablaufplan
1. Trimmt die übergebene Füllartnummer.
2. Gibt bei leerem Ergebnis einen leeren String zurück.
3. Wandelt ein führendes `F` in `9` um.
4. Gibt sonst den getrimmten Originalwert zurück.

## Eingaben
- `string $matstammFuellArtNr`

## Ausgaben
- Ein normalisierter Vergleichsschlüssel für `required_pe_text1`.

## Verwendete Abhängigkeiten
- `trim()`
- `str_starts_with()`
- `substr()`

## Fachliche Regeln
Ein führendes `F` wird fachlich immer zu `9` transformiert.

## Fehlerfälle / Fallbacks
- Leere Eingaben liefern bewusst einen leeren String statt eines Fehlers.

## Relevanz für Erweiterungen
- Anpassen, wenn sich die Füllart-zu-PEText1-Regel fachlich ändert.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen oder Produktnamen-Lookups.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese kleine Regel steuert, welche Inventar-UIDs fachlich zu einem Auftrag passen.
