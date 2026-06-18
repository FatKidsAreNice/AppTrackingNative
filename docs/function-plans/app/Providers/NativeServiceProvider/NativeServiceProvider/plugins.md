# plugins

## Ort
- Datei: `app/Providers/NativeServiceProvider.php`
- Klasse/Modul: `App\Providers\NativeServiceProvider`
- Signatur: `plugins(): array`

## Kurzbeschreibung
Gibt die für NativePHP erlaubten Plugins zurück.

## Zweck im System
Begrenzt bewusst, welche nativen Erweiterungen in die mobile App einkompiliert werden.

## Ablaufplan
1. Baut die Liste freigegebener NativePHP-Service-Provider auf.
2. Gibt aktuell nur den Kamera-Provider zurück.

## Eingaben
- Keine expliziten Parameter; die Funktion nutzt ihren Modul- oder Laufzeitkontext.

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten statisch erkannt.

## Fachliche Regeln
Nur explizit gelistete Plugins werden in native Builds übernommen.

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
