/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

let notificationObj;

function initManagerPopovers() {
    const labelId = notificationObj.data('label-id');
    const labelUsername = notificationObj.data('label-username');
    const labelFullname = notificationObj.data('label-fullname');
    $('#generate_page [data-toggle="viewUser"]', notificationObj).each(function() {
        const el = $(this);
        new bootstrap.Popover(this, {
            trigger: 'focus',
            placement: 'top',
            html: true,
            sanitize: false,
            content: labelId + ': ' + el.data('uid') + '<br>' + labelUsername + ': ' + el.data('username') + '<br>' + labelFullname + ': ' + el.data('fullname')
        });
    });
}

function initNotificationAction() {
    const form = document.getElementById('notification_action_form');
    if (!form) return;
    const $form = $(form);
    const ajaxUrl = $form.attr('action');

    $('.receiver_ids', $form).select2({
        ajax: {
            type: 'POST',
            cache: false,
            url: ajaxUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    get_user_json: 1,
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            }
        },
        escapeMarkup: function(markup) { return markup; },
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            return '<div>' + repo.fullname + '<br>(ID #' + repo.id + ', ' + repo.username + ', ' + repo.email + ')</div>';
        },
        templateSelection: function(repo) {
            return repo.fullname || repo.text;
        }
    });

    $('[name=add_time], [name=exp_time]', $form).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });

    $('.sample_exp_time', $form).on('change', function() {
        const val = parseInt($(this).val());
        const origTime = $('[name=add_time]', $form).val();
        if (val && origTime !== '') {
            const parts = origTime.split('/');
            const date = new Date(parts[2], parts[1] - 1, parts[0], 0, 0, 0);
            date.setDate(date.getDate() + val);
            const expTime = date.getDate().toString().padStart(2, '0') + '/' + (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getFullYear();
            $('[name=exp_time]', $form).val(expTime);
            $('[name=exp_hour]', $form).val($('[name=add_hour]', $form).val());
            $('[name=exp_min]', $form).val($('[name=add_min]', $form).val());
        }
        $(this).val('0');
    });
}

$(function() {
    notificationObj = $('#notifications_manager');
    if (!notificationObj.length) return;

    notificationObj.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        const obj = $(this).parents('.notification-item');
        $('.more', obj).hide();
        $('.morecontent', obj).show();
    });

    notificationObj.on('click', '[data-toggle=inform_action]', function() {
        const type = $(this).data('type');
        const title = $(this).data('title');
        const id = type === 'edit' ? $(this).parents('.notification-item').data('id') : '0';
        const url = notificationObj.data('url');
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: { action: id },
            dataType: 'json',
            success: function(result) {
                if ('error' === result.status) {
                    nvToast(result.mess, 'error');
                } else if ('OK' === result.status) {
                    $('.action-title', notificationObj).text(title);
                    $('#notification-action .action-body', notificationObj).html(result.content);
                    $('#generate_page', notificationObj).fadeOut(200, function() {
                        $('#notification-action', notificationObj).removeClass('d-none');
                        initNotificationAction();
                    });
                }
            }
        });
    });

    notificationObj.on('click', '[data-toggle=notification_action_cancel]', function() {
        $('#notification-action', notificationObj).addClass('d-none');
        $('#generate_page', notificationObj).show();
    });

    notificationObj.on('submit', '#notification_action_form', function(e) {
        e.preventDefault();
        const url = $(this).attr('action');
        const data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: data,
            dataType: 'json',
            success: function(result) {
                if ('error' === result.status) {
                    nvToast(result.mess, 'error');
                } else if ('OK' === result.status) {
                    $('.change-status', notificationObj).trigger('change');
                }
            }
        });
    });

    $('.change-status', notificationObj).on('change', function() {
        const url = notificationObj.data('url');
        const val = $(this).val();
        const data = 'ajax=1' + ('' !== val ? '&filter=' + val : '');
        $.ajax({
            type: 'GET',
            url: url,
            data: data,
            success: function(result) {
                $('#notification-action', notificationObj).addClass('d-none');
                $('#generate_page', notificationObj).html(result).show();
                initManagerPopovers();
            }
        });
    });

    notificationObj.on('click', '[data-toggle=inform_del]', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i[data-icon]', btn);
        const orig = icon.data('icon');
        const id = btn.parents('.notification-item').data('id');
        const url = notificationObj.data('url');
        const csrf = notificationObj.data('csrf');
        const confirmMsg = notificationObj.data('delete-confirm');
        nvConfirm(confirmMsg, function() {
            if (icon.is('.fa-spinner')) return;
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: url,
                data: { delete: id, _csrf: csrf },
                dataType: 'json',
                success: function(result) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    if ('error' === result.status) {
                        nvToast(result.mess || '', 'error');
                    } else if ('OK' === result.status) {
                        $('.change-status', notificationObj).trigger('change');
                    }
                },
                error: function(xhr, text) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nvToast(text, 'error');
                }
            });
        });
    });

    initManagerPopovers();
});
