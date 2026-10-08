/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * Hiển thị thông báo lỗi dạng modal
 *
 * @param {string} html
 * @param {string} [title] Mặc định là tiêu đề lỗi của trang
 */
function nvInstallModal(html, title) {
    const modalEl = document.getElementById('install-modal');
    modalEl.querySelector('.modal-title').textContent = title || modalEl.dataset.titleError;
    modalEl.querySelector('.modal-body').innerHTML = html;
    modalEl.querySelector('.modal-footer').classList.add('d-none');
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

/**
 * Hộp thoại xác nhận dạng modal, thay cho confirm()
 *
 * @param {string} html
 * @param {Function} onConfirm Gọi khi người dùng bấm đồng ý
 */
function nvInstallConfirm(html, onConfirm) {
    const modalEl = document.getElementById('install-modal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalEl.querySelector('.modal-title').textContent = modalEl.dataset.titleConfirm;
    modalEl.querySelector('.modal-body').innerHTML = html;
    modalEl.querySelector('.modal-footer').classList.remove('d-none');
    $('[data-toggle="modalConfirm"]', modalEl).off('click').on('click', function() {
        modal.hide();
        onConfirm();
    });
    modal.show();
}

/**
 * Escape chuỗi trước khi chèn vào HTML
 *
 * @param {string} str
 * @returns {string}
 */
function nvInstallEscape(str) {
    return $('<div>').text(str == null ? '' : String(str)).html();
}

/**
 * Kiểm tra form, chỉ đánh dấu các trường lỗi chứ không tô xanh trường hợp lệ
 *
 * @param {HTMLFormElement} form
 * @returns {boolean}
 */
function nvInstallValidate(form) {
    let valid = true;
    $(form).find('input, select, textarea').each(function() {
        const invalid = this.willValidate && !this.checkValidity();
        $(this).toggleClass('is-invalid', invalid);
        if (invalid) {
            valid = false;
        }
    });
    return valid;
}

$(function() {
    const baseSiteUrl = $('body').data('base-siteurl');

    // Bước 1: Chọn ngôn ngữ
    $('[data-toggle="selectLang"]').on('change', function() {
        const url = $(this).val();
        if (url == 'other') {
            window.open($(this).data('other-url'), '_blank');
            return;
        }
        window.location.href = url;
    });

    // Bước 1: Kiểm tra máy chủ hỗ trợ rewrite rồi mới hiện nút tiếp tục
    $('[data-toggle="checkRewrite"]').each(function() {
        const $next = $(this);
        $.ajax({
            url: $next.data('url'),
            type: 'GET',
            cache: false
        }).done(function(res) {
            const supports = ['rewrite_mode_apache', 'rewrite_mode_iis', 'nginx'].includes(res) ? res : '';
            const expires = new Date();
            expires.setDate(expires.getDate() + 1);
            document.cookie = 'supports_rewrite=' + encodeURIComponent(supports) + '; expires=' + expires.toUTCString() + '; path=' + baseSiteUrl + '; SameSite=Lax';
        }).always(function() {
            $next.prop('hidden', false);
        });
    });

    // Bước 2: Tự dò thư mục gốc FTP
    $('[data-toggle="findFtpPath"]').on('click', function() {
        const $btn = $(this);
        const $form = $btn.closest('form');
        const data = {
            ftp_server: $('[name="ftp_server"]', $form).val(),
            ftp_port: $('[name="ftp_port"]', $form).val(),
            ftp_user_name: $('[name="ftp_user_name"]', $form).val(),
            ftp_user_pass: $('[name="ftp_user_pass"]', $form).val(),
            tetectftp: 1
        };

        if (data.ftp_server == '' || data.ftp_user_name == '' || data.ftp_user_pass == '') {
            nvInstallModal('<p class="mb-0">' + nvInstallEscape($form.data('error-empty')) + '</p>');
            return;
        }

        $btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: data
        }).done(function(res) {
            res = String(res).split('|');
            if (res[0] == 'OK') {
                $('[name="ftp_path"]', $form).val(res[1]);
            } else {
                nvInstallModal('<p class="mb-0">' + nvInstallEscape(res[1]) + '</p>');
            }
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });

    // Bước 5: Kiểm tra loại CSDL có đủ file hỗ trợ
    const $dbtype = $('[name="dbtype"]');
    if ($dbtype.length) {
        const checkDbDriver = function() {
            $('#dbtype-check').removeClass('d-none');
            $.ajax({
                type: 'POST',
                cache: false,
                url: $dbtype.data('url') + '&nocache=' + new Date().getTime(),
                data: {
                    checkdbtype: $dbtype.val()
                },
                dataType: 'json'
            }).done(function(res) {
                if (res.status == 'success') {
                    return;
                }
                let html = '';
                const files = Object.values(res.files || {});
                if (files.length) {
                    html += '<p><a href="' + nvInstallEscape(res.link) + '" target="_blank" rel="noopener">' + nvInstallEscape(res.message) + '</a></p><ul class="mb-0">';
                    files.forEach(function(file) {
                        html += '<li><code>' + nvInstallEscape(file) + '</code></li>';
                    });
                    html += '</ul>';
                } else {
                    html += '<p class="mb-0">' + nvInstallEscape(res.message) + '</p>';
                }
                nvInstallModal(html);
                $dbtype.find('option').prop('selected', false);
            }).always(function() {
                $('#dbtype-check').addClass('d-none');
            });
        };
        checkDbDriver();
        $dbtype.on('change', checkDbDriver);
    }

    // Bước 5: Cài đặt CSDL theo 3 giai đoạn system, module, finish
    $('[data-toggle="installDb"]').each(function() {
        const $form = $(this);
        const ajaxUrl = $form.attr('action');
        const $progress = $('#nv_install_progress');
        const $nav = $('#step5_nav');
        const $log = $('#nv_progress_log');
        const lang = {
            installingDb: $form.data('lang-installing-db'),
            installingModules: $form.data('lang-installing-modules'),
            installingModule: $form.data('lang-installing-module'),
            finalizing: $form.data('lang-finalizing'),
            installDone: $form.data('lang-install-done'),
            installError: $form.data('lang-install-error')
        };
        let formData = '';

        const logMsg = function(html, isError) {
            $log.append('<div class="' + (isError ? 'text-danger' : '') + '">' + html + '</div>');
            $log.scrollTop($log[0].scrollHeight);
        };

        const setProgress = function(percent, caption) {
            $('#nv_progress_bar').css('width', percent + '%').text(percent + '%');
            if (caption) {
                $('#nv_progress_caption').text(caption);
            }
        };

        const showForm = function() {
            $form.prop('hidden', false);
            $nav.prop('hidden', false);
        };

        const fail = function(message) {
            $('#nv_progress_bar').removeClass('progress-bar-animated').addClass('bg-danger');
            logMsg(nvInstallEscape(lang.installError + ': ' + (message || '')), true);
        };

        const failHttp = function(xhr) {
            fail('HTTP ' + xhr.status);
        };

        const installModules = function(modules, idx, afterDone) {
            if (idx >= modules.length) {
                afterDone();
                return;
            }
            const mod = modules[idx];
            setProgress(Math.round(30 + ((idx + 1) / modules.length) * 60), lang.installingModule.replace('%s', mod.title));
            logMsg(nvInstallEscape(lang.installingModule).replace('%s', '<strong>' + nvInstallEscape(mod.title) + '</strong> (' + nvInstallEscape(mod.module_file) + ')'));

            setTimeout(function() {
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData + '&ajax_action=module&module_title=' + encodeURIComponent(mod.title) + '&module_file=' + encodeURIComponent(mod.module_file),
                    dataType: 'json'
                }).done(function(res) {
                    if (res.status === 'success') {
                        installModules(modules, idx + 1, afterDone);
                    } else {
                        fail(res.message);
                    }
                }).fail(failHttp);
            }, 800);
        };

        const finish = function() {
            setProgress(92, lang.finalizing);
            logMsg(nvInstallEscape(lang.finalizing) + '...');
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData + '&ajax_action=finish',
                dataType: 'json'
            }).done(function(res) {
                if (res.status === 'success') {
                    setProgress(100, lang.installDone);
                    $('#nv_progress_bar').removeClass('progress-bar-animated').addClass('bg-success');
                    logMsg(nvInstallEscape(lang.installDone));
                    setTimeout(function() {
                        window.location.href = res.redirect;
                    }, 800);
                } else {
                    fail(res.message);
                }
            }).fail(failHttp);
        };

        $form.on('submit', function(e) {
            e.preventDefault();

            if (!nvInstallValidate(this)) {
                return;
            }

            formData = $form.serialize();

            $form.prop('hidden', true);
            $nav.prop('hidden', true);
            $log.empty();
            $('#nv_progress_bar').removeClass('bg-danger bg-success').addClass('progress-bar-animated');
            $progress.prop('hidden', false);
            setProgress(5, lang.installingDb);
            logMsg(nvInstallEscape(lang.installingDb) + '...');

            // Giai đoạn 1: Tạo CSDL hệ thống, db_detete đã nằm trong formData nếu được chọn
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData + '&ajax_action=system',
                dataType: 'json'
            }).done(function(res) {
                if (res.has_table) {
                    // CSDL đã có bảng cũ, yêu cầu xác nhận xóa
                    $progress.prop('hidden', true);
                    $('#db_detete_wrap').prop('hidden', false);
                    showForm();
                    return;
                }
                if (res.status !== 'success') {
                    $progress.prop('hidden', true);
                    showForm();
                    nvInstallModal('<p class="mb-0">' + nvInstallEscape(lang.installError + ': ' + (res.message || '')) + '</p>');
                    return;
                }

                const modules = res.modules || [];
                setProgress(30, lang.installingModules);
                logMsg(nvInstallEscape(lang.installingModules) + ' (' + modules.length + ')...');

                // Giai đoạn 2 và 3: Cài từng module rồi hoàn tất
                installModules(modules, 0, finish);
            }).fail(function(xhr) {
                $progress.prop('hidden', true);
                showForm();
                nvInstallModal('<p class="mb-0">' + nvInstallEscape(lang.installError + ': HTTP ' + xhr.status) + '</p>');
            });
        });
    });

    // Bước 6: Kiểm tra form thông tin website
    $('[data-toggle="siteConfig"]').on('submit', function(e) {
        const repass = document.getElementById('re_password_iavim');
        repass.setCustomValidity(repass.value === document.getElementById('nv_password_iavim').value ? '' : 'mismatch');
        if (!nvInstallValidate(this)) {
            e.preventDefault();
        }
    });

    // Bỏ đánh dấu lỗi khi người dùng sửa lại trường đó
    $(document).on('input change', '.is-invalid', function() {
        $(this).removeClass('is-invalid');
    });
});
