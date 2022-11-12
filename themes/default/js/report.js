/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if ($('[data-toggle=error-report]').length && $('[data-toggle=error-report-modal]').length && $('[data-toggle=error-report-form]').length) {
        var errorReport = $('[data-toggle=error-report]'),
            errorReportModal = $('[data-toggle=error-report-modal]'),
            errorReportForm = $('[data-toggle=error-report-form]'),
            errorReportTitle = $('.modal-title', errorReportModal).text(),
            errorReportModalReset = function() {
                $('.has-error', errorReportModal).removeClass('has-error');
                $('.invalid-feedback', errorReportModal).text('').hide()
            },
            errorReportTimer = null;
        errorReportModal.on('show.bs.modal', function() {
            errorReportModalReset()
        }).on('shown.bs.modal', function() {
            $('.auto-resize', this).trigger('input')
        });
        $('.auto-resize', errorReportModal).on('input', function() {
            this.style.height = "5px";
            this.style.height = (this.scrollHeight) + "px";
        }).on('keyup keypress', function(e) {
            if (e.keyCode === 13 || e.which === 13) {
                e.preventDefault();
                return !1
            }
        });
        $('.submit', errorReportModal).on('click', function(e) {
            errorReportModalReset();
            var report_content = trim(strip_tags($('.report_content', errorReportModal).val())),
                report_fix = trim(strip_tags($('.report_fix', errorReportModal).val().replace(/\s\s+/g, ' '))),
                report_email = trim(strip_tags($('.report_email', errorReportModal).val())),
                result;
            $('.report_fix', errorReportModal).val(report_fix);
            if (report_fix.length) {
                result = report_content.localeCompare(report_fix, undefined, {
                    sensitivity: 'accent'
                });
                if (result === 0) {
                    $('.report_fix', errorReportModal).next('.invalid-feedback').text(errorReportModal.data('samevalues')).show();
                    $('.report_fix', errorReportModal).parents('.form-group').addClass('has-error').focus();
                    return !1
                }
            }
            if (report_email.length && !nv_mailfilter.test(report_email)) {
                $('.report_email', errorReportModal).parents('.form-group').addClass('has-error').focus();
                return !1
            }
            $('[name=report_content]', errorReportForm).val(report_content);
            $('[name=report_fix]', errorReportForm).val(report_fix);
            $('[name=report_email]', errorReportForm).val(report_email);
            $('[type=submit]', errorReportForm).trigger('click')
        });
        errorReportForm.on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action'),
                data = $(this).serialize();
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: data,
                dataType: "json",
                success: function(response) {
                    $('[name=captcha], [name=g-recaptcha-response]', errorReportForm).remove();
                    alert(response.mess);
                    if ('OK' == response.status) {
                        $('[name=report_content], [name=report_fix], [name=report_email]', errorReportForm).val('');
                        errorReportModal.modal('hide')
                    }
                }
            })
        });
        errorReport.on("mouseup keyup touchend", (event) => {
            clearTimeout(errorReportTimer);
            errorReportTimer = setTimeout(() => {
                if (window.getSelection) {
                    var selection = window.getSelection();
                    if (selection.rangeCount !== 0) {
                        var selectedText = trim(strip_tags(selection.toString())),
                            charsnum = selectedText.length;
                        if (charsnum > 2 && !/\r|\n/.exec(selectedText)) {
                            if (charsnum > 250) {
                                errorReportModal.data('exceeding', true);
                                selectedText = selectedText.substr(0, selectedText.lastIndexOf(' ', 250));
                            } else {
                                errorReportModal.data('exceeding', false);
                            }
                            $('.report_content, .report_fix', errorReportModal).css({
                                'minHeight': '70px',
                                'overflow': 'hidden',
                                'resize': 'none'
                            }).val(selectedText)
                            if (!$('#report-tooltip').length) {
                                var tooltip = $('<div id="report-tooltip" style="position:absolute;display:none"></div>'),
                                    btn = $('<button type="button" class="btn btn-danger btn-block">' + errorReportTitle + '</buton>');
                                btn.on('click', function() {
                                    $('#report-tooltip').hide();
                                    var conf = true;
                                    if (errorReportModal.data('exceeding') === true) {
                                        conf = confirm(errorReportModal.data('info'))
                                    }
                                    if (conf) {
                                        errorReportModal.modal('show')
                                    }
                                });
                                tooltip.append(btn);
                                $('body').append(tooltip)
                            }
                            $('#report-tooltip').css({
                                'left': event.pageX >= 60 ? (event.pageX - 50) : 10,
                                'top': event.pageY + 15
                            }).fadeIn(200);
                        } else {
                            $('#report-tooltip').hide()
                        }
                    }
                }
            }, 100)
        })
    }
})
