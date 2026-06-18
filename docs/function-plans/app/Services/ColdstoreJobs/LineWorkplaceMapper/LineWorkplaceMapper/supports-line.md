# supportsLine

## Ort
- Datei: `app/Services/ColdstoreJobs/LineWorkplaceMapper.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\LineWorkplaceMapper`
- Signatur: `supportsLine(int $line): bool`

## Kurzbeschreibung
Prüft, ob eine Liniennummer im zentralen Mapping unterstützt wird.

## Zweck im System
Dient als technische Sicherheitskante für Validierung und Linienwechsel.

## Ablaufplan
1. Liest das interne Mapping.
2. Prüft, ob die Linie als Schlüssel vorhanden ist.
3. Gibt `true` oder `false` zurück.

## Eingaben
- `int $line`

## Ausgaben
- Rückgabe: boolesche Entscheidung.

## Verwendete Abhängigkeiten
- `mapping()`

## Fachliche Regeln
Nur Linien aus dem Mapping werden unterstützt.

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
