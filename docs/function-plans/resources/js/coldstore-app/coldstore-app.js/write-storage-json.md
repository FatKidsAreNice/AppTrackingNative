# writeStorageJson

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `writeStorageJson(key, value)`

## Kurzbeschreibung
Schreibt einen JSON-Wert in den Browser-Storage in den zuständigen Frontend-Speicher.

## Zweck im System
Erhält UI-Zustand oder Zwischenergebnisse über Seitenwechsel hinweg.

## Ablaufplan
1. Nimmt den übergebenen Wert entgegen.
2. Serialisiert oder normalisiert ihn für den Zielkontext.
3. Speichert den Wert an der vorgesehenen Stelle.

## Eingaben
- `key`
- `value`
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
