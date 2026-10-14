/* ==========================================================================
   AK Tech Sol — Admin dashboard charts (ApexCharts, local).
   Data comes ONLY from data-* attributes on the chart containers
   (no Blade dependency). Follows the tailor-app chart baseline:
   Sora font, shared tooltips, bars borderRadius 4 / 52% width,
   pies (not donuts) starting at -90°, area fills fading 0.7 → 0.15.
   ========================================================================== */
(function () {
    'use strict';

    // Rendered chart instances — destroyed before re-render.
    const rendered = {};

    function renderInto(id, buildOptions) {
        const el = document.getElementById(id);
        if (!el || typeof ApexCharts === 'undefined') return;
        if (rendered[id]) {
            try { rendered[id].destroy(); } catch (e) { /* ignore */ }
            delete rendered[id];
        }
        // Force-clean: destroy() can leave a stale SVG behind, and a second
        // SVG stacked on top is what glitches the pie / breaks hover mapping.
        el.innerHTML = '';
        const chart = new ApexCharts(el, buildOptions(el));
        rendered[id] = chart;
        chart.render();
    }

    function readJson(el, key, fallback) {
        try {
            const raw = el.dataset[key];
            return raw ? JSON.parse(raw) : fallback;
        } catch (e) {
            return fallback;
        }
    }

    const FONT = 'Sora, sans-serif';

    // Dark-mode aware palette helpers (re-read on every render).
    const isDark = () => document.documentElement.classList.contains('dark');
    const axisColor = () => (isDark() ? '#94a3b8' : '#64748b');
    const gridColor = () => (isDark() ? 'rgba(148,163,184,0.14)' : '#eef2fb');
    const tooltipTheme = () => (isDark() ? 'dark' : 'light');

    function renderCharts() {
        /* ---- Quotes + Revenue — AREA line chart (tailor-app style: smooth
               lines, gradient fills, soft drop-shadow on the series) ---- */
        renderInto('chart-quotes-revenue', (el) => {
            // Captured once — the tooltip reads THESE arrays, so the hovered
            // month always matches its own values (no label/index mismatch).
            const monthLabels = readJson(el, 'labels', []);
            const quotesData = readJson(el, 'quotes', []).map(Number);
            const revenueData = readJson(el, 'revenue', []).map(Number);
            return {
            chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: FONT, zoom: { enabled: false } },
            series: [
                { name: 'Quotes', data: quotesData },
                { name: 'Revenue (PKR)', data: revenueData },
            ],
            colors: ['#1d4ed8', '#f59e0b'],
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.4,
                    opacityFrom: 0.7,
                    opacityTo: 0.15,
                    stops: [0, 100],
                },
            },
            markers: { size: 0, hover: { size: 6 } },
            dataLabels: { enabled: false },
            tooltip: {
                shared: true,
                intersect: false,
                custom: ({ dataPointIndex }) => {
                    const i = dataPointIndex;
                    const label = monthLabels[i] || '';
                    const q = quotesData[i] ?? 0;
                    const r = revenueData[i] ?? 0;
                    return '<div class="apex-custom-tip">' +
                        '<div class="apex-custom-title">' + label + '</div>' +
                        '<div class="apex-custom-row"><span class="dot" style="background:#1d4ed8"></span>Quotes<b>' + q + '</b></div>' +
                        '<div class="apex-custom-row"><span class="dot" style="background:#f59e0b"></span>Revenue (PKR)<b>PKR ' + Number(r).toLocaleString('en-PK') + '</b></div>' +
                        '</div>';
                },
            },
            legend: { position: 'top', horizontalAlign: 'right', fontFamily: FONT, labels: { colors: axisColor() } },
            grid: { borderColor: gridColor(), strokeDasharray: 0 },
            xaxis: {
                categories: monthLabels,
                labels: { style: { fontFamily: FONT, colors: axisColor() } },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: [
                {
                    title: { text: 'Quotes', style: { fontFamily: FONT, color: axisColor() } },
                    labels: {
                        style: { fontFamily: FONT, colors: axisColor() },
                        formatter: (v) => Math.round(v),
                    },
                    min: 0, forceNiceScale: true,
                },
                {
                    opposite: true,
                    title: { text: 'PKR', style: { fontFamily: FONT, color: axisColor() } },
                    labels: {
                        style: { fontFamily: FONT, colors: axisColor() },
                        formatter: (v) => v >= 1000 ? Math.round(v / 1000) + 'k' : v,
                    },
                },
            ],
            states: {
                hover: { filter: { type: 'lighten', value: 0.08 } },
            },
            };
        });

        /* ---- Project pipeline — PIE (not donut) ---- */
        renderInto('chart-pipeline', (el) => ({
            chart: { type: 'pie', height: 300, fontFamily: FONT, redrawOnParentResize: true },
            series: readJson(el, 'values', []).map(Number),
            labels: readJson(el, 'labels', []).map((s) =>
                s.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())
            ),
            colors: ['#3b82f6', '#8b5cf6', '#f59e0b', '#10b981', '#ef4444', '#06b6d4'],
            plotOptions: {
                pie: { startAngle: -90, endAngle: 270, expandOnClick: false },
            },
            dataLabels: {
                style: { fontFamily: FONT },
                dropShadow: { enabled: false },
            },
            legend: { position: 'bottom', fontFamily: FONT, labels: { colors: axisColor() } },
            tooltip: { theme: tooltipTheme(), style: { fontFamily: FONT } },
            states: {
                hover: { filter: { type: 'lighten', value: 0.08 } },
            },
        }));
    }

    // Exposed so the theme toggle can re-render charts in the new mode.
    window.renderAdminCharts = renderCharts;
    // Single render on DOM ready. (No window.load re-render: a second render
    // right after the first is what stacked duplicate SVGs and glitched the
    // charts. renderInto() force-cleans the container, so theme toggles are safe.)
    document.addEventListener('DOMContentLoaded', renderCharts);
})();
