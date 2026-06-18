# rules

## Ort
- Datei: `app/Http/Requests/StoreBarcodeScanRequest.php`
- Klasse/Modul: `App\Http\Requests\StoreBarcodeScanRequest`
- Signatur: `rules(): array`

## Kurzbeschreibung
Definiert die gültige Payload für normale Scanner-Scans und Marriage-Scans.

## Zweck im System
Schützt den Barcode-Endpunkt vor unvollständigen oder widersprüchlichen Scan-Daten.

## Ablaufplan
1. Erzwingt Barcode, Scanner-ID und Richtung.
2. Erlaubt optional eine Scan-Zeit.
3. Erlaubt optional den Modus `marriage`.
4. Fordert in diesem Modus zusätzlich eine positive `track_id`.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `Rule::requiredIf()`

## Fachliche Regeln
Die `track_id` wird nur im Marriage-Modus verpflichtend.

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
