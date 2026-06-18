# normalizeOrder

## Ort
- Datei: `app/Services/ColdstoreJobs/JobMatchingService.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\JobMatchingService`
- Signatur: `normalizeOrder(array $order): array`

## Kurzbeschreibung
Normalisiert ein Roh-Order-Array in das stabile Dashboard-Format inklusive `required_pe_text1`.

## Zweck im System
Stellt sicher, dass Auftragsdaten konsistent an Jobs-UI und Matching weitergereicht werden.

## Ablaufplan
1. Liest die Rohwerte des Auftrags.
2. Normalisiert `matstamm_fuellartnr`.
3. Leitet daraus `required_pe_text1` ab.
4. Ergänzt optional den Produktnamen aus dem Lookup.
5. Gibt die bereinigte Auftragsstruktur zurück.

## Eingaben
- `array $order`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `$this->etikInterfaceLookupRepository->productNameForRequiredPeText1()`
- `requiredPeText1ForFuellArtNr()`

## Fachliche Regeln
Leere Produktnamen werden nachgeschlagen; Strings werden getrimmt.

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
