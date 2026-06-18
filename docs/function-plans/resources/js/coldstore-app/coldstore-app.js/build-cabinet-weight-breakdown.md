# buildCabinetWeightBreakdown

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `buildCabinetWeightBreakdown(netWeightKg, orderWeightKg)`

## Kurzbeschreibung
Baut die Gewichtsaufteilung eines Schranks für die Jobs-Ansicht.

## Zweck im System
Erzeugt eine strukturierte Zwischenrepräsentation für UI oder API-Aufrufe.

## Ablaufplan
1. Liest die benötigten Eingabewerte.
2. Setzt daraus ein konsistentes Objekt oder Payload zusammen.
3. Gibt das Ergebnis an den Aufrufer zurück.

## Eingaben
- `netWeightKg`
- `orderWeightKg`

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `numericWeight()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Gibt bei fehlenden oder nicht verwertbaren Daten bewusst `null` zurück.

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
