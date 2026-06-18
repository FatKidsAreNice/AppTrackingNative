# casts

## Ort
- Datei: `app/Models/User.php`
- Klasse/Modul: `App\Models\User`
- Signatur: `casts(): array`

## Kurzbeschreibung
Definiert die Casts für verifizierte E-Mail-Zeitpunkte und gehashte Passwörter des User-Modells.

## Zweck im System
Sorgt dafür, dass Standard-Laravel-Benutzerdaten beim Lesen und Schreiben korrekt behandelt werden.

## Ablaufplan
1. Gibt das Cast-Array des User-Modells zurück.
2. Markiert `email_verified_at` als `datetime`.
3. Markiert `password` als `hashed`.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Passwörter werden über den Cast automatisch gehasht.

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
