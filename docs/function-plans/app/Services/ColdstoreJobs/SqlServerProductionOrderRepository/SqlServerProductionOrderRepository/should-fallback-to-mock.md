# shouldFallbackToMock

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `shouldFallbackToMock(Throwable $throwable): bool`

## Kurzbeschreibung
Entscheidet, ob ein Fehler als typischer SQL-Zugriffsfehler in den Mock-Modus überführt werden darf.

## Zweck im System
Die Methode sorgt dafür, dass nur erwartete Datenbankprobleme weich abgefedert werden und nicht jeder Laufzeitfehler im Demo-Modus verschwindet.

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
- Anpassen, wenn weitere Fehlertypen als tolerierbar gelten sollen.

## Nicht zuständig für
- Nicht zuständig für die Auswahl des Fallback-Repositorys.
- Nicht zuständig für das Mapping erfolgreicher Query-Ergebnisse.

## Abhängige Tests
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Entscheidung steuert, ob Störungen im Auftragszugriff sichtbar ausbrechen oder auf Demo-Daten umschalten.
