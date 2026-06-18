# bootDashboard

## Ort
- Datei: `resources/js/coldstore-app.js`
- Klasse/Modul: `coldstore-app.js`
- Signatur: `bootDashboard()`

## Kurzbeschreibung
Initialisiert das Coldstore-Dashboard im Browser.

## Zweck im System
Startet den jeweiligen Frontend-Flow und verbindet DOM, Daten und Interaktionen.

## Ablaufplan
1. Sucht die benötigten DOM-Anker.
2. Beendet sich früh, wenn die Oberfläche nicht vorhanden ist.
3. Bindet State, Ereignisse und Folgefunktionen zusammen.

## Eingaben
- `data-*`-Attribute: DOM-Konfiguration aus Blade
- `window.coldstore...`: serverseitig eingebettete Frontend-Konfiguration
- `state`: aktueller Frontend-Zustand der Seite

## Ausgaben
- UI-/State-Effekt: aktualisiert DOM, Frontend-State oder Browser-Navigation.
- Löst einen Seitenwechsel innerhalb der App aus.
- Schreibt gerendertes Markup in die Oberfläche.

## Verwendete Abhängigkeiten
- `activeFrameId()`
- `activeJobDetailPayload()`
- `adoptPendingMarriageContext()`
- `bindJobOrderActions()`
- `buildCabinetWeightBreakdown()`
- `buildEmptyJobsPayload()`
- `buildJobsUrl()`
- `buildLoadingJobsPayload()`
- `clearOverviewFeedback()`
- `clearPendingMarriageContext()`
- `escapeHtml()`
- `fetch()`
- `findSelectedTrack()`
- `findTrackByUid()`
- `formatCabinetWeight()`
- `formatDuration()`
- `formatEligibilityBlockers()`
- `formatIdentityConfidence()`
- `formatLastSeenTime()`
- `formatOrderQuantity()`
- `formatRequiredPeText1()`
- `formatTrackerStamp()`
- `getFilteredTracks()`
- `humanizeEligibilityReason()`
- `jobProductName()`
- `jobsWorkplaceNumberForLine()`
- `mapPoint()`
- `navigateToScannerForMarriage()`
- `numericWeight()`
- `openOverviewForUid()`
- `readOverviewFeedback()`
- `readPendingMarriageContext()`
- `readTrackUidPresence()`
- `refreshJobs()`
- `refreshOverview()`
- `render()`
- `renderDashboardScreens()`
- `renderDetails()`
- `renderJobDetailPanel()`
- `renderJobOrder()`
- `renderJobOverview()`
- `renderJobStatus()`
- `renderJobs()`
- `renderJobsLineSelector()`
- `renderLinePicker()`
- `renderMap()`
- `renderMatchingUids()`
- `renderMeta()`
- `renderNextMatchingUids()`
- `renderNoNextOrder()`
- `renderOrderSection()`
- `renderSections()`
- `renderTrackList()`
- `resetJobOrderView()`
- `scrollJobsDetailIntoView()`
- `selectTrack()`
- `selectedLineOption()`
- `setLinePickerOpen()`
- `syncMapBackgroundCache()`
- `syncTrackSessionSeenAt()`
- `trackColor()`
- `trackIsEligible()`
- `trackStatusLabel()`
- `trackStatusTone()`
- `window.location.assign()`
- `writePendingMarriageContext()`
- `writeTrackUidPresence()`

## Fachliche Regeln
Keine besonderen fachlichen Regeln.

## Fehlerfälle / Fallbacks
- Wirft bei nicht erfüllten Voraussetzungen oder Remote-/Datenfehlern eine Exception.
- Fängt Fehler ab und wandelt sie je nach Kontext in Fallbacks oder API-Fehlermeldungen um.
- Arbeitet mit Fallback-Werten für fehlende oder `null`-Daten.
- Gibt bei fehlenden oder nicht verwertbaren Daten bewusst `null` zurück.
- Beendet sich bei fehlenden Voraussetzungen früh ohne weitere Seiteneffekte.

## Relevanz für Erweiterungen
- Anpassen, wenn sich der konkrete Ablauf oder das Datenmodell dieser Funktion ändert.
- Anpassen, wenn sich Dashboard-State, Scanner-Navigation oder Frontend-Rendering in diesem Teilfluss ändern.

## Nicht zuständig für
- Nicht zuständig für SQL-Zugriffe oder die finale Remote-Persistenz.

## Abhängige Tests
Keine direkten Tests gefunden.

## Einschätzung
- vorsichtig ändern
- Frontend-State, Rendering und Navigation greifen hier oft eng ineinander.
