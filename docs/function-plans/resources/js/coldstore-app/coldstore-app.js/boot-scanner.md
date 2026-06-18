# bootScanner

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `bootScanner()`

## Kurzbeschreibung
Initialisiert die Scanner-Seite im Browser oder in NativePHP.

## Zweck im System
Startet den jeweiligen Frontend-Flow und verbindet DOM, Daten und Interaktionen.

## Ablaufplan
1. Sucht die benötigten DOM-Anker.
2. Beendet sich früh, wenn die Oberfläche nicht vorhanden ist.
3. Bindet State, Ereignisse und Folgefunktionen zusammen.

## Eingaben
- `window.localStorage`: persistenter Browser-Status
- `data-*`-Attribute: DOM-Konfiguration aus Blade
- `window.coldstore...`: serverseitig eingebettete Frontend-Konfiguration
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Löst einen Seitenwechsel innerhalb der App aus.
- Schreibt oder löscht persistenten Browser-Status.
- Schreibt gerendertes Markup in die Oberfläche.

## Verwendete Abhängigkeiten
- `Camera.getPhoto()`
- `Events.Camera.*`
- `Off()`
- `On()`
- `clearPendingMarriageContext()`
- `fetch()`
- `persistHistory()`
- `readPendingMarriageContext()`
- `readTrackUidPresence()`
- `renderHistory()`
- `renderLastScan()`
- `sendBarcode()`
- `updateCameraPreview()`
- `window.localStorage`
- `window.location.assign()`
- `writeOverviewFeedback()`
- `writeTrackUidPresence()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.
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
