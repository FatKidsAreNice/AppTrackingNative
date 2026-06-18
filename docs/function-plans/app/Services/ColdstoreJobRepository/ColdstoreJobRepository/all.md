# all

## Ort
- Datei: `app/Services/ColdstoreJobRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobRepository`
- Signatur: `all(): array`

## Kurzbeschreibung
Liefert die statische Demo-Liste der Coldstore-Jobs für einfache Dashboard-Darstellungen.

## Zweck im System
Versorgt die Oberfläche mit einer kleinen, lokalen Beispielquelle außerhalb des eigentlichen Matching-Flows.

## Ablaufplan
1. Baut die Demo-Jobliste im Code auf.
2. Gibt die Liste als Array zurück.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Die enthaltenen Werte sind Demo-Daten und keine Live-Produktionsdaten.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Feature/ColdstoreDashboardTest.php`
- `tests/Feature/ColdstoreOverviewApiTest.php`
- `tests/Feature/ProductionOrderRepositoryBindingTest.php`
- `tests/Unit/ColdstoreJobRepositoryTest.php`
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/LineWorkplaceMapperTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`
- `tests/Unit/SqlServerProductionOrderRepositoryTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
