# navigateToScannerForMarriage

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `navigateToScannerForMarriage(track)`

## Kurzbeschreibung
Steuert die Navigation vom Track zur Scanner-Seite im Verheiratungsmodus.

## Zweck im System
Verbindet Auswahl, Kontext und Seitenwechsel innerhalb des Coldstore-Workflows.

## Ablaufplan
1. Liest den aktuellen Auswahl- oder Navigationskontext.
2. Baut daraus die Zielnavigation.
3. Öffnet die Zielansicht oder fokussiert den passenden Bereich.

## Eingaben
- `track`
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Löst einen Seitenwechsel innerhalb der App aus.

## Verwendete Abhängigkeiten
- `window.location.assign()`
- `writePendingMarriageContext()`

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
- `tests/Feature/ColdstoreDashboardTest.php`

## Einschätzung
- vorsichtig ändern
- Frontend-State, Rendering und Navigation greifen hier oft eng ineinander.
