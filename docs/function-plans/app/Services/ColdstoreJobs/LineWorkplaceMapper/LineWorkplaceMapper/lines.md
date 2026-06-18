# lines

## Ort
- Datei: `app/Services/ColdstoreJobs/LineWorkplaceMapper.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\LineWorkplaceMapper`
- Signatur: `lines(): array`

## Kurzbeschreibung
Liefert nur die unterstützten Liniennummern aus dem zentralen Mapping.

## Zweck im System
Versorgt Validierung und Jobs-Logik mit der kanonischen Linienliste.

## Ablaufplan
1. Liest das interne Mapping.
2. Extrahiert die vorhandenen Liniennummern.
3. Gibt sie als Array zurück.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.

## Verwendete Abhängigkeiten
- `mapping()`

## Fachliche Regeln
Nur gemappte Linien gelten als unterstützt.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Pest.php`
- `tests/Unit/LineWorkplaceMapperTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
