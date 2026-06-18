# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerProductionOrderRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Gibt für diese Auftragsquelle `sqlsrv` oder nach einem Laufzeit-Fallback `mock` zurück.

## Zweck im System
Die Methode macht sichtbar, ob der aktuelle Jobs-Datenfluss noch aus SQL stammt oder bereits auf Demo-Daten ausgewichen ist.

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
Der Rückgabewert spiegelt den tatsächlichen letzten Laufzeitpfad wider und nicht nur die Klassenart.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle; die Methode zeigt nur den bereits entschiedenen Zustand an.

## Relevanz für Erweiterungen
- Anpassen, wenn zusätzliche Herkunftsmodi oder detailliertere Diagnosedaten gebraucht werden.

## Nicht zuständig für
- Nicht zuständig für das Auslösen des Fallbacks selbst.
- Nicht zuständig für SQL-Abfragen oder Mapping.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- kritisch
- UI und Diagnose verlassen sich darauf, dass dieser Wert den echten Laufzeitpfad korrekt zeigt.
