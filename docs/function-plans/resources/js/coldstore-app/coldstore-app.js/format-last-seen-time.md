# formatLastSeenTime

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `formatLastSeenTime(trackStampSeconds)`

## Kurzbeschreibung
Formatiert die letzte Sichtung eines Tracks für die Anzeige.

## Zweck im System
Bereitet Rohwerte für eine lesbare Anzeige im Frontend auf.

## Ablaufplan
1. Prüft den Eingabewert.
2. Wendet das gewünschte Anzeigeformat an.
3. Gibt den formatierten Wert zurück.

## Eingaben
- `trackStampSeconds`
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `formatTrackerStamp()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

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
