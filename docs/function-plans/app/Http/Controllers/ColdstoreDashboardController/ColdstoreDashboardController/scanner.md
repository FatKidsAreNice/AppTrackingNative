# scanner

## Ort
- Datei: `app/Http/Controllers/ColdstoreDashboardController.php`
- Klasse/Modul: `App\Http\Controllers\ColdstoreDashboardController`
- Signatur: `scanner(Request $request): View`

## Kurzbeschreibung
Rendert die Scanner-Seite und ergänzt bei Bedarf den Verheiratungs-Kontext eines ausgewählten Tracks.

## Zweck im System
Verbindet die Overview-Auswahl mit dem bestehenden Scanner-Flow.

## Ablaufplan
1. Liest `track_id`, `mode` und optionale Track-Labels aus dem Request.
2. Entscheidet zwischen normalem Scan-Modus und Verheiratungsmodus.
3. Baut bei Marriage-Modus den Kontext für Track, Position und Rücksprung zur Overview.
4. Rendert die Scanner-View mit Endpunkten, Geräteinfos und Kontextdaten.

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
Ein Marriage-Kontext wird nur aufgebaut, wenn `mode=marriage` und eine positive `track_id` vorliegt.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
- `tests/Feature/BarcodeScanApiTest.php`
- `tests/Feature/ColdstoreDashboardTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
