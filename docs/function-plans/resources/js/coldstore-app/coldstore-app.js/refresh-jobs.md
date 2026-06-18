# refreshJobs

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `refreshJobs(selectedLine)`

## Kurzbeschreibung
Aktualisiert die Jobs-Daten für eine Linie.

## Zweck im System
Holt frische Daten und synchronisiert damit den Frontend-State.

## Ablaufplan
1. Startet den zugehörigen Lade- oder Request-Ablauf.
2. Verarbeitet Erfolg oder Fehler.
3. Aktualisiert State und UI.

## Eingaben
- `selectedLine`
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `buildEmptyJobsPayload()`
- `buildJobsUrl()`
- `buildLoadingJobsPayload()`
- `fetch()`
- `render()`
- `resetJobOrderView()`
- `setLinePickerOpen()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.
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
