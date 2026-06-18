# resolve

## Ort
- Datei: `app/Support/ColdstoreAppSurfaceResolver.php`
- Klasse/Modul: `App\Support\ColdstoreAppSurfaceResolver`
- Signatur: `resolve(Request $request): string`

## Kurzbeschreibung
Bestimmt, ob die Coldstore-App in der Desktop- oder Mobile-Oberfläche laufen soll.

## Zweck im System
Steuert die Oberflächenwahl zwischen Query-Parameter, Session und NativePHP-Erkennung.

## Ablaufplan
1. Prüft zunächst einen expliziten `surface`-Parameter.
2. Speichert eine gültige Anforderung in der Session.
3. Fällt sonst auf einen Session-Wert zurück.
4. Erkennt NativePHP-Requests automatisch als `mobile`.
5. Verwendet zuletzt die Konfiguration oder `desktop` als Fallback.

## Eingaben
- `Request $request`
- `config(...)`: Coldstore- oder Laufzeitkonfiguration
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `config()`
- `isNativePhpRequest()`
- `normalizeSurface()`

## Fachliche Regeln
Nur `desktop` und `mobile` gelten als gültige Oberflächenwerte.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Feature/ProductionOrderRepositoryBindingTest.php`
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
