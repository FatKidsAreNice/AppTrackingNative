# requiredPeText1ForFuellArtNr

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `requiredPeText1ForFuellArtNr(string $matstammFuellArtNr): string`

## Kurzbeschreibung
Leitet aus einer Füllartnummer den Vergleichswert für `required_pe_text1` ab.

## Zweck im System
Kapselt die Umrechnung zwischen Produktionsauftrag und Inventar-Materialschlüssel.

## Ablaufplan
1. Trimmt die Füllartnummer.
2. Behandelt leere Eingaben als leer.
3. Ersetzt ein führendes `F` durch `9`.
4. Gibt den normalisierten Vergleichswert zurück.

## Eingaben
- `string $matstammFuellArtNr`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Ein führendes `F` wird fachlich zu `9` normalisiert.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn Matching-Regeln oder das Jobs-Payload fachlich erweitert werden.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`

## Einschätzung
- kritisch
- Diese Funktion hängt an zentralen Payloads, Datenquellen oder Matching-Regeln und sollte nur mit vollständigem Kontext geändert werden.
