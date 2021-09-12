/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if (!!Chart && $('[data-chart-type]').length) {
        $('[data-chart-type]').each(function() {
            var canvasID = $(this).attr('id'),
                chartType = $(this).data('chart-type'),
                chartData = {
                    labels: $(this).data('labels').split('_'),
                    datasets: [{
                        data: $(this).data('values').split('_').map(function(num) {
                            return parseInt(num, 10)
                        })
                    }]
                };

            if ('line' == chartType || 'bar' == chartType) {
                chartData['datasets'][0].backgroundColor = 'rgb(' + $(this).data('bg') + ')';
                chartData['datasets'][0].borderColor = 'rgb(' + $(this).data('bg') + ')';
                chartData['datasets'][0].fill = !1;

                var xtitle = $(this).data('xtitle'),
                    ytitle = $(this).data('ytitle');

                new Chart(document.getElementById(canvasID).getContext("2d"), {
                    type: chartType,
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
                                    },
                                    usePointStyle: true
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
            } else if ('pie' == chartType) {
                chartData['datasets'][0].backgroundColor = $(this).data('bg').split('|').map(function(color) {
                    return 'rgb(' + color + ')'
                });

                new Chart(document.getElementById(canvasID).getContext("2d"), {
                    type: chartType,
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
            }
        })
    }
});
