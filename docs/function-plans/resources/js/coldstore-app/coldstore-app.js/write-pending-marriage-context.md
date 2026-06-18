# writePendingMarriageContext

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `writePendingMarriageContext(value)`

## Kurzbeschreibung
Schreibt den vorgemerkten Verheiratungs-Kontext in den zuständigen Frontend-Speicher.

## Zweck im System
Erhält UI-Zustand oder Zwischenergebnisse über Seitenwechsel hinweg.

## Ablaufplan
1. Nimmt den übergebenen Wert entgegen.
2. Serialisiert oder normalisiert ihn für den Zielkontext.
3. Speichert den Wert an der vorgesehenen Stelle.

## Eingaben
- `value`

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `writeStorageJson()`

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
- `tests/Feature/ColdstoreDashboardTest.php`

## Einschätzung
- vorsichtig ändern
- Frontend-State, Rendering und Navigation greifen hier oft eng ineinander.
