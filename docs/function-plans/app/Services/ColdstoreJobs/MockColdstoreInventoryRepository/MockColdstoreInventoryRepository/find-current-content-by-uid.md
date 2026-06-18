# findCurrentContentByUid

## Ort
- Datei: `app/Services/ColdstoreJobs/MockColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockColdstoreInventoryRepository`
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
- `demoUidForTrack()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
