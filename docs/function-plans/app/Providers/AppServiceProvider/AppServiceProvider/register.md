# register

## Ort
- Datei: `app/Providers/AppServiceProvider.php`
- Klasse/Modul: `App\Providers\AppServiceProvider`
- Signatur: `register(): void`

## Kurzbeschreibung
Bindet Produktionsauftrags- und Inventar-Repositories abhängig von der Coldstore-Konfiguration an den Service-Container.

## Zweck im System
Steuert zentral, ob Mock- oder SQL-Implementierungen im Jobs-Flow verwendet werden.

## Ablaufplan
1. Bindet `ProductionOrderRepository` konfigurationsabhängig an Mock oder SQL-Server.
2. Prüft dabei, ob der jeweilige SQL-Treiber verfügbar ist.
3. Bindet anschließend `ColdstoreInventoryRepository` nach demselben Muster.
4. Fällt bei unbekannten oder nicht lauffähigen Konfigurationen auf Mock-Implementierungen zurück.

## Eingaben
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `$this->app->bind()`
- `SqlServerColdstoreInventoryRepository::driverAvailable()`
- `SqlServerProductionOrderRepository::driverAvailable()`
- `config()`

## Fachliche Regeln
Nicht verfügbare SQL-Treiber führen bewusst zum Mock-Fallback.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
