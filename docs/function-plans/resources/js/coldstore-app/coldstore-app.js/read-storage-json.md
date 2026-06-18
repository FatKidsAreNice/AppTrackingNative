# readStorageJson

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `readStorageJson(key, fallbackValue)`

## Kurzbeschreibung
Liest einen JSON-Wert aus dem Browser-Storage und verwendet bei Bedarf Fallback-Werte.

## Zweck im System
Stützt den Frontend-State auf gespeicherte oder serverseitig eingebettete Daten.

## Ablaufplan
1. Liest die benötigten Eingaben oder den Browser-State.
2. Prüft, ob ein verwertbarer Wert vorliegt.
3. Gibt den gelesenen oder den Fallback-Wert zurück.

## Eingaben
- `key`
- `fallbackValue`
- `window.localStorage`: persistenter Browser-Status

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `window.localStorage`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.

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
