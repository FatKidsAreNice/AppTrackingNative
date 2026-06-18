# renderDashboardScreens

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `renderDashboardScreens()`

## Kurzbeschreibung
Rendert die aktiven Dashboard-Bereiche.

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
