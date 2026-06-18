# demoUidForTrack

## Ort
- Datei: `app/Services/ColdstoreJobs/MockColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\MockColdstoreInventoryRepository`
- Signatur: `demoUidForTrack(int $trackId, string $fallbackUid): string`

## Kurzbeschreibung
Leitet für einen Demo-Track eine stabile UID aus der Konfiguration oder einem Fallback-Wert ab.

## Zweck im System
Die Methode hält Mock-Inventar, Track-Referenzen und Demo-Scanner-Flows konsistent, damit dieselben Tracks immer wieder mit denselben Beispiel-UIDs erscheinen können.

## Ablaufplan
1. Nimmt Track-ID und Fallback-UID entgegen.
2. Prüft `coldstore.demo_track_barcodes` auf eine feste Demo-Zuordnung.
3. Gibt die konfigurierte UID oder den Fallback zurück.

## Eingaben
- `int $trackId`
- `string $fallbackUid`
- `config('coldstore.demo_track_barcodes')`

## Ausgaben
- Eine stabile Demo-UID für den angefragten Track.

## Verwendete Abhängigkeiten
- `config()`

## Fachliche Regeln
Konfigurierte Demo-UIDs haben Vorrang vor dem im Code hinterlegten Fallback.

## Fehlerfälle / Fallbacks
- Fehlt eine Konfigurationszuordnung, wird die übergebene Fallback-UID verwendet.

## Relevanz für Erweiterungen
- Anpassen, wenn Demo-Track-Zuordnungen aus einer anderen Quelle kommen sollen.

## Nicht zuständig für
- Nicht zuständig für Live-UID-Zuordnungen.
- Nicht zuständig für Remote-Persistenz oder Scanner-POSTs.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Methode ist klein, aber wichtig für reproduzierbare Demo-Zuordnungen.
