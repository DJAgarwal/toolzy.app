@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Main Tool Area -->
        <div class="col-lg-8">
            <!-- Quick Schedule Builder -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-25">
                <div class="card-header bg-opacity-10 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-lightning-charge me-2"></i>Quick Schedule Builder</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="quickSelect" class="form-label small fw-bold text-uppercase text-muted">Run every...</label>
                            <select id="quickSelect" class="form-select">
                                <option value="* * * * *">Every Minute</option>
                                <option value="*/5 * * * *">Every 5 Minutes</option>
                                <option value="0 * * * *">Every Hour (at minute 0)</option>
                                <option value="0 0 * * *">Every Day at Midnight</option>
                                <option value="0 12 * * *">Every Day at Noon</option>
                                <option value="0 0 * * 0">Every Sunday at Midnight</option>
                                <option value="0 9 * * 1-5">Weekdays at 9:00 AM</option>
                                <option value="0 0 1 * *">First Day of every Month</option>
                                <option value="0 0 1 1 *">Every Year on Jan 1st</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button id="applyQuick" class="btn btn-primary w-100 py-2"><i class="bi bi-magic me-2"></i>Apply Template</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Cron Builder -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Advanced Cron Builder</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col">
                            <label class="form-label small fw-bold text-muted text-uppercase text-center d-block">Minute</label>
                            <input type="text" id="field-min" class="form-control text-center font-monospace cron-field" value="*" placeholder="0-59">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-bold text-muted text-uppercase text-center d-block">Hour</label>
                            <input type="text" id="field-hour" class="form-control text-center font-monospace cron-field" value="*" placeholder="0-23">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-bold text-muted text-uppercase text-center d-block">Day (Month)</label>
                            <input type="text" id="field-dom" class="form-control text-center font-monospace cron-field" value="*" placeholder="1-31">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-bold text-muted text-uppercase text-center d-block">Month</label>
                            <input type="text" id="field-month" class="form-control text-center font-monospace cron-field" value="*" placeholder="1-12">
                        </div>
                        <div class="col">
                            <label class="form-label small fw-bold text-muted text-uppercase text-center d-block">Day (Week)</label>
                            <input type="text" id="field-dow" class="form-control text-center font-monospace cron-field" value="*" placeholder="0-6">
                        </div>
                    </div>

                    <!-- Visual Selectors -->
                    <div id="fieldDetails" class="p-3 bg-light rounded border mb-4">
                        <div class="text-muted italic small mb-2">Click a field above to see valid options or edit syntax directly.</div>
                        <div class="d-flex flex-wrap gap-2" id="valButtons">
                            <!-- Buttons will be injected here -->
                        </div>
                    </div>

                    <!-- Generated Expression -->
                    <div class="bg-dark text-white rounded p-4 text-center position-relative mb-3">
                        <div class="small text-uppercase text-muted mb-2">Generated Cron Expression</div>
                        <div id="fullExpression" class="h2 fw-bold font-monospace mb-0 text-success">* * * * *</div>
                        <div class="mt-3 d-flex justify-content-center gap-2 flex-wrap">
                            <button id="copyExpr" class="btn btn-sm btn-outline-light"><i class="bi bi-clipboard me-1"></i>Copy Expression</button>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item small export-btn" data-format="txt">As TXT</button></li>
                                    <li><button class="dropdown-item small export-btn" data-format="json">As JSON</button></li>
                                    <li><button class="dropdown-item small export-btn" data-format="csv">As CSV</button></li>
                                </ul>
                            </div>
                            <button id="resetExpr" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Reset</button>
                        </div>
                    </div>

                    <div id="humanDesc" class="alert alert-info py-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i><strong>Description:</strong> <span id="humanText">Runs every minute.</span>
                    </div>
                </div>
            </div>

            <!-- Execution Preview -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event me-2"></i>Execution Schedule</h5>
                    <div class="d-flex align-items-center gap-2">
                        <select id="tzSelect" class="form-select form-select-sm">
                            <option value="local">Local Time</option>
                            <option value="UTC">UTC</option>
                        </select>
                        <select id="countSelect" class="form-select form-select-sm">
                            <option value="10">Next 10</option>
                            <option value="25">Next 25</option>
                            <option value="50">Next 50</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="100">Run #</th>
                                    <th>Date & Time</th>
                                    <th>Relative</th>
                                </tr>
                            </thead>
                            <tbody id="executionList">
                                <!-- Dates will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Validator/Parser -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-patch-check me-2"></i>Cron Validator & Parser</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Paste an existing cron expression to validate and parse it.</p>
                    <div class="input-group mb-3">
                        <input type="text" id="parseInput" class="form-control font-monospace" placeholder="0 9 * * 1-5">
                        <button id="parseBtn" class="btn btn-primary">Parse</button>
                    </div>
                    <div id="parseError" class="alert alert-danger d-none py-2 px-3 small"></div>
                </div>
            </div>

            <!-- Cheat Sheet -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-book me-2"></i>Cron Cheat Sheet</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tbody class="list-group list-group-flush">
                            <tr class="list-group-item d-flex justify-content-between"><td><code>*</code></td><td class="text-end text-muted">Any value</td></tr>
                            <tr class="list-group-item d-flex justify-content-between"><td><code>,</code></td><td class="text-end text-muted">Value list (1,2,5)</td></tr>
                            <tr class="list-group-item d-flex justify-content-between"><td><code>-</code></td><td class="text-end text-muted">Value range (1-5)</td></tr>
                            <tr class="list-group-item d-flex justify-content-between"><td><code>/</code></td><td class="text-end text-muted">Step values (*/10)</td></tr>
                            <tr class="list-group-item d-flex justify-content-between"><td><code>L</code></td><td class="text-end text-muted">Last day (some systems)</td></tr>
                            <tr class="list-group-item d-flex justify-content-between"><td><code>W</code></td><td class="text-end text-muted">Weekday (some systems)</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Field Ranges -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Standard Unix Format</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between"><span>Minute</span><span class="fw-bold">0-59</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Hour</span><span class="fw-bold">0-23</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Day of Month</span><span class="fw-bold">1-31</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Month</span><span class="fw-bold">1-12</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Day of Week</span><span class="fw-bold">0-6 (Sun-Sat)</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content Section -->
    <div class="mt-5 border-top pt-5">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Understanding Cron Schedules</h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold small text-uppercase text-primary">What is a Cron Expression?</h5>
                        <p class="text-muted small">A cron expression is a string representating a schedule. It is commonly used in Linux crontab files, Docker containers, and CI/CD pipelines like GitHub Actions. Each field represents a unit of time, and the combination defines exactly when a task should trigger.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold small text-uppercase text-success">Common Operators</h5>
                        <p class="text-muted small"><strong>Asterisk (*):</strong> Matches all values. In the hour field, it means "every hour".<br><strong>Comma (,):</strong> Defines a list, e.g., <code>1,3,5</code>.<br><strong>Hyphen (-):</strong> Defines a range, e.g., <code>1-5</code>.<br><strong>Slash (/):</strong> Defines increments, e.g., <code>*/15</code> in minutes means "every 15 mins".</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm bg-light border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Developer Implementation Examples</h6>
                        <div class="mb-3">
                            <div class="small fw-bold text-muted mb-1 text-uppercase x-small">Linux Crontab</div>
                            <code class="d-block p-2 bg-white rounded border x-small">0 0 * * * /usr/bin/php /path/to/script.php</code>
                        </div>
                        <div class="mb-3">
                            <div class="small fw-bold text-muted mb-1 text-uppercase x-small">Laravel Scheduler</div>
                            <code class="d-block p-2 bg-white rounded border x-small">$schedule->command('emails:send')->cron('* * * * *');</code>
                        </div>
                        <div class="mb-0">
                            <div class="small fw-bold text-muted mb-1 text-uppercase x-small">Kubernetes CronJob</div>
                            <code class="d-block p-2 bg-white rounded border x-small">schedule: "0 0 * * *"</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
#tzSelect, #countSelect {
    width: auto;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.x-small {
    font-size: 0.75rem;
}

.italic {
    font-style: italic;
}

.cron-field {
    border-width: 2px;
}

.cron-field:focus {
    border-color: #0d6efd;
    box-shadow: none;
}

#fullExpression {
    letter-spacing: 4px;
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
const CronTool = (function() {
    const fields = ['min', 'hour', 'dom', 'month', 'dow'];
    const fieldRanges = {
        min: [0, 59],
        hour: [0, 23],
        dom: [1, 31],
        month: [1, 12],
        dow: [0, 6]
    };
    const dowNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    const elements = {
        quick: document.getElementById('quickSelect'),
        full: document.getElementById('fullExpression'),
        human: document.getElementById('humanText'),
        execList: document.getElementById('executionList'),
        parseIn: document.getElementById('parseInput'),
        parseErr: document.getElementById('parseError'),
        tz: document.getElementById('tzSelect'),
        count: document.getElementById('countSelect')
    };

    function init() {
        bindEvents();
        updateAll();
    }

    function bindEvents() {
        document.querySelectorAll('.cron-field').forEach(el => {
            el.addEventListener('input', updateAll);
        });

        document.getElementById('applyQuick').addEventListener('click', () => {
            const val = elements.quick.value.split(' ');
            fields.forEach((f, i) => {
                document.getElementById(`field-${f}`).value = val[i];
            });
            updateAll();
        });

        document.getElementById('parseBtn').addEventListener('click', parseExternal);
        document.getElementById('copyExpr').addEventListener('click', () => {
            navigator.clipboard.writeText(elements.full.textContent).then(() => showToast('Copied!', 'success'));
        });
        document.getElementById('resetExpr').addEventListener('click', () => {
            fields.forEach(f => document.getElementById(`field-${f}`).value = '*');
            updateAll();
        });

        elements.tz.addEventListener('change', updateAll);
        elements.count.addEventListener('change', updateAll);

        // Export buttons
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                exportData(btn.getAttribute('data-format'));
            });
        });
    }

    function updateAll() {
        const expr = fields.map(f => document.getElementById(`field-${f}`).value.trim()).join(' ');
        elements.full.textContent = expr;
        
        const sets = parseExpression(expr);
        if (sets) {
            elements.human.parentElement.classList.remove('alert-danger');
            elements.human.parentElement.classList.add('alert-info');
            elements.human.textContent = generateHumanDescription(expr);
            renderSchedule(sets);
        } else {
            elements.human.parentElement.classList.remove('alert-info');
            elements.human.parentElement.classList.add('alert-danger');
            elements.human.textContent = 'Invalid Cron Expression';
            elements.execList.innerHTML = '<tr><td colspan="3" class="text-center text-muted italic">Correct the expression to see the schedule</td></tr>';
        }
    }

    function parseExpression(expr) {
        const parts = expr.split(/\s+/);
        if (parts.length !== 5) return null;

        try {
            return {
                min: expandField(parts[0], 0, 59),
                hour: expandField(parts[1], 0, 23),
                dom: expandField(parts[2], 1, 31),
                month: expandField(parts[3], 1, 12),
                dow: expandField(parts[4], 0, 6)
            };
        } catch (e) {
            return null;
        }
    }

    function expandField(val, min, max) {
        const values = new Set();
        const parts = val.split(',');

        parts.forEach(part => {
            if (part === '*') {
                for (let i = min; i <= max; i++) values.add(i);
            } else if (part.includes('/')) {
                const [range, step] = part.split('/');
                const s = parseInt(step);
                let start = min, end = max;
                if (range !== '*') {
                    const [rStart, rEnd] = range.split('-');
                    start = parseInt(rStart);
                    end = rEnd ? parseInt(rEnd) : max;
                }
                for (let i = start; i <= end; i += s) values.add(i);
            } else if (part.includes('-')) {
                const [start, end] = part.split('-').map(Number);
                for (let i = start; i <= end; i++) values.add(i);
            } else {
                values.add(Number(part));
            }
        });

        const arr = Array.from(values).sort((a, b) => a - b);
        if (arr.some(v => isNaN(v) || v < min || v > max)) throw new Error('Range Error');
        return arr;
    }

    function generateHumanDescription(expr) {
        const parts = expr.split(/\s+/);
        // Simple rules for description
        let desc = 'Runs ';
        const [m, h, dom, mon, dow] = parts;

        if (expr === '* * * * *') return 'Runs every minute.';
        
        if (m.startsWith('*/')) desc += `every ${m.split('/')[1]} minutes `;
        else if (m === '0') desc += 'at the start of the hour ';
        else if (m === '*') desc += 'every minute ';
        else desc += `at minute ${m} `;

        if (h === '*') desc += 'of every hour ';
        else if (h.includes(',')) desc += `during hours ${h} `;
        else desc += `at ${h.padStart(2, '0')}:00 `;

        if (dom !== '*') desc += `on day ${dom} of the month `;
        if (mon !== '*') desc += `in ${mon === '*' ? 'every month' : 'month ' + mon} `;
        if (dow !== '*') {
            const days = dow.split(',').map(d => dowNames[d] || d).join(', ');
            desc += `on ${days} `;
        }

        return desc.trim() + '.';
    }

    function renderSchedule(sets) {
        const count = parseInt(elements.count.value);
        const tz = elements.tz.value;
        const now = new Date();
        let current = new Date(now.getTime());
        current.setSeconds(0, 0);

        let runs = [];
        let iterations = 0;
        
        while (runs.length < count && iterations < 10000) {
            current.setMinutes(current.getMinutes() + 1);
            iterations++;

            const check = (tz === 'UTC') ? 
                { m: current.getUTCMinutes(), h: current.getUTCHours(), d: current.getUTCDate(), mo: current.getUTCMonth() + 1, dw: current.getUTCDay() } :
                { m: current.getMinutes(), h: current.getHours(), d: current.getDate(), mo: current.getMonth() + 1, dw: current.getDay() };

            if (sets.min.includes(check.m) && 
                sets.hour.includes(check.h) && 
                sets.dom.includes(check.d) && 
                sets.month.includes(check.mo) && 
                sets.dow.includes(check.dw)) {
                runs.push(new Date(current.getTime()));
            }
        }

        let html = '';
        runs.forEach((date, i) => {
            const dateStr = (tz === 'UTC') ? date.toUTCString() : date.toLocaleString();
            html += `<tr>
                <td class="text-muted small">#${i + 1}</td>
                <td class="font-monospace">${dateStr}</td>
                <td class="small text-primary">${getRelativeTime(date)}</td>
            </tr>`;
        });
        elements.execList.innerHTML = html || '<tr><td colspan="3" class="text-center py-4">No matching executions found in the near future.</td></tr>';
    }

    function getRelativeTime(date) {
        const diff = Math.floor((date - new Date()) / 1000);
        if (diff < 60) return 'in less than a minute';
        if (diff < 3600) return `in ${Math.floor(diff / 60)} minutes`;
        if (diff < 86400) return `in ${Math.floor(diff / 3600)} hours`;
        return `in ${Math.floor(diff / 86400)} days`;
    }

    function parseExternal() {
        const val = elements.parseIn.value.trim();
        const parts = val.split(/\s+/);
        
        elements.parseErr.classList.add('d-none');

        if (parts.length !== 5) {
            elements.parseErr.textContent = 'Invalid format. Standard Cron requires exactly 5 fields.';
            elements.parseErr.classList.remove('d-none');
            return;
        }

        const sets = parseExpression(val);
        if (!sets) {
            elements.parseErr.textContent = 'Malformed expression. Check for invalid characters or ranges.';
            elements.parseErr.classList.remove('d-none');
            return;
        }

        fields.forEach((f, i) => {
            document.getElementById(`field-${f}`).value = parts[i];
        });
        updateAll();
        showToast('Expression parsed and applied!', 'success');
    }

    function exportData(format) {
        const expr = elements.full.textContent;
        const desc = elements.human.textContent;
        const schedule = Array.from(elements.execList.querySelectorAll('tr')).map(tr => {
            const tds = tr.querySelectorAll('td');
            return tds.length > 1 ? tds[1].textContent : '';
        }).filter(t => t);

        let content = '';
        let type = 'text/plain';
        let ext = format;

        if (format === 'txt') {
            content = `Cron Expression: ${expr}\nDescription: ${desc}\n\nExecution Schedule:\n${schedule.join('\n')}`;
        } else if (format === 'json') {
            content = JSON.stringify({ expression: expr, description: desc, schedule: schedule }, null, 2);
            type = 'application/json';
        } else if (format === 'csv') {
            content = `"Expression","Description","Run Time"\n` + schedule.map(s => `"${expr}","${desc}","${s}"`).join('\n');
            type = 'text/csv';
        }

        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `cron-schedule.${ext}`;
        a.click();
        URL.revokeObjectURL(url);
    }

    return { init, exportData };
})();

document.addEventListener('DOMContentLoaded', function() {
    CronTool.init();
});
</script>
@endpush
