# defaultLine

## Ort
- Datei: `app/Services/ColdstoreJobs/LineWorkplaceMapper.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\LineWorkplaceMapper`
- Signatur: `defaultLine(): int`

## Kurzbeschreibung
Bestimmt die Standardlinie der Anwendung auf Basis der Konfiguration.

## Zweck im System
Sorgt dafür, dass Dashboard und Jobs mit einer konsistenten Erstlinie starten.

## Ablaufplan
1. Liest die konfigurierte Standardlinie.
2. Prüft, ob sie unterstützt wird.
3. Gibt die konfigurierte oder eine Fallback-Linie zurück.

## Eingaben
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `config()`
- `lines()`
- `supportsLine()`

## Fachliche Regeln
Nicht unterstützte Konfigurationswerte fallen auf eine gültige Linie zurück.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
