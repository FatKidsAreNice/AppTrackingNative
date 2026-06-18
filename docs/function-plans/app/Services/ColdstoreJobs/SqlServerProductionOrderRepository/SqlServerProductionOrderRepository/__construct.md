# __construct

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `__construct(private ?string $connectionName = null)`

## Kurzbeschreibung
Nimmt optional den Namen der SQL-Server-Verbindung für Produktionsauftragsabfragen entgegen.

## Zweck im System
Der Konstruktor erlaubt Tests und Spezialumgebungen, die Auftragsquelle auf eine andere SQL-Connection umzubiegen, ohne die Repository-Logik umzuschreiben.

## Ablaufplan
1. Liest optional einen Connection-Namen aus dem Konstruktor.
2. Speichert ihn per Constructor Property Promotion im Repository.
3. Stellt ihn später `connection()` als Override der Standardverbindung bereit.

## Eingaben
- `?string $connectionName`

## Ausgaben
- Keine direkte Rückgabe; der Konstruktor setzt den internen Verbindungszustand.

## Verwendete Abhängigkeiten
- Keine direkten Laufzeitaufrufe im Konstruktor.

## Fachliche Regeln
Ohne expliziten Parameter wird `coldstore.jobs.production_orders.sqlsrv_connection` verwendet.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle sichtbar; die eigentliche Verbindungsprüfung folgt erst beim SQL-Zugriff.

## Relevanz für Erweiterungen
- Anpassen, wenn weitere Verbindungsparameter oder unterschiedliche Auftragsquellen injiziert werden sollen.

## Nicht zuständig für
- Nicht zuständig für SQL-Abfragen selbst.
- Nicht zuständig für Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen beeinflussen direkt, gegen welche Auftragsquelle diese Klasse später arbeitet.
