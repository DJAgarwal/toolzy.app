@extends('layouts.app')

{{-- Force Recompile --}}
@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <!-- Current Timestamp Widget -->
    <div class="card mb-4 shadow-sm border-primary border-opacity-25 bg-opacity-10">
        <div class="card-body py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-4 text-center text-lg-start border-lg-end">
                    <div class="small text-uppercase fw-bold text-primary mb-1">Current Unix Timestamp</div>
                    <div id="currentTimestamp" class="h1 fw-bold mb-0 font-monospace">1780840231</div>
                    <div class="small text-muted mt-1">Seconds since Jan 1, 1970</div>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="small text-muted text-uppercase fw-bold x-small">UTC Time</div>
                            <div id="currentUtc" class="fw-bold font-monospace">2026-06-07 12:30:31 UTC</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small text-muted text-uppercase fw-bold x-small">Local Time</div>
                            <div id="currentLocal" class="fw-bold font-monospace text-primary">2026-06-07 18:00:31</div>
                        </div>
                        <div class="col-sm-12">
                            <div class="small text-muted text-uppercase fw-bold x-small">ISO 8601</div>
                            <div id="currentIso" class="small font-monospace">2026-06-07T12:30:31.000Z</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Conversion Area -->
        <div class="col-lg-8">
            <!-- Timestamp to Date -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-right-circle me-2 text-primary"></i>Unix Timestamp <i class="bi bi-arrow-right mx-2 text-muted small"></i> Human Date</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="tsInput" class="form-label small fw-bold text-uppercase text-muted">Enter Unix Timestamp</label>
                            <div class="input-group">
                                <input type="text" id="tsInput" class="form-control form-control-lg font-monospace" placeholder="1780840231">
                                <button id="useCurrentTs" class="btn btn-outline-secondary" title="Use Current"><i class="bi bi-clock-history"></i></button>
                                <button id="clearTs" class="btn btn-outline-secondary" title="Clear"><i class="bi bi-x-lg"></i></button>
                            </div>
                            <div id="tsDetection" class="small mt-1 text-primary italic">Detected as seconds</div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button id="convertTsBtn" class="btn btn-primary btn-lg w-100">Convert</button>
                        </div>
                    </div>

                    <div id="tsResults" class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted text-uppercase fw-bold x-small mb-1">UTC Time</div>
                                <div id="resUtc" class="fw-bold font-monospace fs-5">-</div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-secondary x-small copy-btn" data-copy-target="resUtc"><i class="bi bi-clipboard me-1"></i>Copy</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted text-uppercase fw-bold x-small mb-1">Local Time</div>
                                <div id="resLocal" class="fw-bold font-monospace fs-5">-</div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-secondary x-small copy-btn" data-copy-target="resLocal"><i class="bi bi-clipboard me-1"></i>Copy</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0 small">
                                    <tbody>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small" width="150">ISO 8601</td><td id="resIso" class="font-monospace">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">RFC 2822</td><td id="resRfc">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Relative</td><td id="resRelative">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Day of Week</td><td id="resDay">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Day of Year</td><td id="resDayYear">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Week Number</td><td id="resWeek">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Quarter</td><td id="resQuarter">-</td></tr>
                                        <tr><td class="fw-bold text-muted text-uppercase x-small">Leap Year</td><td id="resLeap">-</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date to Timestamp -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-date me-2 text-success"></i>Human Date <i class="bi bi-arrow-right mx-2 text-muted small"></i> Unix Timestamp</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="dateInput" class="form-label small fw-bold text-uppercase text-muted">Date</label>
                            <input type="date" id="dateInput" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="timeInput" class="form-label small fw-bold text-uppercase text-muted">Time</label>
                            <input type="time" id="timeInput" class="form-control" step="1">
                        </div>
                        <div class="col-md-4">
                            <label for="tzSelect" class="form-label small fw-bold text-uppercase text-muted">Timezone</label>
                            <select id="tzSelect" class="form-select">
                                <option value="UTC">UTC / GMT</option>
                                <option value="local" selected>Local Time</option>
                            </select>
                        </div>
                    </div>

                    <div id="dateResults" class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="small text-muted text-uppercase fw-bold x-small mb-1">Unix Timestamp (Seconds)</div>
                                <div id="resSec" class="h3 fw-bold font-monospace text-success">-</div>
                                <button class="btn btn-sm btn-outline-success x-small mt-2 copy-btn" data-copy-target="resSec"><i class="bi bi-clipboard me-1"></i>Copy</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="small text-muted text-uppercase fw-bold x-small mb-1">Milliseconds</div>
                                <div id="resMs" class="h3 fw-bold font-monospace text-muted">-</div>
                                <button class="btn btn-sm btn-outline-secondary x-small mt-2 copy-btn" data-copy-target="resMs"><i class="bi bi-clipboard me-1"></i>Copy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Difference Calculator -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-hourglass-split me-2 text-warning"></i>Time Difference Calculator</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label for="diffStart" class="form-label small fw-bold text-uppercase text-muted">Start Timestamp</label>
                            <input type="text" id="diffStart" class="form-control font-monospace" placeholder="1717400000">
                        </div>
                        <div class="col-md-5">
                            <label for="diffEnd" class="form-label small fw-bold text-uppercase text-muted">End Timestamp</label>
                            <input type="text" id="diffEnd" class="form-control font-monospace" placeholder="1717486400">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="calcDiffBtn" class="btn btn-outline-primary w-100">Calculate</button>
                        </div>
                    </div>

                    <div id="diffResults" class="d-none">
                        <div class="p-3 border rounded bg-light">
                            <div class="row text-center g-3">
                                <div class="col-4 col-md-2">
                                    <div class="h4 mb-0 fw-bold" id="diffDays">0</div>
                                    <div class="small text-muted text-uppercase x-small">Days</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="h4 mb-0 fw-bold" id="diffHours">0</div>
                                    <div class="small text-muted text-uppercase x-small">Hours</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="h4 mb-0 fw-bold" id="diffMins">0</div>
                                    <div class="small text-muted text-uppercase x-small">Mins</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="h4 mb-0 fw-bold" id="diffSecs">0</div>
                                    <div class="small text-muted text-uppercase x-small">Secs</div>
                                </div>
                                <div class="col-8 col-md-4 border-start">
                                    <div class="h4 mb-0 fw-bold text-primary" id="diffTotalSecs">0</div>
                                    <div class="small text-muted text-uppercase x-small">Total Seconds</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Quick Generator -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lightning me-2"></i>Quick Generator</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-secondary text-start gen-btn" data-gen-type="now">Current Timestamp</button>
                        <button class="btn btn-sm btn-outline-secondary text-start gen-btn" data-gen-type="dayStart">Start of Today</button>
                        <button class="btn btn-sm btn-outline-secondary text-start gen-btn" data-gen-type="dayEnd">End of Today</button>
                        <button class="btn btn-sm btn-outline-secondary text-start gen-btn" data-gen-type="monthStart">Start of Month</button>
                        <button class="btn btn-sm btn-outline-secondary text-start gen-btn" data-gen-type="yearStart">Start of Year</button>
                    </div>
                    <hr>
                    <div class="row g-2">
                        <div class="col-6"><button class="btn btn-sm btn-outline-primary w-100 add-btn" data-add-amount="3600">+1 Hour</button></div>
                        <div class="col-6"><button class="btn btn-sm btn-outline-primary w-100 add-btn" data-add-amount="86400">+1 Day</button></div>
                        <div class="col-6"><button class="btn btn-sm btn-outline-primary w-100 add-btn" data-add-amount="604800">+1 Week</button></div>
                        <div class="col-6"><button class="btn btn-sm btn-outline-primary w-100 add-btn" data-add-amount="2592000">+30 Days</button></div>
                    </div>
                </div>
            </div>

            <!-- Common Examples -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2"></i>Milestones</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small" id="milestonesList">
                        <button class="list-group-item list-group-item-action py-2 load-btn" data-load-ts="0">
                            <div class="fw-bold">Unix Epoch</div>
                            <code class="x-small">0</code> (1970-01-01)
                        </button>
                        <button class="list-group-item list-group-item-action py-2 load-btn" data-load-ts="946684800">
                            <div class="fw-bold">Y2K Milestone</div>
                            <code class="x-small">946684800</code> (2000-01-01)
                        </button>
                        <button class="list-group-item list-group-item-action py-2 load-btn" data-load-ts="1234567890">
                            <div class="fw-bold">1234567890</div>
                            <code class="x-small">1234567890</code> (2009-02-13)
                        </button>
                        <button class="list-group-item list-group-item-action py-2 load-btn" data-load-ts="2147483647">
                            <div class="fw-bold text-danger">Year 2038 Problem</div>
                            <code class="x-small">2147483647</code> (Last 32-bit int)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content Section -->
    <div class="mt-5 border-top pt-5">
        <h2 class="h4 fw-bold mb-4">Deep Dive into Unix Timestamps</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-4">
                    <h5 class="fw-bold">What is a Unix Timestamp?</h5>
                    <p class="text-muted small">Unix time (also known as Epoch time or POSIX time) is a system for describing a point in time. It is the number of seconds that have elapsed since the Unix epoch, which is 00:00:00 UTC on 1 January 1970, minus leap seconds. This format is popular because it reduces date math to simple integer operations.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold">The Year 2038 Problem (Y2K38)</h5>
                    <p class="text-muted small">The Y2K38 problem arises from the fact that many older computer systems store Unix time as a signed 32-bit integer. The maximum value for this integer is 2,147,483,647, which will be reached on January 19, 2038. After this point, the timestamp will "wrap around" to a negative value, causing systems to fail. Modern 64-bit systems are immune to this, as they can store dates far beyond the life span of our solar system.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <h5 class="fw-bold">Milliseconds vs. Seconds</h5>
                    <p class="text-muted small">While the standard Unix timestamp uses seconds, many high-precision environments (like JavaScript or Java) use milliseconds. A 13-digit timestamp is likely milliseconds, while a 10-digit one is seconds. Our tool automatically detects these formats to provide the correct conversion.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold">Timezones and UTC</h5>
                    <p class="text-muted small">A core rule of working with timestamps is: <strong>Store in UTC, display in Local Time.</strong> Unix timestamps are always UTC-based by definition. This makes them perfectly neutral for cross-border data synchronization and logging.</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <x-what-is :tool="$tool" />
            <x-faq :tool="$tool" />
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.x-small {
    font-size: 0.75rem;
}

.italic {
    font-style: italic;
}

#currentTimestamp {
    color: #0d6efd;
    letter-spacing: -1px;
}

.border-lg-end {
    border-right: none;
}

@media (min-width: 992px) {
    .border-lg-end {
        border-right: 1px solid #dee2e6;
    }
}

.list-group-item code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    color: #e83e8c;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const TimestampTool = (function() {
    let clockInterval;
    
    const elements = {
        clock: document.getElementById('currentTimestamp'),
        clockUtc: document.getElementById('currentUtc'),
        clockLocal: document.getElementById('currentLocal'),
        clockIso: document.getElementById('currentIso'),
        tsInput: document.getElementById('tsInput'),
        tsDetection: document.getElementById('tsDetection'),
        resUtc: document.getElementById('resUtc'),
        resLocal: document.getElementById('resLocal'),
        resIso: document.getElementById('resIso'),
        resRfc: document.getElementById('resRfc'),
        resRelative: document.getElementById('resRelative'),
        resDay: document.getElementById('resDay'),
        resDayYear: document.getElementById('resDayYear'),
        resWeek: document.getElementById('resWeek'),
        resQuarter: document.getElementById('resQuarter'),
        resLeap: document.getElementById('resLeap'),
        dateInput: document.getElementById('dateInput'),
        timeInput: document.getElementById('timeInput'),
        tzSelect: document.getElementById('tzSelect'),
        resSec: document.getElementById('resSec'),
        resMs: document.getElementById('resMs'),
        diffStart: document.getElementById('diffStart'),
        diffEnd: document.getElementById('diffEnd'),
        diffResults: document.getElementById('diffResults')
    };

    function init() {
        startClock();
        bindEvents();
        setDefaultDates();
        
        // Initial conversion if URL param exists
        const params = new URLSearchParams(window.location.search);
        if (params.has('t')) {
            elements.tsInput.value = params.get('t');
            convertTimestamp();
        }
    }

    function bindEvents() {
        elements.tsInput.addEventListener('input', () => {
            detectTimestamp();
            convertTimestamp();
        });
        document.getElementById('convertTsBtn').addEventListener('click', convertTimestamp);
        document.getElementById('useCurrentTs').addEventListener('click', () => {
            elements.tsInput.value = Math.floor(Date.now() / 1000);
            detectTimestamp();
            convertTimestamp();
        });
        document.getElementById('clearTs').addEventListener('click', () => {
            elements.tsInput.value = '';
            elements.tsDetection.textContent = 'Enter a timestamp';
        });

        elements.dateInput.addEventListener('input', convertDate);
        elements.timeInput.addEventListener('input', convertDate);
        elements.tzSelect.addEventListener('change', convertDate);

        document.getElementById('calcDiffBtn').addEventListener('click', calculateDifference);

        // Copy buttons
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                copyText(btn.getAttribute('data-copy-target'));
            });
        });

        // Gen buttons
        document.querySelectorAll('.gen-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                gen(btn.getAttribute('data-gen-type'));
            });
        });

        // Add buttons
        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                add(parseInt(btn.getAttribute('data-add-amount')));
            });
        });

        // Load buttons
        document.querySelectorAll('.load-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                load(btn.getAttribute('data-load-ts'));
            });
        });
    }

    function startClock() {
        const update = () => {
            const now = new Date();
            elements.clock.textContent = Math.floor(now.getTime() / 1000);
            elements.clockUtc.textContent = now.toUTCString().replace('GMT', 'UTC');
            elements.clockLocal.textContent = now.toLocaleString();
            elements.clockIso.textContent = now.toISOString();
        };
        update();
        clockInterval = setInterval(update, 1000);
    }

    function setDefaultDates() {
        const now = new Date();
        elements.dateInput.value = now.toISOString().split('T')[0];
        elements.timeInput.value = now.toTimeString().split(' ')[0];
        convertDate();
    }

    function detectTimestamp() {
        const val = elements.tsInput.value.trim();
        if (!val) {
            elements.tsDetection.textContent = 'Enter a timestamp';
            return;
        }
        const len = val.length;
        if (len <= 11) elements.tsDetection.textContent = 'Detected as seconds';
        else if (len <= 14) elements.tsDetection.textContent = 'Detected as milliseconds';
        else if (len <= 17) elements.tsDetection.textContent = 'Detected as microseconds';
        else elements.tsDetection.textContent = 'Detected as nanoseconds';
    }

    function convertTimestamp() {
        const val = elements.tsInput.value.trim();
        if (!val) return;

        let ts = parseFloat(val);
        const len = val.replace('-', '').length;

        // Normalize to milliseconds
        if (len <= 11) ts *= 1000; // sec -> ms
        else if (len <= 14) {} // ms -> ms
        else if (len <= 17) ts /= 1000; // us -> ms
        else ts /= 1000000; // ns -> ms

        const date = new Date(ts);
        if (isNaN(date.getTime())) return;

        elements.resUtc.textContent = date.toUTCString().replace('GMT', 'UTC');
        elements.resLocal.textContent = date.toLocaleString();
        elements.resIso.textContent = date.toISOString();
        elements.resRfc.textContent = date.toString();
        elements.resRelative.textContent = getRelativeTime(date);
        elements.resDay.textContent = new Intl.DateTimeFormat('en-US', { weekday: 'long' }).format(date);
        
        // Day of Year
        const start = new Date(date.getFullYear(), 0, 0);
        const diff = date - start;
        const oneDay = 1000 * 60 * 60 * 24;
        const dayYear = Math.floor(diff / oneDay);
        elements.resDayYear.textContent = dayYear;

        // Week Number
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        const weekNo = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        elements.resWeek.textContent = weekNo;

        elements.resQuarter.textContent = 'Q' + (Math.floor(date.getMonth() / 3) + 1);
        
        const year = date.getFullYear();
        const isLeap = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
        elements.resLeap.textContent = isLeap ? 'Yes' : 'No';

        // Update URL
        const url = new URL(window.location);
        url.searchParams.set('t', val);
        window.history.replaceState({}, '', url);
    }

    function convertDate() {
        const dateVal = elements.dateInput.value;
        const timeVal = elements.timeInput.value;
        if (!dateVal || !timeVal) return;

        let date;
        if (elements.tzSelect.value === 'UTC') {
            date = new Date(dateVal + 'T' + timeVal + 'Z');
        } else {
            date = new Date(dateVal + 'T' + timeVal);
        }

        if (isNaN(date.getTime())) return;

        const sec = Math.floor(date.getTime() / 1000);
        elements.resSec.textContent = sec;
        elements.resMs.textContent = date.getTime();
    }

    function calculateDifference() {
        const startVal = elements.diffStart.value.trim();
        const endVal = elements.diffEnd.value.trim();
        if (!startVal || !endVal) return;

        const parse = (v) => {
            let t = parseFloat(v);
            if (v.length <= 11) t *= 1000;
            return t;
        };

        const start = parse(startVal);
        const end = parse(endVal);
        const diffMs = Math.abs(end - start);
        const diffSecs = Math.floor(diffMs / 1000);

        elements.diffResults.classList.remove('d-none');
        document.getElementById('diffTotalSecs').textContent = diffSecs.toLocaleString();
        document.getElementById('diffDays').textContent = Math.floor(diffSecs / 86400);
        document.getElementById('diffHours').textContent = Math.floor((diffSecs % 86400) / 3600);
        document.getElementById('diffMins').textContent = Math.floor((diffSecs % 3600) / 60);
        document.getElementById('diffSecs').textContent = diffSecs % 60;
    }

    function getRelativeTime(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((date - now) / 1000);
        const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

        const absDiff = Math.abs(diffInSeconds);
        if (absDiff < 60) return rtf.format(diffInSeconds, 'second');
        if (absDiff < 3600) return rtf.format(Math.floor(diffInSeconds / 60), 'minute');
        if (absDiff < 86400) return rtf.format(Math.floor(diffInSeconds / 3600), 'hour');
        if (absDiff < 2592000) return rtf.format(Math.floor(diffInSeconds / 86400), 'day');
        if (absDiff < 31536000) return rtf.format(Math.floor(diffInSeconds / 2592000), 'month');
        return rtf.format(Math.floor(diffInSeconds / 31536000), 'year');
    }

    function copyText(id) {
        const text = document.getElementById(id).textContent;
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success');
        });
    }

    function load(ts) {
        elements.tsInput.value = ts;
        detectTimestamp();
        convertTimestamp();
    }

    function gen(type) {
        const now = new Date();
        let ts;
        switch(type) {
            case 'now': ts = Math.floor(now.getTime() / 1000); break;
            case 'dayStart': ts = Math.floor(new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000); break;
            case 'dayEnd': ts = Math.floor(new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59).getTime() / 1000); break;
            case 'monthStart': ts = Math.floor(new Date(now.getFullYear(), now.getMonth(), 1).getTime() / 1000); break;
            case 'yearStart': ts = Math.floor(new Date(now.getFullYear(), 0, 1).getTime() / 1000); break;
        }
        load(ts);
    }

    function add(seconds) {
        const current = parseInt(elements.tsInput.value) || Math.floor(Date.now() / 1000);
        load(current + seconds);
    }

    return { init, copyText, load, gen, add };
})();

document.addEventListener('DOMContentLoaded', function() {
    TimestampTool.init();
});
</script>
@endpush
