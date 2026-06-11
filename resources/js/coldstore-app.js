import { Camera, Events, Off, On } from '#nativephp';

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

document.addEventListener('DOMContentLoaded', () => {
    bootDashboard();
    bootScanner();
});

function bootDashboard() {
    const root = document.querySelector('[data-coldstore-dashboard]');

    if (!root) {
        return;
    }

    const overviewEndpoint = root.dataset.overviewEndpoint;
    const pollInterval = Number(root.dataset.pollInterval ?? '5000');
    const initialOverview = window.coldstoreDashboardConfig ?? {};
    const initialFrameId = initialOverview.meta?.frame_id ?? 'coldstore-map';
    const state = {
        overview: initialOverview,
        jobs: window.coldstoreDashboardJobs ?? [],
        filter: '',
        selectedJobUid: null,
        selectedTrackId: initialOverview.overview?.selected_track_id ?? null,
        trackSessionSeenAt: {},
        mapBackgroundCache: {
            [initialFrameId]: {
                backgroundBase64: initialOverview.map?.background_base64 ?? null,
                backgroundUrl: initialOverview.map?.background_url ?? null,
            },
        },
    };

    const jobsList = root.querySelector('[data-jobs-list]');
    const trackList = root.querySelector('[data-track-list]');
    const detailList = root.querySelector('[data-track-detail]');
    const sectionList = root.querySelector('[data-section-list]');
    const map = root.querySelector('[data-track-map]');
    const sourcePill = root.querySelector('[data-source-pill]');
    const subtitle = root.querySelector('[data-overview-subtitle]');
    const trackCount = root.querySelector('[data-track-count]');
    const bevSource = root.querySelector('[data-bev-source]');
    const jobStatus = root.querySelector('[data-job-status]');
    const updatedAt = root.querySelector('[data-updated-at]');
    const detailTitle = root.querySelector('[data-detail-title]');
    const filterInput = root.querySelector('[data-track-filter]');
    const refreshButton = root.querySelector('[data-refresh-overview]');
    const rootStyles = window.getComputedStyle(document.documentElement);

    filterInput?.addEventListener('input', (event) => {
        state.filter = event.target.value.toLowerCase();
        render();
    });

    refreshButton?.addEventListener('click', async () => {
        await refreshOverview();
    });

    function activeFrameId(overview = state.overview) {
        return overview.meta?.frame_id ?? 'coldstore-map';
    }

    function syncTrackSessionSeenAt() {
        const visibleTrackIds = new Set(
            (state.overview.tracks ?? []).map((track) => String(track.track_id)),
        );

        visibleTrackIds.forEach((trackId) => {
            if (!state.trackSessionSeenAt[trackId]) {
                state.trackSessionSeenAt[trackId] = Date.now();
            }
        });

        Object.keys(state.trackSessionSeenAt).forEach((trackId) => {
            if (!visibleTrackIds.has(trackId)) {
                delete state.trackSessionSeenAt[trackId];
            }
        });
    }

    function syncMapBackgroundCache() {
        const frameId = activeFrameId();
        const cachedBackground = state.mapBackgroundCache[frameId] ?? {
            backgroundBase64: null,
            backgroundUrl: null,
        };
        const incomingBackgroundBase64 = state.overview.map?.background_base64 ?? null;
        const incomingBackgroundUrl = state.overview.map?.background_url ?? null;

        if (cachedBackground.backgroundBase64 || cachedBackground.backgroundUrl) {
            state.overview.map = {
                ...(state.overview.map ?? {}),
                background_base64: cachedBackground.backgroundBase64,
                background_url: cachedBackground.backgroundUrl,
                show_background: Boolean(cachedBackground.backgroundBase64 || cachedBackground.backgroundUrl),
            };

            return;
        }

        if (!incomingBackgroundBase64 && !incomingBackgroundUrl) {
            return;
        }

        state.mapBackgroundCache[frameId] = {
            backgroundBase64: incomingBackgroundBase64,
            backgroundUrl: incomingBackgroundUrl,
        };
    }

    async function refreshOverview() {
        refreshButton?.setAttribute('disabled', 'disabled');

        try {
            const response = await fetch(overviewEndpoint, {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Overview konnte nicht geladen werden.');
            }

            state.overview = await response.json();
            syncMapBackgroundCache();
            syncTrackSessionSeenAt();

            if (!state.selectedJobUid && !findSelectedTrack()) {
                state.selectedTrackId = state.overview.overview?.selected_track_id ?? state.overview.tracks?.[0]?.track_id ?? null;
            }

            syncSelectedJobToTrack();

            render();
        } catch (error) {
            syncState.textContent = error.message;
        } finally {
            refreshButton?.removeAttribute('disabled');
        }
    }

    function getFilteredTracks() {
        const tracks = state.overview.tracks ?? [];

        if (!state.filter) {
            return tracks;
        }

        return tracks.filter((track) => {
            const haystack = [
                track.display_id,
                track.track_id,
                track.barcode_id,
                track.x,
                track.y,
            ]
                .join(' ')
                .toLowerCase();

            return haystack.includes(state.filter);
        });
    }

    function findSelectedTrack() {
        return (state.overview.tracks ?? []).find((track) => track.track_id === Number(state.selectedTrackId)) ?? null;
    }

    function findTrackForJob(job) {
        if (!job) {
            return null;
        }

        return (state.overview.tracks ?? []).find((track) => {
            return (
                String(track.track_id) === String(job.uid)
                || String(track.display_id) === String(job.uid)
                || String(track.barcode_id) === String(job.uid)
            );
        }) ?? null;
    }

    function setTrackFilter(value) {
        state.filter = value.toLowerCase();

        if (filterInput) {
            filterInput.value = value;
        }
    }

    function selectJob(job) {
        state.selectedJobUid = job.uid;
        setTrackFilter(job.uid);

        const matchingTrack = findTrackForJob(job);

        if (matchingTrack) {
            state.selectedTrackId = matchingTrack.track_id;
        } else {
            state.selectedTrackId = null;
        }

        render();
    }

    function syncSelectedJobToTrack() {
        const selectedJob = state.jobs.find((job) => job.uid === state.selectedJobUid);

        if (!selectedJob) {
            return;
        }

        const matchingTrack = findTrackForJob(selectedJob);

        if (matchingTrack) {
            state.selectedTrackId = matchingTrack.track_id;
        } else {
            state.selectedTrackId = null;
        }
    }

    function mapPoint(x, y) {
        const [minX, minY] = state.overview.map?.roi_min ?? [-14.5, -15];
        const [maxX, maxY] = state.overview.map?.roi_max ?? [9, 6];
        const rotationDeg = Number(state.overview.map?.rotation_deg ?? 0);
        const trackOffsetY = Number.parseFloat(rootStyles.getPropertyValue('--bev-track-offset-y')) || 0;
        const centerX = (minX + maxX) / 2;
        const centerY = (minY + maxY) / 2;
        const normalizedRotationDeg = Number.isFinite(rotationDeg) ? rotationDeg : 0;
        const rotationRad = (normalizedRotationDeg * Math.PI) / 180;
        const rotatedX = centerX + ((x - centerX) * Math.cos(rotationRad)) - ((y - centerY) * Math.sin(rotationRad));
        const rotatedY = centerY + ((x - centerX) * Math.sin(rotationRad)) + ((y - centerY) * Math.cos(rotationRad));
        const xRatio = maxX <= minX ? 0 : (rotatedX - minX) / (maxX - minX);
        const yRatio = maxY <= minY ? 0 : (maxY - rotatedY) / (maxY - minY);

        return {
            x: Math.max(0, Math.min(100, xRatio * 100)),
            y: Math.max(0, Math.min(100, (yRatio * 100) + trackOffsetY)),
        };
    }

    function formatTrackerStamp(seconds) {
        const numericSeconds = Number(seconds);

        if (!Number.isFinite(numericSeconds) || numericSeconds <= 0) {
            return '-';
        }

        return `${numericSeconds.toFixed(3)} s`;
    }

    function formatLastSeenTime(trackStampSeconds) {
        const numericTrackStamp = Number(trackStampSeconds);
        const numericOverviewStamp = Number(state.overview.meta?.track_stamp_sec ?? 0);
        const updatedAtValue = state.overview.meta?.updated_at;
        const updatedAtMs = updatedAtValue ? new Date(updatedAtValue).getTime() : Number.NaN;

        if (!Number.isFinite(numericTrackStamp) || numericTrackStamp <= 0) {
            return '-';
        }

        if (!Number.isFinite(numericOverviewStamp) || numericOverviewStamp <= 0) {
            return formatTrackerStamp(numericTrackStamp);
        }

        if (!Number.isFinite(updatedAtMs)) {
            return formatTrackerStamp(numericTrackStamp);
        }

        const deltaSeconds = Math.max(0, numericOverviewStamp - numericTrackStamp);
        const absoluteTimestamp = updatedAtMs - (deltaSeconds * 1000);

        return new Date(absoluteTimestamp).toLocaleTimeString('de-DE');
    }

    function formatDuration(durationMs) {
        const safeDurationMs = Math.max(0, durationMs);
        const totalSeconds = Math.floor(safeDurationMs / 1000);
        const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const secs = String(totalSeconds % 60).padStart(2, '0');

        return `${hours}:${minutes}:${secs}`;
    }

    function trackColor() {
        return '#d71920';
    }

    function renderMap() {
        const tracks = state.overview.tracks ?? [];
        const backgroundBase64 = state.overview.map?.background_base64;
        const backgroundUrl = state.overview.map?.background_url;
        const frameId = state.overview.meta?.frame_id ?? 'coldstore-map';
        const clipPathId = `bev-map-clip-${String(frameId).replace(/[^a-zA-Z0-9_-]/g, '-')}`;
        const imageHref = backgroundBase64 ? `data:image/png;base64,${backgroundBase64}` : backgroundUrl;
        const trackMarkup = tracks
            .map((track) => {
                const point = mapPoint(track.x, track.y);
                const radius = track.track_id === Number(state.selectedTrackId) ? 2.4 : 1.7;

                return `
                    <circle
                        cx="${point.x}"
                        cy="${point.y}"
                        r="${radius}"
                        fill="${trackColor()}"
                        stroke="${track.track_id === Number(state.selectedTrackId) ? '#111827' : 'none'}"
                        stroke-width="${track.track_id === Number(state.selectedTrackId) ? '0.45' : '0'}"
                        data-track-node="${track.track_id}"
                    ></circle>
                `;
            })
            .join('');

        map.innerHTML = `
            <defs>
                <clipPath id="${clipPathId}">
                    <rect x="4" y="4" width="92" height="92" rx="4"></rect>
                </clipPath>
            </defs>
            <rect x="0" y="0" width="100" height="100" fill="#f3f7f4" rx="6"></rect>
            <rect x="4" y="4" width="92" height="92" fill="#ffffff" rx="4"></rect>
            <g class="bev-rotation-layer" clip-path="url(#${clipPathId})">
                ${imageHref ? `<image class="bev-map-image" href="${imageHref}" x="4" y="4" width="92" height="92" preserveAspectRatio="none"></image>` : ''}
                ${trackMarkup}
            </g>
            <rect x="0.5" y="0.5" width="99" height="99" fill="none" stroke="#3d5a40" stroke-width="1" rx="6"></rect>
        `;

        map.querySelectorAll('[data-track-node]').forEach((node) => {
            node.addEventListener('click', () => {
                state.selectedTrackId = Number(node.dataset.trackNode);
                render();
            });
        });
    }

    function renderJobs() {
        jobsList.innerHTML = state.jobs
            .map((job) => {
                const selected = job.uid === state.selectedJobUid;

                return `
                    <button class="job-row ${selected ? 'job-row--active' : ''}" type="button" data-select-job="${job.uid}">
                        <span>
                            <strong>UID ${job.uid}</strong>
                            <small>Aeltester Job zuerst</small>
                        </span>
                        <span>
                            <strong>${job.destination}</strong>
                            <small>Prioritaet ${job.priority}</small>
                        </span>
                    </button>
                `;
            })
            .join('');

        jobsList.querySelectorAll('[data-select-job]').forEach((button) => {
            button.addEventListener('click', () => {
                const job = state.jobs.find((entry) => entry.uid === button.dataset.selectJob);

                if (!job) {
                    return;
                }

                selectJob(job);
            });
        });

        const selectedJob = state.jobs.find((job) => job.uid === state.selectedJobUid);

        if (!selectedJob) {
            jobStatus.textContent = 'Die manuelle Suche bleibt darunter als Fallback verfuegbar.';

            return;
        }

        const matchingTrack = findTrackForJob(selectedJob);

        jobStatus.textContent = matchingTrack
            ? `Aktiver Job UID ${selectedJob.uid}: Live-Track ${matchingTrack.display_id} wurde markiert.`
            : `Aktiver Job UID ${selectedJob.uid}: Noch kein passender Live-Track gefunden, die Suche wurde als Fallback gesetzt.`;
    }

    function renderTrackList() {
        const filteredTracks = getFilteredTracks();

        trackList.innerHTML = filteredTracks
            .map((track) => {
                const selected = track.track_id === Number(state.selectedTrackId);

                return `
                    <button class="track-row ${selected ? 'track-row--active' : ''}" type="button" data-select-track="${track.track_id}">
                        <span>
                            <strong>${track.display_id}</strong>
                            <small>Position x=${track.x.toFixed(2)}, y=${track.y.toFixed(2)}</small>
                        </span>
                        <span>
                            <strong>${selected ? 'Ausgewaehlt' : 'Track'}</strong>
                            <small>${track.barcode_id || 'Kein Barcode'}</small>
                        </span>
                    </button>
                `;
            })
            .join('');

        trackList.querySelectorAll('[data-select-track]').forEach((button) => {
            button.addEventListener('click', () => {
                state.selectedTrackId = Number(button.dataset.selectTrack);
                render();
            });
        });
    }

    function renderSections() {
        const sections = state.overview.coldstore?.sections ?? [];

        sectionList.innerHTML = sections
            .map((section) => {
                return `
                    <article class="section-card">
                        <p class="section-card__title">${section.name}</p>
                        <p class="section-card__value">${section.occupancy}</p>
                        <p class="section-card__note">${section.note || section.status}</p>
                    </article>
                `;
            })
            .join('');
    }

    function renderDetails() {
        const selectedTrack = findSelectedTrack();

        if (!selectedTrack) {
            detailTitle.textContent = 'Keine Auswahl';
            detailList.innerHTML = `
                <dt>Hinweis</dt>
                <dd>Bitte einen Track aus Liste oder Karte auswaehlen.</dd>
            `;

            return;
        }

        const sessionSeenAt = state.trackSessionSeenAt[String(selectedTrack.track_id)] ?? Date.now();

        detailTitle.textContent = selectedTrack.display_id;
        detailList.innerHTML = [
            ['Track ID', selectedTrack.track_id],
            ['Barcode', selectedTrack.barcode_id || '-'],
            ['Position', `x=${selectedTrack.x.toFixed(3)}, y=${selectedTrack.y.toFixed(3)}, z=${selectedTrack.z.toFixed(3)}`],
            ['In Ansicht seit', new Date(sessionSeenAt).toLocaleTimeString('de-DE')],
            ['Dauer in Ansicht', formatDuration(Date.now() - sessionSeenAt)],
            ['Letzte Sichtung', formatLastSeenTime(selectedTrack.last_stamp_sec)],
        ]
            .map(([label, value]) => `<dt>${label}</dt><dd>${value}</dd>`)
            .join('');
    }

    function renderMeta() {
        sourcePill.textContent = state.overview.meta?.source_mode ?? 'unknown';
        sourcePill.classList.toggle('status-pill--warn', state.overview.meta?.source_mode !== 'remote');
        subtitle.textContent = state.overview.overview?.subtitle ?? '';
        trackCount.textContent = state.overview.overview?.track_count ?? '0';
        bevSource.textContent = state.overview.map?.background_url ? 'Live-Bild aktiv' : 'Nur Track-Overlay';
        updatedAt.textContent = new Date(state.overview.meta?.updated_at ?? Date.now()).toLocaleString('de-DE');
    }

    function render() {
        renderMeta();
        renderJobs();
        renderMap();
        renderTrackList();
        renderSections();
        renderDetails();
    }

    syncMapBackgroundCache();
    syncTrackSessionSeenAt();
    render();
    window.setInterval(() => {
        if (findSelectedTrack()) {
            renderDetails();
        }
    }, 1000);
    window.setInterval(refreshOverview, pollInterval);
}

function bootScanner() {
    const root = document.querySelector('[data-coldstore-scanner]');

    if (!root) {
        return;
    }

    const barcodeEndpoint = root.dataset.barcodeEndpoint;
    const form = root.querySelector('[data-barcode-form]');
    const barcodeInput = root.querySelector('[data-barcode-input]');
    const scannerIdInput = root.querySelector('[data-scanner-id-input]');
    const directionInput = root.querySelector('[data-direction-input]');
    const scanStatus = root.querySelector('[data-scan-status]');
    const lastScan = root.querySelector('[data-last-scan]');
    const history = root.querySelector('[data-scan-history]');
    const openCameraButton = root.querySelector('[data-open-native-camera]');
    const cameraStatus = root.querySelector('[data-camera-status]');
    const cameraPreview = root.querySelector('[data-camera-preview]');
    const cameraPreviewImage = root.querySelector('[data-camera-preview-image]');
    const cameraPath = root.querySelector('[data-camera-path]');
    const historyKey = 'coldstore-scan-history';

    const state = {
        history: JSON.parse(window.localStorage.getItem(historyKey) ?? '[]'),
    };

    const persistHistory = () => {
        window.localStorage.setItem(historyKey, JSON.stringify(state.history.slice(0, 20)));
    };

    const renderHistory = () => {
        history.innerHTML = state.history
            .map((entry) => {
                return `
                    <article class="track-row">
                        <span>
                            <strong>${entry.barcode_id}</strong>
                            <small>${entry.direction} · ${new Date(entry.scanned_at).toLocaleString('de-DE')}</small>
                        </span>
                        <span>
                            <strong>${entry.status}</strong>
                            <small>${entry.remote_message ?? ''}</small>
                        </span>
                    </article>
                `;
            })
            .join('');
    };

    const renderLastScan = (entry = null) => {
        if (!entry) {
            lastScan.innerHTML = '<dt>Status</dt><dd>Noch kein Scan gesendet.</dd>';

            return;
        }

        lastScan.innerHTML = [
            ['Barcode', entry.barcode_id],
            ['Scanner', entry.scanner_id],
            ['Richtung', entry.direction],
            ['Zeit', new Date(entry.scanned_at).toLocaleString('de-DE')],
            ['Status', entry.status],
            ['Antwort', entry.remote_message ?? '-'],
        ]
            .map(([label, value]) => `<dt>${label}</dt><dd>${value}</dd>`)
            .join('');
    };

    const updateCameraPreview = (path = null) => {
        if (!cameraPreview || !cameraPreviewImage || !cameraPath) {
            return;
        }

        if (!path) {
            cameraPreview.hidden = true;
            cameraPreviewImage.removeAttribute('src');
            cameraPath.textContent = 'Noch kein Foto aufgenommen.';

            return;
        }

        cameraPreview.hidden = false;
        cameraPreviewImage.src = path;
        cameraPath.textContent = path;
    };

    const sendBarcode = async (payload) => {
        scanStatus.textContent = 'Sende Barcode an den anderen PC ...';

        try {
            const response = await fetch(barcodeEndpoint, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            const json = await response.json();

            if (!response.ok) {
                throw new Error(json.message ?? 'Barcode konnte nicht gesendet werden.');
            }

            const entry = {
                barcode_id: json.scan.barcode_id,
                scanner_id: json.scan.scanner_id,
                direction: json.scan.direction,
                scanned_at: json.scan.scanned_at,
                status: 'gesendet',
                remote_message: json.remote_response?.message ?? json.message,
            };

            state.history.unshift(entry);
            persistHistory();
            renderHistory();
            renderLastScan(entry);

            scanStatus.textContent = json.message;
            form.reset();
            scannerIdInput.value = window.coldstoreScannerConfig?.scannerId ?? 'coldstore-entry-01';
            directionInput.value = window.coldstoreScannerConfig?.scanDirection ?? 'entry';
        } catch (error) {
            const failedEntry = {
                barcode_id: payload.barcode_id,
                scanner_id: payload.scanner_id,
                direction: payload.direction,
                scanned_at: payload.scanned_at,
                status: 'fehler',
                remote_message: error.message,
            };

            state.history.unshift(failedEntry);
            persistHistory();
            renderHistory();
            renderLastScan(failedEntry);
            scanStatus.textContent = error.message;
        }
    };

    const handlePhotoTaken = (payload) => {
        const parsedPayload = typeof payload === 'object' && payload !== null ? payload : {};
        const photoPathValue = typeof payload === 'string' ? payload : parsedPayload.path ?? '';

        if (!photoPathValue) {
            cameraStatus.textContent = 'Foto aufgenommen, aber ohne Dateipfad zurueckgemeldet.';

            return;
        }

        cameraStatus.textContent = 'Kamera-Test erfolgreich. Foto wurde lokal aufgenommen.';
        updateCameraPreview(photoPathValue);
    };

    const handlePhotoCancelled = () => {
        cameraStatus.textContent = 'Kamera-Test abgebrochen.';
    };

    const handleCameraPermissionDenied = () => {
        cameraStatus.textContent = 'Kamera-Berechtigung wurde verweigert.';
    };

    On(Events.Camera.PhotoTaken, handlePhotoTaken);
    On(Events.Camera.PhotoCancelled, handlePhotoCancelled);
    On(Events.Camera.PermissionDenied, handleCameraPermissionDenied);

    window.addEventListener('beforeunload', () => {
        Off(Events.Camera.PhotoTaken, handlePhotoTaken);
        Off(Events.Camera.PhotoCancelled, handlePhotoCancelled);
        Off(Events.Camera.PermissionDenied, handleCameraPermissionDenied);
    });

    openCameraButton?.addEventListener('click', async () => {
        cameraStatus.textContent = 'Oeffne Kamera fuer den Geraetetest ...';
        updateCameraPreview();

        try {
            await Camera.getPhoto()
                .id(scannerIdInput.value.trim() || window.coldstoreScannerConfig?.scannerId || 'coldstore-entry-01');
        } catch (error) {
            cameraStatus.textContent = 'Kamera konnte nicht gestartet werden. Im Browser ausserhalb der App ist das normal. Nutze alternativ das Eingabefeld.';
        }
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();

        await sendBarcode({
            barcode_id: barcodeInput.value.trim(),
            scanner_id: scannerIdInput.value.trim() || window.coldstoreScannerConfig?.scannerId || 'coldstore-entry-01',
            direction: directionInput.value || window.coldstoreScannerConfig?.scanDirection || 'entry',
            scanned_at: new Date().toISOString(),
        });
    });

    renderHistory();
    renderLastScan(state.history[0] ?? null);
}
