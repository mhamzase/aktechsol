/**
 * admin-finance.js — Finance page behaviour.
 *
 * Three tabs (Revenue / Expenses / Withdrawals), each with a date
 * range filter (presets + the shared custom range picker), stat
 * cards and an ApexCharts chart fed by the summary endpoint. The
 * listings themselves are the shared data-table (admin.js) and stay
 * the full overall record; only stats + chart follow the range.
 *
 * Conventions kept: delegated listeners only (drawer/table DOM is
 * re-rendered), chart.destroy() followed by innerHTML = '' (a stale
 * SVG survives destroy otherwise).
 */
(function () {
    'use strict';

    var PALETTE = ['#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#ef4444', '#ec4899', '#84cc16', '#f97316', '#64748b'];
    var ADD_LABELS = { revenue: 'Add Revenue', expense: 'Add Expense', withdrawal: 'Add Withdrawal' };
    var panels = {};
    var activeType = 'revenue';

    function pad(n) { return String(n).padStart(2, '0'); }
    function iso(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
    function fmtDate(isoStr) {
        if (!isoStr) return '';
        var parts = isoStr.split('-');
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return parts[2] + ' ' + months[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
    }

    /** Effective range for a preset, as {from, to} ISO dates (or nulls). */
    function rangeFor(preset) {
        var now = new Date();
        if (preset === 'month') {
            return { from: iso(new Date(now.getFullYear(), now.getMonth(), 1)), to: iso(now) };
        }
        if (preset === 'last') {
            return {
                from: iso(new Date(now.getFullYear(), now.getMonth() - 1, 1)),
                to: iso(new Date(now.getFullYear(), now.getMonth(), 0)),
            };
        }
        if (preset === '6m') {
            return { from: iso(new Date(now.getFullYear(), now.getMonth() - 5, 1)), to: iso(now) };
        }
        return { from: null, to: null }; // all
    }

    function readCustom(panel) {
        var f = panel.el.querySelector('input[name="from"]');
        var t = panel.el.querySelector('input[name="to"]');
        return { from: f && f.value ? f.value : null, to: t && t.value ? t.value : null };
    }

    function refresh(panel) {
        var qs = new URLSearchParams({ type: panel.type });
        if (panel.range.from) qs.set('from', panel.range.from);
        if (panel.range.to) qs.set('to', panel.range.to);

        fetch(window.FIN_SUMMARY_URL + '?' + qs.toString(), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        }).then(function (res) {
            if (!res.ok) throw new Error('summary failed');
            return res.json();
        }).then(function (data) {
            Object.keys(data.cards || {}).forEach(function (key) {
                var shell = panel.el.querySelector('[data-fin-card="' + key + '"]');
                if (!shell) return;
                var card = data.cards[key];
                shell.querySelector('[data-card-label]').textContent = card.label;
                shell.querySelector('[data-card-main]').textContent = card.main;
                shell.querySelector('[data-card-sub]').textContent = card.sub;
            });

            var miss = panel.el.querySelector('[data-fin-missing]');
            if (data.missing_rates && data.missing_rates.length) {
                miss.textContent = 'Note: no PKR rate is set for ' + data.missing_rates.join(', ') +
                    ', so those amounts are left out of the PKR totals. Set the rates in Settings, Payments tab.';
                miss.classList.remove('hidden');
            } else {
                miss.classList.add('hidden');
            }

            var label = panel.el.querySelector('[data-fin-range-label]');
            label.textContent = (panel.range.from || panel.range.to)
                ? 'Stats and chart: ' + (panel.range.from ? fmtDate(panel.range.from) : 'the start') + ' to ' + (panel.range.to ? fmtDate(panel.range.to) : 'today')
                : 'Stats and chart: all time';

            renderChart(panel, data);
            panel.dirty = false;
        }).catch(function () {
            // Honest failure state (never an endless "Loading..."):
            // cards show a retry the admin can click.
            panel.dirty = true;
            panel.el.querySelectorAll('[data-fin-card]').forEach(function (shell) {
                shell.querySelector('[data-card-main]').textContent = '—';
                shell.querySelector('[data-card-sub]').innerHTML =
                    'Could not load. <button type="button" data-fin-retry class="font-semibold underline">Retry</button>';
            });
            var label = panel.el.querySelector('[data-fin-range-label]');
            if (label) label.textContent = 'Stats failed to load';
        });
    }

    function renderChart(panel, data) {
        var el = panel.el.querySelector('[data-fin-chart]');
        var empty = panel.el.querySelector('[data-fin-chart-empty]');
        var hasData = (data.series || []).some(function (s) {
            return s.data.some(function (v) { return v > 0; });
        });

        if (panel.chart) {
            panel.chart.destroy();
            panel.chart = null;
        }
        el.innerHTML = '';

        if (!hasData || typeof ApexCharts === 'undefined') {
            empty.classList.remove('hidden');
            el.classList.add('hidden');
            return;
        }
        empty.classList.add('hidden');
        el.classList.remove('hidden');

        var dark = document.documentElement.classList.contains('dark');
        var series = data.series.map(function (s, i) {
            return { name: s.name, data: s.data, color: PALETTE[i % PALETTE.length] };
        });
        // One axis per currency (amounts are never mixed); only the
        // first (PKR when present) is visible, like the dashboard.
        var yaxis = data.series.map(function (s, i) {
            return {
                seriesName: s.name,
                show: i === 0,
                labels: {
                    formatter: function (v) {
                        var sym = (s.symbol || '').trim();
                        return (sym ? sym : '') + Number(v).toLocaleString('en-US', { maximumFractionDigits: s.decimals });
                    },
                },
            };
        });

        panel.chart = new ApexCharts(el, {
            chart: {
                type: 'area', height: 300, toolbar: { show: false },
                fontFamily: 'Sora, sans-serif',
                foreColor: dark ? '#94a3b8' : '#64748b',
                zoom: { enabled: false },
            },
            series: series,
            xaxis: { categories: data.labels, labels: { rotate: -35, hideOverlappingLabels: true } },
            yaxis: yaxis,
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
            dataLabels: { enabled: false },
            tooltip: { shared: true, intersect: false, theme: dark ? 'dark' : 'light' },
            legend: { position: 'top', horizontalAlign: 'left' },
            grid: { borderColor: dark ? 'rgba(148,163,184,.15)' : '#e2e8f0' },
        });
        panel.chart.render();
    }

    function setPreset(panel, preset) {
        panel.preset = preset;
        panel.el.querySelectorAll('[data-fin-preset]').forEach(function (btn) {
            btn.classList.toggle('fin-active', btn.getAttribute('data-fin-preset') === preset);
        });
        var customBox = panel.el.querySelector('[data-fin-custom]');
        if (customBox) customBox.style.display = preset === 'custom' ? '' : 'none';
        panel.range = preset === 'custom' ? readCustom(panel) : rangeFor(preset);
        refresh(panel);
    }

    function activate(type) {
        activeType = type;
        document.querySelectorAll('[data-fin-tab]').forEach(function (btn) {
            btn.classList.toggle('active', btn.getAttribute('data-fin-tab') === type);
        });
        document.querySelectorAll('[data-fin-panel]').forEach(function (p) {
            p.classList.toggle('hidden', p.getAttribute('data-fin-panel') !== type);
        });
        // The hero Add button follows the active tab.
        var addLabel = document.querySelector('[data-fin-add-label]');
        if (addLabel) addLabel.textContent = ADD_LABELS[type] || 'Add';
        try { history.replaceState(null, '', '#' + type); } catch (e) { /* no-op */ }
        var panel = panels[type];
        if (panel && panel.dirty) refresh(panel);
    }

    /** Hero Add button: open the create drawer for the active tab. */
    window.finOpenCreate = function () {
        var btn = document.getElementById('fin-add-btn');
        if (!btn || !window.openDrawer) return;
        var url = btn.getAttribute('data-url-' + activeType);
        if (url) window.openDrawer(url, ADD_LABELS[activeType] || 'Add Entry', true);
    };

    function refreshActive() {
        Object.keys(panels).forEach(function (key) { panels[key].dirty = true; });
        var active = document.querySelector('[data-fin-panel]:not(.hidden)');
        if (active) refresh(panels[active.getAttribute('data-fin-panel')]);
    }

    // Delegated clicks: tab buttons + preset buttons + card retry.
    document.addEventListener('click', function (e) {
        var retry = e.target.closest('[data-fin-retry]');
        if (retry) {
            var retryPanel = retry.closest('[data-fin-panel]');
            if (retryPanel) refresh(panels[retryPanel.getAttribute('data-fin-panel')]);
            return;
        }
        var tab = e.target.closest('[data-fin-tab]');
        if (tab) {
            activate(tab.getAttribute('data-fin-tab'));
            return;
        }
        var presetBtn = e.target.closest('[data-fin-preset]');
        if (presetBtn) {
            var panelEl = presetBtn.closest('[data-fin-panel]');
            if (panelEl) setPreset(panels[panelEl.getAttribute('data-fin-panel')], presetBtn.getAttribute('data-fin-preset'));
        }
    });

    // Picking dates in the custom range switches the panel to Custom.
    document.addEventListener('change', function (e) {
        if (!e.target.matches('[data-fin-panel] input[name="from"], [data-fin-panel] input[name="to"]')) return;
        var panelEl = e.target.closest('[data-fin-panel]');
        if (!panelEl) return;
        var panel = panels[panelEl.getAttribute('data-fin-panel')];
        if (panel.preset !== 'custom') {
            setPreset(panel, 'custom');
        } else {
            panel.range = readCustom(panel);
            refresh(panel);
        }
    });

    // Any saved/deleted entry makes every tab's numbers stale.
    document.addEventListener('ajaxFormSuccess', refreshActive);
    document.addEventListener('rowDeleted', refreshActive);

    // Init: register panels, default preset = This Month, open the
    // tab from the URL hash when present.
    document.querySelectorAll('[data-fin-panel]').forEach(function (el) {
        var type = el.getAttribute('data-fin-panel');
        panels[type] = { type: type, el: el, chart: null, dirty: true, preset: 'month', range: rangeFor('month') };
        el.querySelectorAll('[data-fin-preset]').forEach(function (btn) {
            btn.classList.toggle('fin-active', btn.getAttribute('data-fin-preset') === 'month');
        });
        var customBox = el.querySelector('[data-fin-custom]');
        if (customBox) customBox.style.display = 'none';
    });

    var initial = (window.location.hash || '#revenue').slice(1);
    if (!panels[initial]) initial = 'revenue';
    activate(initial);
    refresh(panels[initial]);
})();
