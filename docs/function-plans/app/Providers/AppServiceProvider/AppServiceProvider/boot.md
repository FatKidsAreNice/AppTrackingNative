# boot

## Ort
- Datei: `app/Providers/AppServiceProvider.php`
- Klasse/Modul: `App\Providers\AppServiceProvider`
- Signatur: `boot(): void`

## Kurzbeschreibung
Aktiviert produktionsspezifische HTTPS-Erzwingung und normalisiert die von Vite erzeugten Asset-Pfade.

## Zweck im System
Schafft eine konsistente Laufzeitbasis für ausgelieferte Coldstore-Oberflächen.

## Ablaufplan
1. Prüft, ob die Anwendung im Produktionsmodus läuft.
2. Erzwingt dort HTTPS-URLs.
3. Registriert eine Vite-Strategie, die Asset-Pfade mit führendem Slash ausgibt.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `$this->app->isProduction()`
- `URL::forceHttps()`
- `Vite::createAssetPathsUsing()`

## Fachliche Regeln
HTTPS wird nur in Produktion erzwungen.

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
