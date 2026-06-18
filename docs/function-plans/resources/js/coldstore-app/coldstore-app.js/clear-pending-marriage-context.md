# clearPendingMarriageContext

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `clearPendingMarriageContext()`

## Kurzbeschreibung
Entfernt den vorgemerkten Verheiratungs-Kontext aus dem Frontend-Kontext.

## Zweck im System
Räumt veraltete Zwischendaten auf, damit nachfolgende Flows sauber starten.

## Ablaufplan
1. Bestimmt den betroffenen Speicher- oder State-Bereich.
2. Löscht den vorhandenen Eintrag.
3. Hinterlässt einen leeren oder neutralen Zustand.

## Eingaben
- `window.localStorage`: persistenter Browser-Status

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Schreibt oder löscht persistenten Browser-Status.

## Verwendete Abhängigkeiten
- `window.localStorage`

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
