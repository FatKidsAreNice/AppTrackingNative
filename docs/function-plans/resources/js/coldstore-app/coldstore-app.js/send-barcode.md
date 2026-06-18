# sendBarcode

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `sendBarcode(payload)`

## Kurzbeschreibung
Verarbeitet einen Barcode- oder Marriage-Scan an das Backend.

## Zweck im System
Unterstützt einen klar benannten Schritt im Coldstore-Frontend.

## Ablaufplan
1. Liest den nötigen Kontext.
2. Führt die Funktion im vorgesehenen Frontend-Teilfluss aus.
3. Gibt ein Ergebnis zurück oder aktualisiert die Oberfläche.

## Eingaben
- `payload`
- `window.coldstore...`: serverseitig eingebettete Frontend-Konfiguration
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Löst einen Seitenwechsel innerhalb der App aus.

## Verwendete Abhängigkeiten
- `clearPendingMarriageContext()`
- `fetch()`
- `persistHistory()`
- `readPendingMarriageContext()`
- `renderHistory()`
- `renderLastScan()`
- `window.location.assign()`
- `writeOverviewFeedback()`
- `writeTrackUidPresence()`

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
