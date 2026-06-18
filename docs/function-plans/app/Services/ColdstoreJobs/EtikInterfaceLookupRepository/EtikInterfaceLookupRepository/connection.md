# connection

## Ort
- Datei: `app/Services/ColdstoreJobs/EtikInterfaceLookupRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\EtikInterfaceLookupRepository`
- Signatur: `connection(): ConnectionInterface`

## Kurzbeschreibung
Liefert die Datenbankverbindung für den EtikInterface-Lookup.

## Zweck im System
Zentralisiert die Wahl der SQL-Server-Connection innerhalb des Repositorys.

## Ablaufplan
1. Liest den konfigurierten Connection-Namen.
2. Ruft `DB::connection()` mit diesem Namen auf.
3. Gibt die Verbindung zurück.

## Eingaben
- `config(...)`: Coldstore- oder Laufzeitkonfiguration

## Ausgaben
- Rückgabe: entsprechend der Signatur und dem Modulkontext.

## Verwendete Abhängigkeiten
- `DB::connection()`
- `config()`

## Fachliche Regeln
Die Verbindung wird aus der Coldstore-Produktionsauftragskonfiguration gelesen.

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
