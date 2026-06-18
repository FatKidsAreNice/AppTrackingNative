# allCurrent

## Ort
- Datei: `app/Services/ColdstoreJobs/MockColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockColdstoreInventoryRepository`
- Signatur: `allCurrent(): array`

## Kurzbeschreibung
Beschreibt die Schnittstelle für alle aktuell relevanten Inventar-Einträge.

## Zweck im System
Legt das erwartete Format fest, das die Matching-Logik von Inventarquellen verlangt.

## Ablaufplan
1. Definiert die Rückgabe aller aktuellen Inventarpositionen.
2. Überlässt die konkrete Beschaffung der Implementierung.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `demoUidForTrack()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

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
