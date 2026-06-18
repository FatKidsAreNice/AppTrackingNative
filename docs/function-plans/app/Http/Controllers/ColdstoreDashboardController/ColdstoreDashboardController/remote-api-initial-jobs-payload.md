# remoteApiInitialJobsPayload

## Ort
- Datei: `app/Http/Controllers/ColdstoreDashboardController.php`
- Klasse/Modul: `App\Http\Controllers\ColdstoreDashboardController`
- Signatur: `remoteApiInitialJobsPayload(int $defaultLine): array`

## Kurzbeschreibung
Erzeugt das neutrale Start-Payload für den Remote-API-Jobs-Modus.

## Zweck im System
Verhindert, dass die Jobs-Ansicht beim ersten Rendern mit lokalen Daten vermischt wird.

## Ablaufplan
1. Übernimmt die Standardlinie.
2. Ermittelt die zugehörige Arbeitsplatznummer.
3. Setzt Aufträge und Matching-Listen leer.
4. Markiert das Payload als `remote_api` und `loading`.

## Eingaben
- `int $defaultLine`

## Ausgaben
- Rückgabe: Array- oder Payload-Struktur für nachgelagerte Logik.
- Baut strukturierte Daten für den nächsten Verarbeitungsschritt auf.

## Verwendete Abhängigkeiten
- `$this->lineWorkplaceMapper->workplaceNumberForLine()`

## Fachliche Regeln
Im Remote-Modus bleiben `order` und `next_order` zunächst bewusst leer.

## Fehlerfälle / Fallbacks
- Keine besonderen Fehlerfälle direkt sichtbar; das Verhalten folgt dem normalen Modulfluss.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.

## Nicht zuständig für
- Nicht zuständig für die eigentliche Fachlogik; diese liegt in Requests, Services oder Repositories.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- stabil
- Die Funktion ist klar abgegrenzt und wirkt wie ein gut lokalisierbarer Baustein.
