# syncMapBackgroundCache

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `syncMapBackgroundCache()`

## Kurzbeschreibung
Synchronisiert den BEV-Hintergrund-Cache mit dem aktuellen Overview-State.

## Zweck im System
Hält Frontend-State, lokale Zwischenspeicher und Overview-Daten konsistent.

## Ablaufplan
1. Liest den aktuellen State und den externen Kontext.
2. Gleitet Unterschiede ab oder übernimmt fehlende Werte.
3. Schreibt den synchronisierten Zustand zurück.

## Eingaben
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `activeFrameId()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.
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
