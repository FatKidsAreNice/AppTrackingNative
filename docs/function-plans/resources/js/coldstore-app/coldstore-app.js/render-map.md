# renderMap

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `renderMap()`

## Kurzbeschreibung
Rendert die BEV-Karte mit Track-Punkten.

## Zweck im System
Bringt den aktuellen Frontend-State in eine sichtbare UI-Darstellung.

## Ablaufplan
1. Liest den aktuellen Dashboard- oder Scanner-State.
2. Baut daraus Markup oder Textwerte.
3. Schreibt das Ergebnis in die zuständigen DOM-Bereiche.

## Eingaben
- `data-*`-Attribute: DOM-Konfiguration aus Blade
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Schreibt gerendertes Markup in die Oberfläche.

## Verwendete Abhängigkeiten
- `escapeHtml()`
- `mapPoint()`
- `render()`
- `selectTrack()`
- `trackColor()`
- `trackStatusTone()`

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
