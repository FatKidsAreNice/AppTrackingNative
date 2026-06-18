# Extension Map

## Neue Jobs-UI-Funktion hinzufügen
Relevante Stellen:
- `app/Http/Controllers/ColdstoreDashboardController.php`
- `app/Services/ColdstoreJobs/JobMatchingService.php`
- `resources/js/coldstore-app.js`
Warum:
- Der Controller liefert die Startdaten, der Matching-Service baut das fachliche Jobs-Payload und das Frontend rendert daraus Übersicht, Detailpanel und Interaktionen.

## Neue Matching-Regel hinzufügen
Relevante Stellen:
- `app/Services/ColdstoreJobs/JobMatchingService.php`
- `app/Services/ColdstoreJobs/ColdstoreInventoryRepository.php`
- konkrete Inventory-Repositories unter `app/Services/ColdstoreJobs/`
Warum:
- Materialvergleich, UID-Trefferliste und die Trennung zwischen abstrakter Schnittstelle und konkreter Inventarquelle liegen genau in diesen Schichten.

## Neue SQL-Quelle anbinden
Relevante Stellen:
- `app/Providers/AppServiceProvider.php`
- `app/Services/ColdstoreJobs/SqlServerProductionOrderRepository.php`
- `app/Services/ColdstoreJobs/SqlServerColdstoreInventoryRepository.php`
- `app/Support/SqlServerConnectionConfig.php`
Warum:
- Hier werden Container-Bindings, Treiberverfügbarkeit, Verbindungsoptionen, SQL-Abfragen, Row-Mapping und Mock-Fallbacks gesteuert.

## Neue Barcode-/Scanner-Funktion hinzufügen
Relevante Stellen:
- `app/Http/Controllers/Api/BarcodeScanController.php`
- `app/Http/Controllers/Api/TrackMarriageController.php`
- `app/Http/Requests/StoreBarcodeScanRequest.php`
- `app/Http/Requests/StoreTrackMarriageRequest.php`
- `app/Services/ColdstoreApiService.php`
- `resources/js/coldstore-app.js`
Warum:
- Validierung, Proxy-Aufruf, Rückmeldung und Scanner-UI sind auf genau diese Schichten verteilt.

## Neue Track-/Overview-Funktion hinzufügen
Relevante Stellen:
- `app/Services/ColdstoreApiService.php`
- `app/Http/Controllers/Api/ColdstoreOverviewController.php`
- `app/Http/Controllers/ColdstoreDashboardController.php`
- `resources/js/coldstore-app.js`
Warum:
- Overview-Normalisierung, API-Ausgabe, View-Initialisierung und Frontend-Rendering hängen in diesem Projekt direkt an diesen vier Einstiegspunkten.

## Neue Track-ID-Stabilisierung hinzufügen
Relevante Stellen:
- `app/Services/ColdstoreApiService.php`
- Remote-Overview-Vertrag laut `config/coldstore.php` und den zugehörigen Endpunkten
- `resources/js/coldstore-app.js`
Warum:
- Sichtbarkeit von Track-IDs, sichere Marriage-Defaults, lokale Sichtzeit-Persistenz und Overview-Hervorhebungen hängen an denselben normalisierten Track-Feldern.
