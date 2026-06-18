# formatDuration

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `formatDuration(durationMs)`

## Kurzbeschreibung
Formatiert eine Dauer für die Anzeige.

## Zweck im System
Bereitet Rohwerte für eine lesbare Anzeige im Frontend auf.

## Ablaufplan
1. Prüft den Eingabewert.
2. Wendet das gewünschte Anzeigeformat an.
3. Gibt den formatierten Wert zurück.

## Eingaben
- `durationMs`

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn sich Dashboard-State, Scanner-Navigation oder Frontend-Rendering in diesem Teilfluss ändern.

## Nicht zuständig für
- Nicht zuständig für SQL-Zugriffe oder die finale Remote-Persistenz.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- vorsichtig ändern
- Frontend-State, Rendering und Navigation greifen hier oft eng ineinander.
