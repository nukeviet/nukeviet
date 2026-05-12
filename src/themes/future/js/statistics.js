/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

const nvStatCharts = {};

const NV_STAT_COLORS = ['#4285f4', '#34a853', '#fbbc05', '#ea4335', '#9b59b6', '#1abc9c', '#e74c3c', '#3498db'];

function nvInitStatChart(el) {
    const type = el.dataset.type;
    const labels = JSON.parse(el.dataset.labels || '[]');
    const values = JSON.parse(el.dataset.values || '[]');
    const valuesFormatted = JSON.parse(el.dataset.valuesFormatted || '[]');
    const xtitle = el.dataset.xtitle || '';
    const ytitle = el.dataset.ytitle || '';

    if (!labels.length) return;

    let options = {};

    if (type === 'area') {
        options = {
            chart: { type: 'area', height: 220, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: ytitle, data: values }],
            xaxis: { categories: labels, labels: { rotate: -45, style: { fontSize: '11px' } }, tooltip: { enabled: false } },
            yaxis: { show: false },
            colors: ['#4285f4'],
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
            dataLabels: { enabled: false },
            grid: { strokeDashArray: 3 },
            tooltip: {
                x: { formatter: (val) => xtitle + ': ' + val },
                y: { formatter: (val, { dataPointIndex }) => valuesFormatted[dataPointIndex] ?? '' }
            }
        };
    } else if (type === 'bar') {
        options = {
            chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: ytitle, data: values }],
            xaxis: { categories: labels, labels: { style: { fontSize: '11px' } } },
            yaxis: { show: false },
            colors: ['#4285f4'],
            plotOptions: { bar: { borderRadius: 1, columnWidth: '55%' } },
            dataLabels: { enabled: false },
            grid: { strokeDashArray: 3 },
            tooltip: {
                x: { formatter: (val) => xtitle + ': ' + val },
                y: { formatter: (val, { dataPointIndex }) => valuesFormatted[dataPointIndex] ?? '' }
            }
        };
    } else if (type === 'donut') {
        // Lọc bỏ null/0 để donut không bị lỗi, giữ nguyên index tương ứng với valuesFormatted
        const filteredLabels = [];
        const filteredValues = [];
        const filteredFormatted = [];
        labels.forEach((lbl, i) => {
            if (values[i] !== null && values[i] > 0) {
                filteredLabels.push(lbl);
                filteredValues.push(values[i]);
                filteredFormatted.push(valuesFormatted[i] ?? String(values[i]));
            }
        });

        options = {
            chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
            series: filteredValues,
            labels: filteredLabels,
            colors: NV_STAT_COLORS,
            legend: { show: false },
            plotOptions: { pie: { donut: { size: '50%' } } },
            dataLabels: { enabled: false },
            tooltip: { y: { formatter: (val, { seriesIndex }) => filteredFormatted[seriesIndex] ?? String(val) } }
        };
    }

    nvStatCharts[el.id] = new ApexCharts(el, options);
    nvStatCharts[el.id].render();
}

$(function () {
    // Khởi tạo tất cả biểu đồ thống kê trên trang
    document.querySelectorAll('[data-nv-stat-chart]').forEach(el => {
        if (typeof ApexCharts !== 'undefined') {
            nvInitStatChart(el);
        }
    });

    // Đóng popover của block statistics button khi click ra ngoài
    document.addEventListener('click', e => {
        const btn = document.querySelector('[data-nv-counter-btn]');
        if (!btn) return;
        if (!btn.contains(e.target) && !document.querySelector('.popover')?.contains(e.target)) {
            bootstrap.Popover.getInstance(btn)?.hide();
        }
    });
});
