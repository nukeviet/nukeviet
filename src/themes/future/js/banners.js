/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

const bannerCharts = {
    date: null,
    country: null,
    os: null,
    browser: null
};

function renderBannerChart(type, data) {
    const chartId = 'chart-' + type;
    const chartEl = document.getElementById(chartId);
    window.chart = window.chart || {};
    window.chart[type] = data.chart_series_formatted;

    if (!chartEl) return;

    if (bannerCharts[type]) {
        bannerCharts[type].destroy();
        bannerCharts[type] = null;
    }

    if (!data.chart_series || data.chart_series.length === 0) {
        chartEl.innerHTML = '<div class="text-muted text-center">' + $(chartEl).data('empty-mess') + '</div>';
        return;
    }

    const colors = ['#4285f4', '#34a853', '#fbbc05', '#ea4335', '#9b59b6', '#1abc9c', '#e74c3c', '#3498db'];
    let hoverIndex = -1;
    let options = {};

    if (type === 'date') {
        options = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            series: [{ name: data.name, data: data.chart_series }],
            xaxis: {
                categories: data.chart_labels,
                labels: { rotate: -45, style: { fontSize: '11px' } }
            },
            yaxis: { show: false },
            colors: ['#4285f4'],
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 }
            },
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        return chart['date'][opts.dataPointIndex];
                    }
                }
            },
            grid: { strokeDashArray: 3 }
        };
    } else if (type === 'country') {
        options = {
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            series: [{ name: data.name, data: data.chart_series }],
            xaxis: {
                categories: data.chart_labels,
                labels: { show: false }
            },
            plotOptions: {
                bar: { horizontal: true, borderRadius: 1, barHeight: '60%' }
            },
            colors: ['#4285f4'],
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        return chart['country'][opts.dataPointIndex];
                    }
                }
            },
            grid: { strokeDashArray: 3 }
        };
    } else {
        options = {
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'inherit',
                events: {
                    dataPointMouseEnter: function(event, chartContext, config) {
                        hoverIndex = config.dataPointIndex;
                    },
                    dataPointMouseLeave: function() {
                        hoverIndex = -1;
                    }
                }
            },
            series: data.chart_series,
            labels: data.chart_labels,
            colors: colors,
            legend: { position: 'bottom', fontSize: '13px' },
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: data.lbl_total,
                                formatter: function() { return data.total; }
                            },
                            value: {
                                formatter: function() {
                                    return hoverIndex >= 0 ? chart[type][hoverIndex] : '';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return data.percents[opts.seriesIndex] + '%';
                }
            },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        return chart[type][opts.seriesIndex];
                    }
                }
            }
        };
    }

    bannerCharts[type] = new ApexCharts(chartEl, options);
    bannerCharts[type].render();
}

$(function () {
    $('body').on('change', '[data-toggle="loadStat"]', function () {
        const ads = $('#adsstat-ads').val();
        const month = $('#adsstat-month').val();

        if (!ads || !month) {
            $('#stat-summary, #stat-charts').addClass('d-none');
            return;
        }

        $('#stat-loading').removeClass('d-none');
        $('#stat-summary, #stat-charts').addClass('d-none');

        const url = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data +
            '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=viewmap' +
            '&ads=' + ads + '&month=' + month + '&nocache=' + Date.now();

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                $('#stat-loading').addClass('d-none');

                if (data.status === 'error') {
                    nvAlert(data.message);
                    return;
                }

                $('#total-clicks').text(data.total_clicks_formatted);

                const types = ['date', 'country', 'browser', 'os'];
                $.each(types, function(index, type) {
                    const chartCtn = $('#chart-' + type);
                    if (data.charts[type] && data.charts[type].labels.length > 0) {
                        renderBannerChart(type, {
                            chart_labels: data.charts[type].labels,
                            chart_series: data.charts[type].series,
                            chart_series_formatted: data.charts[type].series_formatted,
                            name: data.charts[type].name,
                            total: data.charts[type].total,
                            percents: data.charts[type].percent_series,
                            lbl_total: data.charts[type].lbl_total
                        });
                    } else {
                        chartCtn.html('<div class="text-muted text-center">' + chartCtn.data('empty-mess') + '</div>');
                    }
                });

                $('#stat-summary, #stat-charts').removeClass('d-none');
            },
            error: function() {
                $('#stat-loading').addClass('d-none');
                nvAlert('Error loading statistics');
            }
        });
    });

    const ctn = $('#form-addads');
    if (ctn.length) {
        const planSelect = ctn.find('#banner_plan');
        const imgBox     = ctn.find('[data-area="banner-upload-box"]');
        const fileAst    = ctn.find('[data-area="required-file"]');
        const urlAst     = ctn.find('[data-area="required-url"]');
        const imgInput   = ctn.find('[data-area="image-input"]');
        const urlInput   = ctn.find('[data-area="url-input"]');

        // Khởi tạo giao diện theo plan hiện tại
        let isImage = !!planSelect.find(':selected').data('image');

        // Cập nhật giao diện theo plan
        planSelect.on('change', function () {
            isImage = !!$(this).find(':selected').data('image');

            imgBox.toggleClass('d-none', !isImage);
            fileAst.toggleClass('d-none', !isImage);
            urlAst.toggleClass('d-none', isImage);

            const activeInput = isImage ? imgInput : urlInput;
            const inactiveInput = isImage ? urlInput : imgInput;

            activeInput.attr('data-valid', '');
            nv_validate_reset(inactiveInput); // Gỡ trạng thái valid cũ
            inactiveInput.removeAttr('data-valid');

            if (!isImage) {
                imgInput.val(''); // Xóa giá trị file nếu chuyển sang nhập URL
            }
        });
    }
});
