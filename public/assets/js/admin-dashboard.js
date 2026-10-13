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
            rendered[id].destroy();
            delete rendered[id];
        }
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

    document.addEventListener('DOMContentLoaded', () => {
        /* ---- Quotes (bars) + Revenue (area), last 6 months ---- */
        renderInto('chart-quotes-revenue', (el) => ({
            chart: { type: 'line', height: 300, toolbar: { show: false }, fontFamily: FONT },
            series: [
                {
                    name: 'Quotes',
                    type: 'column',
                    data: readJson(el, 'quotes', []),
                },
                {
                    name: 'Revenue (PKR)',
                    type: 'area',
                    data: readJson(el, 'revenue', []),
                },
            ],
            labels: readJson(el, 'labels', []),
            colors: ['#1d4ed8', '#f59e0b'],
            stroke: { width: [0, 3], curve: 'smooth' },
            fill: {
                type: ['solid', 'gradient'],
                gradient: {
                    shadeIntensity: 0.4,
                    opacityFrom: 0.7,
                    opacityTo: 0.15,
                    stops: [0, 100],
                },
            },
            plotOptions: {
                bar: { borderRadius: 4, columnWidth: '52%' },
            },
            dataLabels: { enabled: false },
            tooltip: { shared: true, intersect: false, theme: 'light' },
            legend: { position: 'top', horizontalAlign: 'right', fontFamily: FONT },
            grid: { borderColor: '#eef2fb', strokeDasharray: 0 },
            xaxis: {
                labels: { style: { fontFamily: FONT, colors: '#64748b' } },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: [
                {
                    title: { text: 'Quotes', style: { fontFamily: FONT, color: '#64748b' } },
                    labels: { style: { fontFamily: FONT, colors: '#64748b' } },
                },
                {
                    opposite: true,
                    title: { text: 'PKR', style: { fontFamily: FONT, color: '#64748b' } },
                    labels: {
                        style: { fontFamily: FONT, colors: '#64748b' },
                        formatter: (v) => v >= 1000 ? Math.round(v / 1000) + 'k' : v,
                    },
                },
            ],
        }));

        /* ---- Project pipeline — PIE (not donut) ---- */
        renderInto('chart-pipeline', (el) => ({
            chart: { type: 'pie', height: 300, fontFamily: FONT },
            series: readJson(el, 'values', []),
            labels: readJson(el, 'labels', []).map((s) =>
                s.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())
            ),
            colors: ['#93c5fd', '#1d4ed8', '#f59e0b', '#8b5cf6', '#10b981', '#64748b'],
            plotOptions: {
                pie: { startAngle: -90, expandOnClick: false },
            },
            dataLabels: {
                style: { fontFamily: FONT },
                dropShadow: { enabled: false },
            },
            legend: { position: 'bottom', fontFamily: FONT },
            tooltip: { theme: 'light', style: { fontFamily: FONT } },
            states: {
                hover: { filter: { type: 'lighten', value: 0.08 } },
            },
        }));
    });
})();
