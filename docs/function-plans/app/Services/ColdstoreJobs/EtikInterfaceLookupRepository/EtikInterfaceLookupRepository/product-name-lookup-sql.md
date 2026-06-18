# productNameLookupSql

## Ort
- Datei: `app/Services/ColdstoreJobs/EtikInterfaceLookupRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\EtikInterfaceLookupRepository`
- Signatur: `productNameLookupSql(): string`

## Kurzbeschreibung
Liefert die SQL-Abfrage für den Produktnamen-Lookup nach `required_pe_text1` zurück.

## Zweck im System
Kapselt die fachliche SQL-Bedingung, mit der Füllartnummern auf den Lookup-Wert gemappt werden.

## Ablaufplan
1. Baut die SQL-Abfrage als Heredoc auf.
2. Vergleicht Füllartnummern mit derselben `F`-zu-`9`-Normalisierung wie im Matching.
3. Gibt die SQL-Zeichenkette zurück.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Ein führendes `F` wird in der SQL-Bedingung ebenfalls zu `9` transformiert.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für fachfremde Schichten außerhalb des direkten Modulkontexts.

## Abhängige Tests
- `tests/Unit/EtikInterfaceLookupRepositoryTest.php`

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
