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
    const initialJobs = window.coldstoreDashboardInitialJobs ?? {};
    const jobsApi = window.coldstoreDashboardJobsApi ?? {
        dataSource: 'local',
        baseUrl: null,
        jobsPath: '/api/coldstore/jobs',
    };
    const initialFrameId = initialOverview.meta?.frame_id ?? 'coldstore-map';
    const state = {
        overview: initialOverview,
        jobsData: initialJobs,
        jobLines: window.coldstoreDashboardJobLines ?? [],
        jobsError: null,
        jobsLoading: Boolean(initialJobs.meta?.loading),
        activeDashboardScreen: 'overview',
        filter: '',
        selectedMatchingUid: null,
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
    const jobOrder = root.querySelector('[data-job-order]');
    const selectedLineLabel = root.querySelector('[data-selected-line-label]');
    const selectedLineValue = root.querySelector('[data-job-selected-line]');
    const selectedWorkplace = root.querySelector('[data-job-workplace]');
    const selectedJobSource = root.querySelector('[data-job-source]');
    const coldstoreName = root.querySelector('[data-coldstore-name]');
    const coldstoreSummary = root.querySelector('[data-coldstore-summary]');
    const dashboardScreens = root.querySelectorAll('[data-dashboard-screen]');
    const dashboardScreenButtons = root.querySelectorAll('[data-select-dashboard-screen]');
    const linePicker = root.querySelector('[data-line-picker]');
    const linePickerToggle = root.querySelector('[data-toggle-line-picker]');
    const linePickerMenu = root.querySelector('[data-line-picker-menu]');
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

    linePickerToggle?.addEventListener('click', () => {
        const expanded = linePickerToggle.getAttribute('aria-expanded') === 'true';

        setLinePickerOpen(!expanded);
    });

    document.addEventListener('click', (event) => {
        if (!linePicker?.contains(event.target)) {
            setLinePickerOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setLinePickerOpen(false);
        }
    });

    dashboardScreenButtons.forEach((button) => {
        button.addEventListener('click', () => {
            state.activeDashboardScreen = button.dataset.selectDashboardScreen ?? 'overview';
            renderDashboardScreens();
        });
    });

    function setLinePickerOpen(isOpen) {
        linePickerToggle?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

        if (linePickerMenu) {
            linePickerMenu.hidden = !isOpen;
        }
    }

    function renderDashboardScreens() {
        dashboardScreens.forEach((screen) => {
            screen.hidden = screen.dataset.dashboardScreen !== state.activeDashboardScreen;
        });

        dashboardScreenButtons.forEach((button) => {
            const isActive = button.dataset.selectDashboardScreen === state.activeDashboardScreen;

            button.classList.toggle('dashboard-switcher__item--active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function activeFrameId(overview = state.overview) {
        return overview.meta?.frame_id ?? 'coldstore-map';
    }

    function selectedLineOption() {
        return state.jobLines.find((line) => line.line === Number(state.jobsData.selected_line)) ?? null;
    }

    function jobsWorkplaceNumberForLine(selectedLine) {
        return state.jobLines.find((line) => line.line === Number(selectedLine))?.workplace_number ?? null;
    }

    function findSelectedMatchingUid() {
        return (state.jobsData.matching_uids ?? []).find((entry) => entry.uid === state.selectedMatchingUid) ?? null;
    }

    function buildJobsUrl(selectedLine) {
        const jobsPath = jobsApi.jobsPath ?? '/api/coldstore/jobs';

        if (jobsApi.dataSource === 'remote_api') {
            if (!jobsApi.baseUrl) {
                throw new Error('Backend nicht erreichbar');
            }

            return `${jobsApi.baseUrl}${jobsPath}?line=${selectedLine}`;
        }

        return `${jobsPath}?line=${selectedLine}`;
    }

    function buildLoadingJobsPayload(selectedLine) {
        return {
            selected_line: selectedLine,
            arbeitsplatz_nr: jobsWorkplaceNumberForLine(selectedLine),
            order: null,
            next_order: null,
            matching_uids: [],
            next_matching_uids: [],
            meta: {
                source_mode: jobsApi.dataSource === 'remote_api' ? 'remote_api' : 'local',
                loading: true,
            },
        };
    }

    function buildEmptyJobsPayload(selectedLine) {
        return {
            selected_line: selectedLine,
            arbeitsplatz_nr: jobsWorkplaceNumberForLine(selectedLine),
            order: null,
            next_order: null,
            matching_uids: [],
            next_matching_uids: [],
            meta: {
                source_mode: jobsApi.dataSource === 'remote_api' ? 'remote_api' : 'local',
            },
        };
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

            if (!findSelectedTrack()) {
                state.selectedTrackId = state.overview.overview?.selected_track_id ?? state.overview.tracks?.[0]?.track_id ?? null;
            }

            render();
        } catch (error) {
            subtitle.textContent = error.message;
        } finally {
            refreshButton?.removeAttribute('disabled');
        }
    }

    async function refreshJobs(selectedLine) {
        state.jobsError = null;
        state.jobsLoading = true;
        state.jobsData = buildLoadingJobsPayload(selectedLine);
        render();

        try {
            const response = await fetch(buildJobsUrl(selectedLine), {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Jobs konnten nicht geladen werden.');
            }

            state.jobsData = await response.json();
            state.jobsLoading = false;
            state.selectedMatchingUid = null;
            setLinePickerOpen(false);
            render();
        } catch (error) {
            console.error('Jobs fetch failed.', error);
            state.jobsLoading = false;
            state.jobsData = buildEmptyJobsPayload(selectedLine);
            state.jobsError = jobsApi.dataSource === 'remote_api'
                ? 'Backend nicht erreichbar'
                : (error.message ?? 'Jobs konnten nicht geladen werden.');
            render();
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

    function setTrackFilter(value) {
        state.filter = value.toLowerCase();

        if (filterInput) {
            filterInput.value = value;
        }
    }

    function selectMatchingUid(uid) {
        state.selectedMatchingUid = uid;

        const selectedUid = findSelectedMatchingUid();

        if (selectedUid?.track_id) {
            state.selectedTrackId = selectedUid.track_id;
            setTrackFilter(String(selectedUid.track_id));
        }

        render();
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

    function renderLinePicker() {
        const activeLine = Number(state.jobsData.selected_line);
        const activeLineOption = selectedLineOption();

        if (selectedLineLabel) {
            selectedLineLabel.textContent = activeLineOption?.label ?? `Linie ${activeLine}`;
        }

        if (!linePickerMenu) {
            return;
        }

        linePickerMenu.innerHTML = state.jobLines
            .map((line) => {
                const selected = line.line === activeLine;

                return `
                    <button
                        class="line-picker__option ${selected ? 'line-picker__option--active' : ''}"
                        type="button"
                        data-select-line="${line.line}"
                    >
                        <span>${line.label}</span>
                        <small>AP ${line.workplace_number}</small>
                    </button>
                `;
            })
            .join('');

        linePickerMenu.querySelectorAll('[data-select-line]').forEach((button) => {
            button.addEventListener('click', async () => {
                await refreshJobs(Number(button.dataset.selectLine));
            });
        });
    }

    function renderJobMeta() {
        const activeLineOption = selectedLineOption();
        const selectedLine = Number(state.jobsData.selected_line);

        if (selectedLineValue) {
            selectedLineValue.textContent = activeLineOption?.label ?? `Linie ${selectedLine}`;
        }

        if (selectedWorkplace) {
            selectedWorkplace.textContent = String(state.jobsData.arbeitsplatz_nr ?? '-');
        }

        if (selectedJobSource) {
            selectedJobSource.textContent = state.jobsData.meta?.source_mode ?? 'unknown';
        }
    }

    function renderJobOrder() {
        const order = state.jobsData.order;
        const nextOrder = state.jobsData.next_order;
        const nextMatchingUids = state.jobsData.next_matching_uids ?? [];

        if (!jobOrder) {
            return;
        }

        if (state.jobsLoading) {
            jobOrder.innerHTML = `
                <div class="job-order-card__empty">
                    <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                    <p>Jobs werden geladen ...</p>
                </div>
            `;

            return;
        }

        if (!order) {
            jobOrder.innerHTML = `
                <div class="job-order-card__empty">
                    <p class="panel-card__eyebrow">Aktueller Auftrag</p>
                    <p>Für diese Linie liegt aktuell kein offener Auftrag mit VA_Status 2 vor.</p>
                </div>
            `;

            return;
        }

        jobOrder.innerHTML = `
            ${renderOrderSection(order, 'Aktueller Auftrag')}
            ${nextOrder ? renderOrderSection(nextOrder, 'Folgeauftrag', nextMatchingUids) : renderNoNextOrder()}
        `;
    }

    function renderOrderSection(order, label, matchingUids = null) {
        return `
            <section class="job-order-card__section">
                <div class="job-order-card__header">
                    <div>
                        <p class="panel-card__eyebrow">${label}</p>
                        <h3 class="panel-card__title">${order.va_auftragsnr}</h3>
                    </div>
                    <span class="status-pill status-pill--ok">VA_Status ${order.va_status}</span>
                </div>
                <dl class="detail-grid detail-grid--compact">
                    <dt>Produktname</dt>
                    <dd>${order.required_product_name ?? order.matstamm_maktx}</dd>
                    <dt>MatStamm MatNr</dt>
                    <dd>${order.matstamm_matnr}</dd>
                    <dt>MatStamm_FuellArtNr</dt>
                    <dd>${order.matstamm_fuellartnr}</dd>
                    <dt>Required_PEText1</dt>
                    <dd>${order.required_pe_text1}</dd>
                    <dt>Menge</dt>
                    <dd>${formatOrderQuantity(order.va_menge_kg)}</dd>
                    <dt>Beginn Soll</dt>
                    <dd>${order.va_beginn_soll}</dd>
                </dl>
                ${matchingUids === null ? '' : renderNextMatchingUids(matchingUids)}
            </section>
        `;
    }

    function formatOrderQuantity(quantity) {
        if (quantity === null || quantity === undefined || Number.isNaN(Number(quantity))) {
            return 'unbekannt';
        }

        return `${Number(quantity).toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })} kg`;
    }

    function renderNextMatchingUids(matchingUids) {
        if (matchingUids.length === 0) {
            return '<p class="panel-card__muted job-order-card__note">Keine passende UID fuer den Folgeauftrag gefunden.</p>';
        }

        return `
            <div class="job-order-card__matches">
                ${matchingUids
            .map((matchingUid) => `
                        <span>
                            <strong>${matchingUid.uid}</strong>
                            <small>PEText1 ${matchingUid.etikinterface_pe_text1}</small>
                        </span>
                    `)
            .join('')}
            </div>
        `;
    }

    function renderNoNextOrder() {
        return `
            <section class="job-order-card__section job-order-card__empty">
                <p class="panel-card__eyebrow">Folgeauftrag</p>
                <p>Kein freigegebener Folgeauftrag vorhanden</p>
            </section>
        `;
    }

    function renderJobStatus() {
        if (state.jobsLoading) {
            jobStatus.textContent = 'Jobs werden geladen ...';

            return;
        }

        if (state.jobsError) {
            jobStatus.textContent = state.jobsError;

            return;
        }

        if (!state.jobsData.order) {
            jobStatus.textContent = 'Für diese Linie liegt aktuell kein offener Auftrag mit VA_Status 2 vor.';

            return;
        }

        const matchingUids = state.jobsData.matching_uids ?? [];

        if (matchingUids.length === 0) {
            jobStatus.textContent = 'Kein passender UID-Bestand im Kühlhaus gefunden.';

            return;
        }

        const selectedUid = findSelectedMatchingUid();

        if (!selectedUid) {
            jobStatus.textContent = `${matchingUids.length} passende UID(s) im Kühlhaus gefunden.`;

            return;
        }

        if (!selectedUid.track_id) {
            jobStatus.textContent = `UID ${selectedUid.uid} ist passend, aber aktuell keinem Track zugeordnet.`;

            return;
        }

        jobStatus.textContent = `UID ${selectedUid.uid} nutzt die bestehende Track-Markierung fuer Track ${selectedUid.track_id}.`;
    }

    function renderJobs() {
        const matchingUids = state.jobsData.matching_uids ?? [];

        if (state.jobsLoading) {
            jobsList.innerHTML = `
                <article class="job-row job-row--empty">
                    <span>
                        <strong>Jobs werden geladen ...</strong>
                        <small>Die Auftragsdaten werden vom Backend abgerufen.</small>
                    </span>
                </article>
            `;
            renderJobStatus();

            return;
        }

        if (matchingUids.length === 0) {
            jobsList.innerHTML = `
                <article class="job-row job-row--empty">
                    <span>
                        <strong>Keine passende UID</strong>
                        <small>Im aktuellen Kühlhausbestand wurde noch kein Treffer gefunden.</small>
                    </span>
                </article>
            `;
            renderJobStatus();

            return;
        }

        jobsList.innerHTML = matchingUids
            .map((matchingUid) => {
                const selected = matchingUid.uid === state.selectedMatchingUid;
                const hasTrackAssignment = Boolean(matchingUid.track_id);

                return `
                    <button
                        class="job-row ${selected ? 'job-row--active' : ''} ${hasTrackAssignment ? '' : 'job-row--muted'}"
                        type="button"
                        data-select-job-uid="${matchingUid.uid}"
                    >
                        <span>
                            <strong>${matchingUid.uid}</strong>
                            <small>PEText1 ${matchingUid.etikinterface_pe_text1}</small>
                        </span>
                        <span>
                            <strong>${hasTrackAssignment ? `Track ${matchingUid.track_id}` : 'Kein Track'}</strong>
                            <small>${matchingUid.state}</small>
                        </span>
                    </button>
                `;
            })
            .join('');

        jobsList.querySelectorAll('[data-select-job-uid]').forEach((button) => {
            button.addEventListener('click', () => {
                selectMatchingUid(button.dataset.selectJobUid);
            });
        });

        renderJobStatus();
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
                            <strong>${selected ? 'Ausgewählt' : 'Track'}</strong>
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
                <dd>Bitte einen Track aus Liste oder Karte auswählen.</dd>
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

        if (coldstoreName) {
            coldstoreName.textContent = state.overview.coldstore?.name ?? '';
        }

        if (coldstoreSummary) {
            coldstoreSummary.textContent = state.overview.coldstore?.summary ?? '';
        }
    }

    function render() {
        renderMeta();
        renderDashboardScreens();
        renderLinePicker();
        renderJobMeta();
        renderJobOrder();
        renderJobs();
        renderMap();
        renderTrackList();
        renderSections();
        renderDetails();
    }

    syncMapBackgroundCache();
    syncTrackSessionSeenAt();
    render();

    if (jobsApi.dataSource === 'remote_api') {
        void refreshJobs(Number(state.jobsData.selected_line));
    }

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
            cameraStatus.textContent = 'Foto aufgenommen, aber ohne Dateipfad zurückgemeldet.';

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
        cameraStatus.textContent = 'Öffne Kamera für den Gerätetest ...';
        updateCameraPreview();

        try {
            await Camera.getPhoto()
                .id(scannerIdInput.value.trim() || window.coldstoreScannerConfig?.scannerId || 'coldstore-entry-01');
        } catch (error) {
            cameraStatus.textContent = 'Kamera konnte nicht gestartet werden. Im Browser außerhalb der App ist das normal. Nutze alternativ das Eingabefeld.';
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
