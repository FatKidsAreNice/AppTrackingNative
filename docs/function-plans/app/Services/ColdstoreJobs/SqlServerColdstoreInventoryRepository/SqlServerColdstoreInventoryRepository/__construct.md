# __construct

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `__construct(private ?string $connectionName = null)`

## Kurzbeschreibung
Nimmt optional den Namen der SQL-Server-Verbindung für Inventarabfragen entgegen.

## Zweck im System
Der Konstruktor erlaubt Tests und Spezialumgebungen, die Coldstore-Inventarquelle gezielt auf eine andere SQL-Connection umzubiegen, ohne die Repository-Logik selbst zu ändern.

## Ablaufplan
1. Liest optional einen Connection-Namen aus dem Konstruktor.
2. Speichert ihn per Constructor Property Promotion im Repository.
3. Stellt ihn später `connection()` als Override der Standardkonfiguration bereit.

## Eingaben
- `?string $connectionName`

## Ausgaben
- Keine direkte Rückgabe; der Konstruktor setzt den internen Verbindungszustand.

## Verwendete Abhängigkeiten
- Keine direkten Laufzeitaufrufe; die Abhängigkeit wird später von `connection()` genutzt.

## Fachliche Regeln
Fehlt ein expliziter Connection-Name, fällt die Klasse auf `coldstore.jobs.inventory.sqlsrv_connection` zurück.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle sichtbar; die eigentliche Verbindungsprüfung passiert erst beim Datenzugriff.

## Relevanz für Erweiterungen
- Anpassen, wenn weitere Verbindungsparameter oder eine andere Konfigurationsstrategie nötig werden.

## Nicht zuständig für
- Nicht zuständig für das Öffnen der Datenbankverbindung selbst.
- Nicht zuständig für SQL-Fallback-Entscheidungen.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen beeinflussen direkt, gegen welche Inventarquelle die Klasse später liest.
