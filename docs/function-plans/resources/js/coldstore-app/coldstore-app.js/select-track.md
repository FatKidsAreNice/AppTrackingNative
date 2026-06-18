# selectTrack

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `selectTrack(trackId, highlightedUid = null)`

## Kurzbeschreibung
Setzt einen Track als aktive Auswahl als aktive Auswahl.

## Zweck im System
Steuert, welcher Track oder welcher Jobs-Kontext im Frontend weiterverarbeitet wird.

## Ablaufplan
1. Übernimmt die neue Auswahl.
2. Aktualisiert Highlight- und Statuswerte.
3. Löst bei Bedarf Folgeaktionen wie Navigation oder Rendering aus.

## Eingaben
- `trackId`
- `highlightedUid = null`
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `findSelectedTrack()`
- `navigateToScannerForMarriage()`
- `trackIsEligible()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Beendet sich bei fehlenden Voraussetzungen früh ohne weitere Seiteneffekte.

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
