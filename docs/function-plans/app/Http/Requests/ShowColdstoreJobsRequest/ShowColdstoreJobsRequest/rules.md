# rules

## Ort
- Datei: `app/Http/Requests/ShowColdstoreJobsRequest.php`
- Klasse/Modul: `App\Http\Requests\ShowColdstoreJobsRequest`
- Signatur: `rules(): array`

## Kurzbeschreibung
Definiert die gültige Linienauswahl für den Jobs-Endpunkt.

## Zweck im System
Stellt sicher, dass nur unterstützte Linien an die Jobs-Logik gelangen.

## Ablaufplan
1. Lädt die unterstützten Linien aus `LineWorkplaceMapper`.
2. Markiert `selected_line` als Pflichtfeld.
3. Erzwingt Integer-Format und Zugehörigkeit zur unterstützten Linienliste.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `Rule::in()`

## Fachliche Regeln
Nur Linien aus dem zentralen Mapper sind zulässig.

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
