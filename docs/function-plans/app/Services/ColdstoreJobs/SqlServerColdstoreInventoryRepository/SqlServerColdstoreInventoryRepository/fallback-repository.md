# fallbackRepository

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `fallbackRepository(): ColdstoreInventoryRepository`

## Kurzbeschreibung
Erzeugt das Mock-Inventar-Repository als Ausweichquelle für SQL-nahe Fehlerfälle.

## Zweck im System
Die Methode hält den Jobs-Flow auch dann lesbar, wenn Inventar-Queries oder der SQL-Treiber nicht sauber arbeiten.

## Ablaufplan
1. Fragt den Service-Container nach `MockColdstoreInventoryRepository`.
2. Gibt diese Implementierung an den Aufrufer zurück.

## Eingaben
- Keine direkten Parameter; genutzt wird der Service-Container der Anwendung.

## Ausgaben
- Ein `ColdstoreInventoryRepository`, konkret die Mock-Implementierung.

## Verwendete Abhängigkeiten
- `app()`

## Fachliche Regeln
Der Fallback ist fest mit der Mock-Inventarquelle verdrahtet.

## Fehlerfälle / Fallbacks
- Ist der Container-Binding defekt, schlägt auch die Fallback-Beschaffung fehl.

## Relevanz für Erweiterungen
- Anpassen, wenn statt der Mock-Quelle ein anderer Ersatzpfad genutzt werden soll.

## Nicht zuständig für
- Nicht zuständig für die Entscheidung, wann überhaupt ein Fallback erlaubt ist.
- Nicht zuständig für das Mappen einzelner Inventardatensätze.

## Abhängige Tests
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Der gewählte Ersatzpfad bestimmt das sichtbare Verhalten bei SQL-Störungen.
