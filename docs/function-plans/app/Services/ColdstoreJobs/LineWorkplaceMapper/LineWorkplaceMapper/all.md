# all

## Ort
- Datei: `app/Services/ColdstoreJobs/LineWorkplaceMapper.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\LineWorkplaceMapper`
- Signatur: `all(): array`

## Kurzbeschreibung
Liefert die komplette Linien-zu-Arbeitsplatz-Liste inklusive Labels für die UI.

## Zweck im System
Stellt Dashboard und Jobs-Endpunkt dieselbe zentrale Linienkonfiguration bereit.

## Ablaufplan
1. Liest das interne Mapping.
2. Formt daraus eine Liste von Linienobjekten.
3. Gibt die Liste für die UI zurück.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- `mapping()`

## Fachliche Regeln
Linien und Arbeitsplatznummern werden zentral in einer Mapping-Tabelle gepflegt.

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
