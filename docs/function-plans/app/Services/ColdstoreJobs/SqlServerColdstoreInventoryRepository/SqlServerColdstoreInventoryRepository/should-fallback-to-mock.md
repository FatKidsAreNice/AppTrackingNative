# shouldFallbackToMock

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `shouldFallbackToMock(Throwable $throwable): bool`

## Kurzbeschreibung
Entscheidet, ob ein Fehler als typischer SQL-Zugriffsfehler in den Mock-Modus überführt werden darf.

## Zweck im System
Die Methode verhindert, dass fachfremde Laufzeitprobleme still hinter einem Mock-Fallback verschwinden.

## Ablaufplan
1. Prüft, ob die Exception eine `QueryException` oder `PDOException` ist.
2. Gibt nur in diesen Fällen `true` zurück.

## Eingaben
- `Throwable $throwable`

## Ausgaben
- `true` für bekannte SQL-nahe Fehler, sonst `false`.

## Verwendete Abhängigkeiten
- `QueryException`
- `PDOException`

## Fachliche Regeln
Nur Datenbank- und PDO-nahe Fehler dürfen den Mock-Fallback auslösen.

## Fehlerfälle / Fallbacks
- Andere Fehlerarten werden bewusst nicht abgefangen und laufen nach oben weiter.

## Relevanz für Erweiterungen
- Anpassen, wenn zusätzliche SQL-Fehlertypen als tolerierbar eingestuft werden sollen.

## Nicht zuständig für
- Nicht zuständig für die Beschaffung des Fallback-Repositorys.
- Nicht zuständig für das Mappen erfolgreicher SQL-Ergebnisse.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Entscheidung steuert, ob SQL-Störungen sichtbar ausbrechen oder weich in Demo-Daten kippen.
