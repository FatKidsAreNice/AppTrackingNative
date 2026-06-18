# isNativePhpRequest

## Ort
- Datei: `app/Support/ColdstoreAppSurfaceResolver.php`
- Klasse/Modul: `App\Support\ColdstoreAppSurfaceResolver`
- Signatur: `isNativePhpRequest(Request $request): bool`

## Kurzbeschreibung
Prüft, ob ein Request aus einer NativePHP-App kommt.

## Zweck im System
Ermöglicht die automatische Umschaltung auf die mobile Coldstore-Oberfläche.

## Ablaufplan
1. Liest den User-Agent des Requests.
2. Sucht darin nach `nativephp`.
3. Gibt `true` oder `false` zurück.

## Eingaben
- `Request $request`
- `Request`: HTTP- oder Query-Kontext

## Ausgaben
- Rückgabe: boolesche Entscheidung.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Die Erkennung basiert ausschließlich auf dem User-Agent.

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
