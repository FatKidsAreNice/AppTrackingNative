# renderMatchingUids

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `renderMatchingUids(matchingUids, orderWeightKg = null)`

## Kurzbeschreibung
Rendert die UID-Trefferliste eines Auftrags.

## Zweck im System
Bringt den aktuellen Frontend-State in eine sichtbare UI-Darstellung.

## Ablaufplan
1. Liest den aktuellen Dashboard- oder Scanner-State.
2. Baut daraus Markup oder Textwerte.
3. Schreibt das Ergebnis in die zuständigen DOM-Bereiche.

## Eingaben
- `matchingUids`
- `orderWeightKg = null`

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.

## Verwendete Abhängigkeiten
- `buildCabinetWeightBreakdown()`
- `escapeHtml()`
- `formatCabinetWeight()`

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
- `tests/Feature/ColdstoreDashboardTest.php`

## Einschätzung
- vorsichtig ändern
- Frontend-State, Rendering und Navigation greifen hier oft eng ineinander.
