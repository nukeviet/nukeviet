/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Callback sau khi khởi tạo trình soạn thảo, chỉ định tại contents.php
let focusEditor = 'emailtemplates_default_content';
function setEditorReady(editor) {
    editor.editing.view.document.on('change:isFocused', () => {
        focusEditor = editor.config.get('nukeviet').editorId;
    });
}

$(function() {
    // Thay đổi thứ tự danh mục
    $('[data-toggle="weightcat"]').on('change', function() {
        var weight = $(this).val();
        var catid = $(this).data('catid');
        $(this).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(),
            data: {
                'changeweight': $(this).data('checksess'),
                'catid': catid,
                'new_weight': weight
            },
            cache: false,
            success: function() {
                location.reload();
            },
            error: function(jqXHR, exception, te) {
                console.log(jqXHR, exception, te);
                nvToast(te, 'error');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });
    });

    // Xóa danh mục
    $('[data-toggle="delcat"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(),
                data: {
                    'delete': btn.data('checksess'),
                    'catid': btn.data('catid')
                },
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    var r_split = res.split('_');
                    if (r_split[0] != 'OK') {
                        nvToast(nv_is_change_act_confirm[2], 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa mẫu email
    $('[data-click="deltpl"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
                data: {
                    'delete': btn.data('checksess'),
                    'emailid': btn.data('emailid')
                },
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    var r_split = res.split('_');
                    if (r_split[0] != 'OK') {
                        nvToast(nv_is_change_act_confirm[2], 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa đính kèm
    $(document).on('click', '[data-toggle="attdel"]', function(e) {
        e.preventDefault();
        $(this).closest('.input-group').remove();
    });

    // Thay đổi hiển thị nội sung soạn email theo ngôn ngữ
    $('[data-toggle="collapsecontentchange"]').on('click', function(e) {
        e.preventDefault();
        $('[data-toggle="collapsecontentlabel"]').html($(this).html());
        $('[data-toggle="collapsecontent"]').removeClass('show');
        $('#collapse-content-' + $(this).data('lang')).addClass('show');
        $('[name="showlang"]').val($(this).data('lang'));
    });

    var formc = $('#form-emailtemplates');
    if (formc.length) {
        var fieldTimer;
        $('[name="pids[]"]').on('change', function() {
            if (fieldTimer) {
                clearTimeout(fieldTimer);
            }
            $('#merge-fields-content').html('');
            var pids = $(this).val();
            if ($('#tpl_sys_pids').length) {
                $('#tpl_sys_pids').find('option').each(function() {
                    pids.push($(this).attr('value'));
                });
            }
            fieldTimer = setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=contents&nocache=' + new Date().getTime(),
                    data: {
                        'getMergeFields': 1,
                        'pids': pids
                    },
                    cache: false,
                    success: function(res) {
                        $('#merge-fields-content').html(res);
                        fieldTimer = 0;
                    },
                    error: function(jqXHR, exception, t) {
                        console.log(jqXHR, exception, t);
                        fieldTimer = 0;
                    }
                });
            }, 500);
        });
        $('[name="pids[]"]').trigger('change');

        if (typeof CKEDITOR != 'undefined') {
            for (const [key, value] of Object.entries(CKEDITOR.instances)) {
                value.on('focus', function() {
                    focusEditor = key;
                });
            }
        } else {
            $('[data-toggle="textareaemailcontent"]').filter(':visible').on('focus', function() {
                focusEditor = $(this).attr('id');
            });
        }
        $(document).on('click', '[data-toggle="fchoose"]', function(e) {
            e.preventDefault();
            if (typeof CKEDITOR != 'undefined') {
                CKEDITOR.instances[focusEditor].insertHtml(' {' + $(this).data('value') + '}');
            } else if (window.nveditor != "undefined") {
                const editor = window.nveditor[focusEditor];

                editor.model.change(() => {
                    editor.model.insertContent(editor.data.toModel(editor.data.processor.toView(' {' + $(this).data('value') + '}')), editor.model.document.selection);
                });

                const editorElement = editor.ui.getEditableElement();
                const rect = editorElement.getBoundingClientRect();
                const absoluteTop = rect.top + window.pageYOffset;
                $('html, body').animate({ scrollTop: absoluteTop - 100 }, 200);

                editor.editing.view.focus();
            } else {
                $('#' + focusEditor).val($('#' + focusEditor).val() + ' {' + $(this).data('value') + '}');
            }
        });
        $('[data-toggle="attadd"]').prop('disabled', false);

        // Thêm đính kèm
        $('[data-toggle="attadd"]').on('click', function(e) {
            e.preventDefault();
            var size = $(this).data('size');
            size++;
            $(this).data('size', size);
            var area_new = 'tpl_att' + size;
            $('#tpl-attach-temp').find('[name="attachments[]"]').attr('id', area_new);
            $('#tpl-attach-temp').find('[data-toggle="selectfile"]').attr('data-target', area_new);
            $('#tpl-attachments').append($('#tpl-attach-temp').html());
        });
    }

    // Select 2
    if ($(".select2").length) {
        $(".select2").select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });
    }

    // Pickdate
    if ($('.datepicker-search').length) {
        $('.datepicker-search').datepicker({
            dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl'
        });
    }

    // Thêm phần tử cho dạng field list khi gửi test mẫu email
    $('[data-toggle="addField"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var ctn = $this.parent().find('.field-ctns');
        var lastItem = $('.item:last', ctn);

        var offset = 0;
        if (lastItem.length) {
            offset = lastItem.data('offset') + 1;
        }

        ctn.append(`<div class="input-group mb-1 item" data-offset="` + offset + `">
            <input type="text" class="form-control" id="f_` + $this.data('fieldname') + `_` + offset + `" name="f_` + $this.data('fieldname') + `[]" value="" placeholder="$` + $this.data('fieldname') + `[]">
            <button data-toggle="delField" class="btn btn-danger" type="button"><i class="fa fa-times"></i></button>
        </div>`);
    });

    // Xóa phần tử cho dạng field list khi gửi test mẫu email
    $(document).on('click', '[data-toggle="delField"]', function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });
});
