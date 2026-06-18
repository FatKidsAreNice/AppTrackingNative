# normalizeRack

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `normalizeRack(array $rack): array`

## Kurzbeschreibung
Normalisiert einen hervorgehobenen Schrank aus dem Remote-Payload.

## Zweck im System
Macht markierte Schränke für die Coldstore-Zusammenfassung und Karte einheitlich nutzbar.

## Ablaufplan
1. Liest Kennung, Label, Zone und Status des Schranks.
2. Setzt Fallbacks für fehlende Texte und Koordinaten.
3. Gibt ein stabiles Rack-Array zurück.

## Eingaben
- `array $rack`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `Str::uuid()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Remote-Payloads, Endpunkte oder Fallback-Verhalten erweitert werden.

## Nicht zuständig für
- Nicht zuständig für das direkte UI-Rendering im Browser.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
