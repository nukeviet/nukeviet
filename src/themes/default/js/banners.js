/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function afSubmit_precheck(form) {
    $(".has-error", form).removeClass("has-error");
    if ($('[name=title]', form).val().length < 3) {
        $('[name=title]', form).parent().addClass('has-error');
        alert($('[name=title]', form).data('mess'));
        $('[name=title]', form).focus();
        return !1
    }

    if ($('[name=image]', form).is('.required') && !$('[name=image]', form).val()) {
        $('[name=image]', form).parent().addClass('has-error');
        alert($('[name=image]', form).data('mess'));
        $('[name=image]', form).focus();
        return !1
    }

    if ($('[name=url]', form).is('.required') && $('[name=url]', form).val().length < 3) {
        $('[name=url]', form).parent().addClass('has-error');
        alert($('[name=url]', form).data('mess'));
        $('[name=url]', form).focus();
        return !1
    }

    if ($('[name=captcha]', form).length && $('[name=captcha]', form).val().length < parseInt($('[name=captcha]', form).attr('maxlength'))) {
        $('[name=captcha]', form).parent().addClass('has-error');
        alert($('[name=captcha]', form).data('mess'));
        $('[name=captcha]', form).focus();
        return !1
    }

    return !0
}

function afSubmit(form) {
    $(".has-error", form).removeClass("has-error");
    var data = new FormData(form);
    $("input,button,select", form).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !0,
        url: $(form).prop("action"),
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(d) {
            alert(d.mess);
            if (d.status == "error") {
                $("input,button,select", form).prop("disabled", !1);
                formChangeCaptcha(form);
                if ("" != d.input && $("[name=" + d.input + "]:visible", form).length) {
                    $("[name=" + d.input + "]:visible", form).parent().addClass('has-error');
                    $("[name=" + d.input + "]:visible", form).focus()
                }
            } else {
                window.location.href = d.redirect;
            }
        }
    })
}

var bannerCharts = {
    date: null,
    country: null,
    os: null,
    browser: null
};

function renderBannerChart(type, data) {
    var chartId = 'chart-' + type;
    var chartEl = document.getElementById(chartId);
    window.chart = window.chart || {};
    window.chart[type] = data.chart_series_formatted;

    if (!chartEl) return;

    if (bannerCharts[type]) {
        bannerCharts[type].destroy();
        bannerCharts[type] = null;
    }

    if (!data.chart_series || data.chart_series.length === 0) {
        chartEl.innerHTML = '<div class="text-muted text-center">No data</div>';
        return;
    }

    var options = {};
    var colors = ['#4285f4', '#34a853', '#fbbc05', '#ea4335', '#9b59b6', '#1abc9c', '#e74c3c', '#3498db'];
    let hoverIndex = -1;

    if (type === 'date') {
        options = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            series: [{
                name: data.name,
                data: data.chart_series
            }],
            xaxis: {
                categories: data.chart_labels,
                labels: {
                    rotate: -45,
                    style: { fontSize: '11px' }
                }
            },
            yaxis: {
                show: false
            },
            colors: ['#4285f4'],
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1
                }
            },
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: function(val, data) {
                        return chart['date'][data.dataPointIndex];
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
            series: [{
                name: data.name,
                data: data.chart_series
            }],
            xaxis: {
                categories: data.chart_labels,
                labels: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '60%'
                }
            },
            colors: ['#4285f4'],
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: function(val, data) {
                        return chart['country'][data.dataPointIndex];
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
                    dataPointMouseEnter: function (event, chartContext, config) {
                        hoverIndex = config.dataPointIndex;
                    },
                    dataPointMouseLeave: function () {
                        hoverIndex = -1;
                    }
                }
            },
            series: data.chart_series,
            labels: data.chart_labels,
            colors: colors,
            legend: {
                position: 'bottom',
                fontSize: '13px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: data.lbl_total,
                                formatter: function() {
                                    return data.total;
                                }
                            },
                            value: {
                                formatter: function(val, opts, w) {
                                    return chart[type][hoverIndex];
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

function loadStat() {
    var ads = $('#adsstat-ads').val(),
        month = $('#adsstat-month').val();

    if (!ads || !month) {
        $('#stat-summary, #stat-charts').hide();
        return;
    }

    $('#stat-loading').show();
    $('#stat-summary, #stat-charts').hide();

    var url = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data +
        '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=viewmap' +
        '&ads=' + ads + '&month=' + month + '&nocache=' + new Date().getTime();

    $.ajax({
        url: url,
        dataType: 'json',
        success: function(data) {
            if (data.status == 'error') {
                return nukeviet.alert(data.message);
            }

            $('#total-clicks').text(data.total_clicks_formatted);

            var types = ['date', 'country', 'browser', 'os'];
            $.each(types, function(index, type) {
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
                    return;
                }

                const chartCtn = $('#chart-' + type);
                chartCtn.html('<div class="text-muted text-center">' + chartCtn.data('empty-mess') + '</div>');
            });

            $('#stat-loading').hide();
            $('#stat-summary, #stat-charts').show();
        },
        error: function() {
            $('#stat-loading').hide();
            nukeviet.alert('Error loading statistics');
        }
    });
}

$(function() {
    // Add banner
    if ($('#banner_plan').length) {
        $('#banner_plan').change(function() {
            var typeimage = $('option:selected', $(this)).data('image'),
                uploadtype = $('option:selected', $(this)).data('uploadtype'),
                form = $(this).parents('form');
            if (!!typeimage) {
                $('#banner_uploadtype').text(' (' + uploadtype + ')').show();
                $('#banner_uploadimage').show();
                $('.file', form).addClass('required');
                $('.url', form).removeClass('required');
            } else {
                $('#banner_uploadimage').hide();
                $('.file', form).removeClass('required');
                $('.url', form).addClass('required');
            }
        });
        $('#banner_plan').trigger('change')
    }

    $('body').on('submit', '[data-toggle=afSubmit]', function(e) {
        e.preventDefault();
        afSubmit(this)
    });

    $('body').on('keypress', '[data-toggle=errorHidden][data-event=keypress]', function() {
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=errorHidden][data-event=change]', function(e) {
        e.preventDefault();
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=loadStat]', function(e) {
        e.preventDefault();
        loadStat()
    });
});
