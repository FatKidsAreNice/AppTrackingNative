# workplaceNumberForLine

## Ort
- Datei: `app/Services/ColdstoreJobs/LineWorkplaceMapper.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\LineWorkplaceMapper`
- Signatur: `workplaceNumberForLine(int $line): int`

## Kurzbeschreibung
Liefert die Arbeitsplatznummer zu einer Linie.

## Zweck im System
Verbindet die Coldstore-Linienauswahl mit der Produktionsauftragsquelle.

## Ablaufplan
1. Liest das interne Mapping.
2. Sucht die Arbeitsplatznummer der angefragten Linie.
3. Gibt den gemappten Wert zurück.

## Eingaben
- `int $line`

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `mapping()`

## Fachliche Regeln
Die Zuordnung erfolgt ausschließlich über das zentrale Mapping.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/LineWorkplaceMapperTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
