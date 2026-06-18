# driverAvailable

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `driverAvailable(): bool`

## Kurzbeschreibung
Prüft, ob die aktuelle PHP-Laufzeit SQL-Server-Abfragen für Produktionsaufträge technisch ausführen kann.

## Zweck im System
Die Methode verhindert, dass eine nicht lauffähige SQL-Auftragsquelle im Container aktiviert wird.

## Ablaufplan
1. Prüft die Erweiterungen `pdo_sqlsrv` und `sqlsrv`.
2. Prüft zusätzlich die von PDO gemeldeten Treiber.
3. Gibt die Verfügbarkeit als Bool zurück.

## Eingaben
- Keine.

## Ausgaben
- `true`, wenn mindestens ein kompatibler SQL-Server-Treiber gefunden wurde, sonst `false`.

## Verwendete Abhängigkeiten
- `extension_loaded()`
- `PDO::getAvailableDrivers()`

## Fachliche Regeln
Schon ein einzelner kompatibler Treiber reicht für einen positiven Befund.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; bei fehlender Unterstützung meldet die Methode schlicht `false`.

## Relevanz für Erweiterungen
- Anpassen, wenn weitere SQL-Server-Treiber oder andere Verfügbarkeitsprüfungen relevant werden.

## Nicht zuständig für
- Nicht zuständig für den eigentlichen Verbindungsaufbau.
- Nicht zuständig für fachliche Auftragslogik.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Die Methode entscheidet mit darüber, ob produktive SQL-Auftragsdaten überhaupt aktiviert werden dürfen.
