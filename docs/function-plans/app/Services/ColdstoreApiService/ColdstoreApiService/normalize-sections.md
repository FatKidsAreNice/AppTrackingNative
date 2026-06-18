# normalizeSections

## Ort
- Datei: `app/Services/ColdstoreApiService.php`
- Klasse/Modul: `App\Services\ColdstoreApiService`
- Signatur: `normalizeSections(array $sections, array $highlightedRacks): array`

## Kurzbeschreibung
Baut die Bereichsdaten der Kühlhaus-Zusammenfassung aus Remote-Sections und markierten Schränken auf.

## Zweck im System
Versorgt die Bereichskarten des Dashboards mit konsistenten Werten.

## Ablaufplan
1. Gruppiert markierte Schränke nach Zone.
2. Verknüpft diese Gruppen mit den vom Remote-System gelieferten Sections.
3. Setzt Fallback-Texte für fehlende Felder.
4. Gibt die normalisierte Sections-Liste zurück.

## Eingaben
- `array $sections`
- `array $highlightedRacks`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

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
