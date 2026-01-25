/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Hàm định dạng số
    function _format(value, decimals = 0, decPoint = '.', thousandsSep = ',') {
        let formatted = '';
        const formatter = new Intl.NumberFormat(
            'de',
            {
                style: 'currency',
                currency: 'eur',
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }
        );
        const parts = formatter.formatToParts(value);

        parts.forEach(part => {
            if (part.type === 'integer' || part.type === 'fraction') {
                formatted += part.value;
            } else if (part.type === 'group') {
                formatted += thousandsSep;
            } else if (part.type === 'decimal') {
                formatted += decPoint;
            }
        });

        return formatted;
    }

    // Hàm định dạng số
    function number_format(value, decimals = 0)
    {
        if (nv_lang_interface == 'vi') {
            return _format(value, decimals, ',', '.');
        }
        return _format(value, decimals);
    }

    // Chart widget hour
    const wgdStatHour = $('#widget-stat-hour');
    if (wgdStatHour.length > 0) {
        let data = JSON.parse(trim($('.data', wgdStatHour).text()));
        wgdStatHour.html('');
        let options = {
            colors: ['#4285f4'],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Visits',
                data: data.data
            }],
            xaxis: {
                categories: data.categories,
                labels: {
                    style: {
                        fontFamily: 'Roboto, system-ui, -apple-system, "Segoe UI", "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: value => {
                        return number_format(value);
                    },
                    style: {
                        fontFamily: 'Roboto, system-ui, -apple-system, "Segoe UI", "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
                        fontSize: '13px'
                    }
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                custom: cfg => {
                    let html = `<div class="apexcharts-tooltip-title fw-medium">` + data.categories[cfg.dataPointIndex] + ` ` + data.hour + `</div>
                    <div class="apexcharts-tooltip-series-group d-flex apexcharts-active">
                    <div class="apexcharts-tooltip-text">
                        <div class="apexcharts-tooltip-y-group">
                            <span class="fw-medium">` + data.data_formatted[cfg.dataPointIndex] + `</span> ` + data.unit + `
                        </div>
                    </div>
                    </div>`;
                    return html;
                }
            },
            grid: {
                strokeDashArray: 2,
            }
        }
        let chart = new ApexCharts(wgdStatHour[0], options);
        chart.render();
    }
});

