# sourceMode

## Ort
- Datei: `app/Services/ColdstoreJobs/ColdstoreInventoryRepository.php`
- Klasse/Modul: `App\Services\ColdstoreJobs\ColdstoreInventoryRepository`
- Signatur: `sourceMode(): string`

## Kurzbeschreibung
Beschreibt auf Abstraktionsebene, wie eine Inventarquelle ihren aktuellen Herkunftsmodus nach außen meldet.

## Zweck im System
Die Basisklasse gibt dem Jobs-Flow eine einheitliche Schnittstelle, über die Mock-, SQL- oder Mischbetrieb sichtbar gemacht werden können.

## Ablaufplan
1. Definiert nur die erwartete Rückgabeform.
2. Überlässt die konkrete Herkunftslogik der jeweiligen Unterklasse.

## Eingaben
- Keine expliziten Parameter; die Methode arbeitet über den Implementierungskontext der Unterklasse.

## Ausgaben
- Ein String, der die aktive Herkunft der Inventardaten beschreibt.

## Verwendete Abhängigkeiten
- Keine direkten Abhängigkeiten; die konkrete Unterklasse füllt die Methode aus.

## Fachliche Regeln
Die Basisklasse legt nur die Vertragsgrenze fest und trifft keine eigene Quellentscheidung.

## Fehlerfälle / Fallbacks
- Keine direkten Fehlerfälle in der abstrakten Definition sichtbar.

## Relevanz für Erweiterungen
- Anpassen, wenn neue Inventarquellen eingeführt oder Source-Mode-Werte vereinheitlicht werden.

## Nicht zuständig für
- Nicht zuständig für das eigentliche Laden von Inventardaten.
- Nicht zuständig für Fallback-Entscheidungen konkreter Implementierungen.

## Abhängige Tests
- `tests/Unit/JobMatchingServiceTest.php`
- `tests/Unit/SqlServerColdstoreInventoryRepositoryTest.php`

## Einschätzung
- kritisch
- Änderungen wirken direkt auf Transparenz und Diagnose der Inventardatenquelle.
