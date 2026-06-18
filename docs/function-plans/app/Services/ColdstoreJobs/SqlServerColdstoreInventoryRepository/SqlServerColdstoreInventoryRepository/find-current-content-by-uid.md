# findCurrentContentByUid

## Ort
- Datei: `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\SqlServerColdstoreInventoryRepository`
- Signatur: `findCurrentContentByUid(string $uid): ?array`

## Kurzbeschreibung
Beschreibt die Schnittstelle zum aktuellen Schrankinhalt einer UID.

## Zweck im System
Trennt die fachliche Frage „was ist aktuell im Schrank?“ von der konkreten Datenquelle.

## Ablaufplan
1. Nimmt die UID entgegen.
2. Erwartet von der Implementierung den aktuellen Inhalt oder `null`.

## Eingaben
- `string $uid`

## Ausgaben
- Rückgabe: optionaler Wert oder `null`.

## Verwendete Abhängigkeiten
- `fallbackRepository()`
- `fetchContentRow()`
- `mapContentRow()`
- `shouldFallbackToMock()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.
- Gibt bei fehlenden oder nicht verwertbaren Daten bewusst `null` zurück.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn sich SQL-Quelle, Row-Mapping oder Fallback-Regeln ändern.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
