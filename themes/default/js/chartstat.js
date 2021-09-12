/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

 $(function() {
    if (!!Chart) {
        if ($('[data-chart-type=line]').length) {
            $('[data-chart-type=line]').each(function() {
                var obj = $(this).attr('id'),
                    chartData = {
                        labels: $(this).data('labels').split('_'),
                        datasets: [{
                            label: '',
                            backgroundColor: 'rgb(' + $(this).data('bg') + ')',
                            borderColor: 'rgb(' + $(this).data('border') + ')',
                            data: $(this).data('values').split('_').map(function(num) {
                                return parseInt(num, 10)
                            }),
                            fill: !1
                        }]
                    },
                    xtitle = $(this).data('xtitle'),
                    ytitle = $(this).data('ytitle');
    
                new Chart(document.getElementById(obj).getContext("2d"), {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return xtitle + ': ' + chartData['labels'][tooltipItems[0].parsed.x]
                                    },
                                    label: function(context) {
                                        var label = ' ' + ytitle + ': ';
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y;
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: xtitle
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: ytitle
                                }
                            }
                        }
                    }
                })
            })
        }

        if ($('[data-chart-type=pie]').length) {
            $('[data-chart-type=pie]').each(function() {
                var obj = $(this).attr('id'),
                    chartData = {
                        labels: $(this).data('labels').split('_'),
                        datasets: [{
                            label: '',
                            backgroundColor: $(this).data('bg').split('|').map(function(color) {
                                return 'rgb(' + color + ')'
                            }),
                            data: $(this).data('values').split('_').map(function(num) {
                                return parseInt(num, 10)
                            })
                        }]
                    };
                new Chart(document.getElementById(obj).getContext("2d"), {
                    type: 'pie',
                    data: chartData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                })
            })
        }
    }
});
