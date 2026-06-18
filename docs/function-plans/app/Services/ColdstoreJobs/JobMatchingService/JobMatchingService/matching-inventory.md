# matchingInventory

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `matchingInventory(string $requiredPeText1): array`

## Kurzbeschreibung
Filtert das aktuelle Coldstore-Inventar auf UIDs, deren Schrankinhalt zum benötigten Material passt.

## Zweck im System
Versorgt die Jobs-Ansicht mit den tatsächlich passenden Schränken für einen Auftrag.

## Ablaufplan
1. Lädt alle aktuellen Inventar-Einträge.
2. Holt pro UID den aktuellen Schrankinhalt nach.
3. Baut pro Inventar-Eintrag ein UI-taugliches Payload.
4. Markiert, ob das Material passt.
5. Entfernt nicht passende Einträge und gibt die Trefferliste zurück.

## Eingaben
- `string $requiredPeText1`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `$this->coldstoreInventoryRepository->allCurrent()`
- `$this->coldstoreInventoryRepository->findCurrentContentByUid()`
- `cabinetContentMatchesRequiredPeText1()`

## Fachliche Regeln
Nur Treffer mit passendem `material_pe_text1` bleiben im Ergebnis.

## Fehlerfälle / Fallbacks
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Matching-Regeln oder das Jobs-Payload fachlich erweitert werden.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
