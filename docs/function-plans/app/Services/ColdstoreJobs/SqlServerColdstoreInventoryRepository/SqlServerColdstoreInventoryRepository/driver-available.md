# driverAvailable

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `driverAvailable(): bool`

## Kurzbeschreibung
Prüft, ob die aktuelle PHP-Laufzeit SQL-Server-Abfragen für das Inventar technisch ausführen kann.

## Zweck im System
Die Methode schützt Container-Bindings und Startlogik davor, eine SQL-Inventarquelle zu aktivieren, obwohl keine kompatiblen Treiber installiert sind.

## Ablaufplan
1. Prüft die Erweiterungen `pdo_sqlsrv` und `sqlsrv`.
2. Prüft zusätzlich die von PDO gemeldeten Treibernamen.
3. Gibt die Verfügbarkeit als Bool zurück.

## Eingaben
- Keine.

## Ausgaben
- `true`, wenn mindestens ein kompatibler SQL-Server-Treiber gefunden wurde, sonst `false`.

## Verwendete Abhängigkeiten
- `extension_loaded()`
- `PDO::getAvailableDrivers()`

## Fachliche Regeln
Schon ein einzelner kompatibler SQL-Server-Treiber reicht für einen positiven Befund.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; bei fehlenden Treibern meldet die Methode schlicht `false`.

## Relevanz für Erweiterungen
- Anpassen, wenn weitere SQL-Server-Treiber oder alternative Prüfstrategien unterstützt werden sollen.

## Nicht zuständig für
- Nicht zuständig für den eigentlichen Verbindungsaufbau.
- Nicht zuständig für fachliche Inventarlogik.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Die Methode entscheidet mit darüber, ob produktive SQL-Inventarzugriffe überhaupt aktiviert werden dürfen.
