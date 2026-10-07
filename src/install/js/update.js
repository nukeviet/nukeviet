/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * Đặt trạng thái cho một tác vụ hoặc một file trong danh sách
 *
 * @param {jQuery} $item
 * @param {string} status iok, ierror, iwarn, iload hoặc rỗng
 * @param {string} [title]
 */
function nvUpdateSetStatus($item, status, title) {
    $item.removeClass('iok ierror iwarn iload').addClass(status);
    if (title !== undefined) {
        $item.attr('title', title);
    }
}

/**
 * Gửi yêu cầu xóa gói cập nhật
 *
 * @param {jQuery} $btn Nút bấm chứa data-url, data-checkss
 * @param {Function} onSuccess
 * @param {Function} onFail Nhận mảng lỗi
 */
function nvUpdateDeletePackage($btn, onSuccess, onFail) {
    nvInstallConfirm('<p class="mb-0">' + nvInstallEscape(nv_is_del_confirm[0]) + '</p>', function() {
        $btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            // URL có thể đã được rewrite thành dạng /admin/vi/webtools/deleteupdate/ nên phải kiểm tra trước khi nối tham số
            url: $btn.data('url') + ($btn.data('url').indexOf('?') === -1 ? '?' : '&') + 'nocache=' + new Date().getTime(),
            data: {
                checkss: $btn.data('checkss')
            },
            dataType: 'json'
        }).done(function(res) {
            if (res.success) {
                onSuccess();
                return;
            }
            $btn.prop('disabled', false);
            onFail(res.error || []);
        }).fail(function(xhr, text) {
            $btn.prop('disabled', false);
            onFail([text]);
        });
    });
}

/**
 * Hiện danh sách lỗi dạng modal
 *
 * @param {Array} errors
 */
function nvUpdateShowErrors(errors) {
    nvInstallModal(errors.map(function(error) {
        return '<p class="mb-1">' + nvInstallEscape(error) + '</p>';
    }).join(''));
}

$(function() {
    // Xóa gói cập nhật rồi chuyển về trang quản trị
    $('[data-toggle="deleteUpdatePackage"]').on('click', function() {
        const $btn = $(this);
        nvUpdateDeletePackage($btn, function() {
            window.location = $btn.data('redirect');
        }, nvUpdateShowErrors);
    });

    // Sao lưu CSDL, sao lưu code, kết quả trả về là HTML có link tải
    $('[data-toggle="updateDump"]').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $result = $($btn.data('target'));
        if ($btn.hasClass('disabled')) {
            return;
        }
        $btn.addClass('disabled').prepend('<span class="spinner-border spinner-border-sm me-1"></span>');
        $.get($btn.attr('href')).done(function(res) {
            $result.append('<div class="mt-1">' + res + '</div>');
        }).fail(function(xhr) {
            nvInstallModal('<p class="mb-0">HTTP ' + xhr.status + '</p>');
        }).always(function() {
            $btn.removeClass('disabled').find('.spinner-border').remove();
        });
    });

    // Chạy lần lượt các tác vụ cập nhật CSDL
    $('#update-tasks').each(function() {
        const $box = $(this);
        const lang = {
            navConfirm: $box.data('lang-nav-confirm'),
            taskiload: $box.data('lang-taskiload'),
            taskierror: $box.data('lang-taskierror'),
            taskiwarn: $box.data('lang-taskiwarn'),
            taskiok: $box.data('lang-taskiok'),
            do1Error: $box.data('lang-do1-error'),
            do2Error: $box.data('lang-do2-error'),
            allComplete: $box.data('lang-all-complete'),
            allCompleteAlert: $box.data('lang-all-complete-alert'),
            taskLoad: $box.data('lang-task-load'),
            taskLoadMessage: $box.data('lang-task-load-message'),
            nextStep: $box.data('lang-next-step')
        };
        const state = {
            isStart: false,
            isAlert: false,
            nextFuncs: String($box.data('next-funcs')),
            nextFuncsName: String($box.data('next-funcs-name')),
            nextUrl: ''
        };

        const $task = function(id) {
            return $(document.getElementById(id));
        };

        const showLoad = function(message) {
            $('#nv-loading').html('<div class="spinner-border text-primary mb-2" role="status"></div><p class="mb-0">' + nvInstallEscape(lang.taskLoad) + ' <strong>' + nvInstallEscape(message) + '</strong><br>' + nvInstallEscape(lang.taskLoadMessage) + '.</p>').prop('hidden', false);
        };

        const hideLoad = function() {
            $('#nv-loading').html('').prop('hidden', true);
        };

        const setStop = function() {
            state.isStart = false;
            $('#nv-message').html('<div class="alert alert-danger mb-0">' + nvInstallEscape(lang.do1Error) + ' <strong>&quot;' + nvInstallEscape(state.nextFuncsName) + '&quot;</strong> ' + lang.do2Error + '</div>').prop('hidden', false);
        };

        const setComplete = function() {
            state.isStart = false;
            const ok = !state.isAlert;
            $('#nv-message').html('<div class="alert ' + (ok ? 'alert-success' : 'alert-warning') + ' mb-0">' + nvInstallEscape(ok ? lang.allComplete : lang.allCompleteAlert) + '</div>').prop('hidden', false);
            $('#control_t').append('<span class="next_step"><a class="btn btn-primary" href="' + $box.data('next-step-url') + '">' + nvInstallEscape(lang.nextStep) + ' <i class="fa-solid fa-arrow-right"></i></a></span>');
        };

        const load = function() {
            const url = state.nextUrl || ($box.data('update-url') + '?step=2&substep=3&load=' + encodeURIComponent(state.nextFuncs));

            $.get(url).done(function(r) {
                // status|funcname|functitle|url|lang|message|stop|allcomplete
                const check = String(r).split('|');
                hideLoad();

                if (check.length < 8) {
                    check[6] = '1';
                }

                if (check[0] == '0') {
                    state.isAlert = true;
                    if (check[6] == '1') {
                        nvUpdateSetStatus($task(state.nextFuncs), 'ierror', lang.taskierror);
                    } else {
                        nvUpdateSetStatus($task(state.nextFuncs), 'iwarn', lang.taskiwarn);
                    }
                } else {
                    nvUpdateSetStatus($task(state.nextFuncs), 'iok', lang.taskiok);
                }

                if (check[6] == '1') {
                    setStop();
                } else if (check[7] == '1') {
                    setComplete();
                } else {
                    state.nextFuncs = check[1];
                    state.nextFuncsName = check[2];
                    state.nextUrl = (check[3] != 'NO' && check[3] != '') ? check[3] : '';
                    showLoad((check[5] != 'NO' && check[5] != '') ? state.nextFuncsName + ' - ' + check[5] : state.nextFuncsName);
                    nvUpdateSetStatus($task(state.nextFuncs), 'iload', lang.taskiload);
                    setTimeout(load, 1000);
                }
            }).fail(function(xhr) {
                hideLoad();
                nvUpdateSetStatus($task(state.nextFuncs), 'ierror', lang.taskierror);
                setStop();
                nvInstallModal('<p class="mb-0">HTTP ' + xhr.status + '</p>');
            });
        };

        $('[data-toggle="updateTaskStart"]').on('click', function() {
            $('#nv-message').prop('hidden', true);
            state.isStart = true;
            showLoad(state.nextFuncsName);
            nvUpdateSetStatus($task(state.nextFuncs), 'iload', lang.taskiload);
            setTimeout(load, 1000);
        });

        window.addEventListener('beforeunload', function(e) {
            if (state.isStart) {
                e.preventDefault();
                e.returnValue = lang.navConfirm;
                return lang.navConfirm;
            }
        });
    });

    // Di chuyển các file của gói nâng cấp
    $('#update-move').each(function() {
        const $box = $(this);
        let isStart = false;

        const start = function() {
            isStart = true;
            $('#ftp_nosupport, #check_ftp').prop('hidden', true);
            $('#nv-toolmove').prop('hidden', true);
            $('#nv-message').html('<div class="text-center"><div class="spinner-border text-primary mb-2" role="status"></div><p class="mb-0">' + nvInstallEscape($box.data('lang-load-waiting')) + '</p></div>').prop('hidden', false);

            $.get($box.data('update-url') + '?step=2&substep=4&move').done(function(r) {
                isStart = false;
                if (r == 'OK') {
                    nvUpdateSetStatus($('.update-task', $box), 'iok');
                    $('#nv-message').html('<div class="alert alert-success mb-0">' + $box.data('ok-message') + '</div>');
                    $('#control_t').append('<span class="next_step"><a class="btn btn-primary" href="' + $box.data('next-step-url') + '">' + nvInstallEscape($box.data('lang-next-step')) + ' <i class="fa-solid fa-arrow-right"></i></a></span>');
                    return;
                }

                // Lỗi thì hiện lại cấu hình FTP và cho thực hiện lại
                $('#ftp_nosupport, #check_ftp').prop('hidden', false);
                $('#nv-message').prop('hidden', true);
                $('#nv-toolmove').removeClass('alert-info').addClass('alert-danger').html(
                    '<p>' + r + '</p>' +
                    '<button type="button" class="btn btn-primary btn-sm mb-2" data-toggle="updateMoveStart"><i class="fa-solid fa-rotate"></i> ' + nvInstallEscape($box.data('lang-move-redo')) + '</button>' +
                    '<p class="mb-1">' + nvInstallEscape($box.data('lang-move-redo-message')) + '</p>' +
                    '<p class="mb-0">' + $box.data('lang-move-redo-manual') + '</p>'
                ).prop('hidden', false);
            }).fail(function(xhr) {
                isStart = false;
                $('#nv-message').prop('hidden', true);
                $('#nv-toolmove').prop('hidden', false);
                nvInstallModal('<p class="mb-0">HTTP ' + xhr.status + '</p>');
            });
        };

        $box.on('click', '[data-toggle="updateMoveStart"]', start);

        window.addEventListener('beforeunload', function(e) {
            if (isStart) {
                e.preventDefault();
                e.returnValue = $box.data('lang-nav-confirm');
                return $box.data('lang-nav-confirm');
            }
        });
    });

    // Bước 3: Tải thông tin phiên bản, nâng cấp toàn hệ thống thì tải thêm thông tin các module
    $('[data-toggle="updateVersionInfo"]').each(function() {
        const $box = $(this);
        $box.load($box.data('url'), function() {
            const modUrl = $box.data('mod-url');
            if (!modUrl) {
                return;
            }
            const $mod = $('<div class="mt-3"><div class="alert alert-light d-flex align-items-center gap-2 mb-0"><span class="spinner-border spinner-border-sm text-primary"></span> ' + nvInstallEscape($box.data('lang-waiting-continue')) + '</div></div>');
            $box.append($mod);
            setTimeout(function() {
                $mod.load(modUrl);
            }, 1000);
        });
    });

    // Bước 3: Kết thúc và xóa gói cập nhật
    $('[data-toggle="deleteUpdatePackageEnd"]').on('click', function() {
        const $btn = $(this);
        nvUpdateDeletePackage($btn, function() {
            $btn.closest('.alert').prop('hidden', true);
            $('#endupdate-success, #endupdate-nav').prop('hidden', false);
        }, function(errors) {
            nvUpdateShowErrors(errors);
            $('#endupdate-error, #endupdate-nav').prop('hidden', false);
        });
    });
});
