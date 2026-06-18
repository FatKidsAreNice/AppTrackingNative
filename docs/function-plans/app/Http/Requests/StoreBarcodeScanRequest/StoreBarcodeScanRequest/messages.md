# messages

## Ort
- Datei: `app/Http/Requests/StoreBarcodeScanRequest.php`
- Klasse/Modul: `App\Http\Requests\StoreBarcodeScanRequest`
- Signatur: `messages(): array`

## Kurzbeschreibung
Liefert deutsche Validierungsfehler für den Barcode- und Marriage-Scan.

## Zweck im System
Macht dem Scanner-Frontend klar, welches Feld korrigiert werden muss.

## Ablaufplan
1. Ordnet Pflicht-, Format- und Bereichsregeln sprechende Meldungen zu.
2. Deckt dabei auch den Sonderfall `mode=marriage` ab.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Für Marriage-Scans wird fehlende `track_id` explizit benannt.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn neue Request-Felder oder Validierungsregeln hinzukommen.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Verarbeitung nach erfolgreicher Validierung.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
