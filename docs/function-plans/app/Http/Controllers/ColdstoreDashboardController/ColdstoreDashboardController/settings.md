# settings

## Ort
- Datei: `app/Http/Controllers/ColdstoreDashboardController.php`
- Klasse/Modul: `App\Http\Controllers\ColdstoreDashboardController`
- Signatur: `settings(Request $request): View`

## Kurzbeschreibung
Rendert die Settings-Seite mit den aktuell wirksamen Integrations- und Laufzeitwerten.

## Zweck im System
Macht Remote-Endpunkte, Polling-Parameter und Scanner-Konfiguration in der UI sichtbar.

## Ablaufplan
1. Löst die aktive App-Oberfläche auf.
2. Baut die relevanten API-Endpunkte für Overview und Scanner ein.
3. Liest Remote-, Polling- und Scanner-Konfigurationen aus.
4. Rendert die Settings-View mit diesen Werten.

## Eingaben
- `Request $request`
- `config(...)`: Coldstore- oder Laufzeitkonfiguration
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: Blade-View mit den für die Seite benötigten Startdaten.

## Verwendete Abhängigkeiten
- `$this->coldstoreAppSurfaceResolver->resolve()`
- `InstalledVersions::isInstalled()`
- `config()`
- `route()`
- `view()`

## Fachliche Regeln
Die Seite dient der Transparenz über die aktive Laufzeitkonfiguration, nicht der direkten Persistenz.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
- `tests/Feature/ColdstoreDashboardTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
