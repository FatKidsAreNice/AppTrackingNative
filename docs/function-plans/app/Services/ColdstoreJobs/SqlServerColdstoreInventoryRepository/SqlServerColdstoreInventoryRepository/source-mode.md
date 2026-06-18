# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Gibt für diese Inventarquelle `sqlsrv` oder nach einem Laufzeit-Fallback `mock` zurück.

## Zweck im System
Die Methode spiegelt nach außen, ob die letzte tatsächliche Inventarleseoperation noch aus SQL stammt oder bereits auf Mock-Daten ausgewichen ist.

## Ablaufplan
1. Prüft die interne Flagge `$usedFallback`.
2. Gibt `mock` bei aktiviertem Fallback, sonst `sqlsrv` zurück.

## Eingaben
- Keine direkten Parameter; genutzt wird der interne Laufzeitstatus des Repositorys.

## Ausgaben
- `sqlsrv` oder `mock`, abhängig vom zuletzt genutzten Pfad.

## Verwendete Abhängigkeiten
- Interner Zustand `$usedFallback`

## Fachliche Regeln
Der Rückgabewert hängt vom tatsächlichen Laufzeitpfad und nicht nur von der Klassenart ab.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; die Methode zeigt nur den bereits entschiedenen Zustand an.

## Relevanz für Erweiterungen
- Anpassen, wenn zusätzliche Betriebsmodi oder detailliertere Herkunftsinformationen benötigt werden.

## Nicht zuständig für
- Nicht zuständig für das Auslösen des Fallbacks selbst.
- Nicht zuständig für Inventarabfragen oder Mapping.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- UI und Diagnose verlassen sich darauf, dass dieser Wert den echten Laufzeitpfad korrekt spiegelt.
