# __construct

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `__construct( private LineWorkplaceMapper $lineWorkplaceMapper, private ProductionOrderRepository $productionOrderRepository, private ColdstoreInventoryRepository $coldstoreInventoryRepository, private EtikInterfaceLookupRepository $etikInterfaceLookupRepository, )`

## Kurzbeschreibung
Nimmt Mapper, Repositories und Lookup-Service für den Jobs-Matching-Flow entgegen.

## Zweck im System
Verdrahtet die zentrale Matching-Logik mit ihren Datenquellen und Hilfsdiensten.

## Ablaufplan
1. Erhält Linien-, Auftrags-, Inventar- und Lookup-Abhängigkeiten.
2. Speichert sie über Constructor Property Promotion im Service.
3. Stellt sie den Matching-Methoden zur Verfügung.

## Eingaben
- `private LineWorkplaceMapper $lineWorkplaceMapper`
- `private ProductionOrderRepository $productionOrderRepository`
- `private ColdstoreInventoryRepository $coldstoreInventoryRepository`
- `private EtikInterfaceLookupRepository $etikInterfaceLookupRepository`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Matching-Regeln oder das Jobs-Payload fachlich erweitert werden.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
