/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

"use strict";

let fieldChoiceItems = 1;

// Load danh sách fields
window.nv_show_list_field = () => {
    $.ajax({
        type: 'GET',
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&qlist=1&nocache=' + new Date().getTime(),
        success: function(data) {
            $('#module_show_list').html(data);
        }
    });
};

// Load SQL choice data
window.nv_load_sqlchoice = function(choice_name_select, choice_seltected) {
    let getval = "";
    if (choice_name_select == "table") {
        let choicesql_module = $("select[name=choicesql_module]").val();
        let module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        getval = "&module=" + module_selected;
        $("#choicesql_column").html("");
    } else if (choice_name_select == "column") {
        let choicesql_module = $("select[name=choicesql_module]").val();
        let module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        let choicesql_table = $("select[name=choicesql_table]").val();
        let table_selected = (choicesql_table == "" || choicesql_table == undefined) ? '' : choicesql_table;
        getval = "&module=" + module_selected + "&table=" + table_selected;
    }
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected, function(res) {
        $('#choicesql_' + choice_name_select).html(res);

        // Gắn sự kiện change cho select mới tạo
        $('#choicesql_' + choice_name_select + ' select').on('change', function() {
            let next = $(this).data('next');
            if (next) {
                window.nv_load_sqlchoice(next, '');
            }
        });
    });
};

// Load current date cho datepicker
function nv_load_current_date() {
    $('input[name="current_date"]').on('change', function() {
        if ($(this).val() == '0') {
            $('input[name="default_date"]').prop('disabled', false).focus();
        } else {
            $('input[name="default_date"]').prop('disabled', true).val('');
        }
    });

    // Khởi tạo trạng thái ban đầu
    if ($('input[name="current_date"]:checked').val() == '1') {
        $('input[name="default_date"]').prop('disabled', true);
    }
}

// Thêm field choice item
function nv_choice_fields_additem(placeholder) {
    fieldChoiceItems++;
    let html = '<tr class="text-center">';
    html += '<td>' + fieldChoiceItems + '</td>';
    html += '<td><input class="form-control" type="text" value="" name="field_choice[' + fieldChoiceItems + ']" placeholder="' + placeholder + '" data-field-choice></td>';
    html += '<td><input class="form-control" type="text" value="" name="field_choice_text[' + fieldChoiceItems + ']"></td>';
    html += '<td><input class="form-check-input" type="radio" name="default_value_choice" value="' + fieldChoiceItems + '"></td>';
    html += '</tr>';
    $('#choiceitems_table tbody').append(html);
}

$(function () {
    // Chép vào bộ nhớ tạm
    $('.copy-btn').each(function () {
        const clipboard = new ClipboardJS(this);
        clipboard.on('success', function(e) {
            nvToast($(e.trigger).data('success'), 'success');
        });
    });

    // Trang trường dữ liệu tùy biến
    if (nv_func_name == 'fields') {
        // Thay đổi thứ tự field
        $('#module_show_list').on('change', 'select[id^="id_weight_"]', function() {
            let fid = $(this).data('fid');
            let new_vid = $(this).val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(),
                data: 'changeweight=1&fid=' + fid + '&new_vid=' + new_vid + '&checkss=' + nv_check_session,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        window.nv_show_list_field();
                    }
                    if (res.mess) {
                        nvToast(res.mess, res.status === 'success' ? 'success' : 'error');
                    }
                },
                error: function() {
                    nvToast('Error response', 'error');
                }
            });
        });

        // Sửa field
        $('#module_show_list').on('click', '[data-action="edit"]', function(e) {
            e.preventDefault();
            window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&fid=' + $(this).data('fid');
        });

        // Xóa field
        $('#module_show_list').on('click', '[data-action="delete"]', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            const fid = btn.data('fid');
            
            nukeviet.confirm(nv_is_del_confirm[0], () => {
                if (icon.is('.fa-spinner')) {
                    return;
                }
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(),
                    data: 'del=1&fid=' + fid + '&checkss=' + nv_check_session,
                    dataType: 'json',
                    success: function(res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (res.status === 'success') {
                            window.nv_show_list_field();
                        }
                        if (res.mess) {
                            nvToast(res.mess, res.status === 'success' ? 'success' : 'error');
                        }
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Lấy danh sách fields khi không sửa field
        if ($("input[name=fid]").val() == 0) {
            window.nv_show_list_field();
        }
        nv_load_current_date();

        // Load SQL choice data if present
        let sqlDataChoice = $('#sql_data_choice');
        if (sqlDataChoice.length > 0) {
            let moduleVal = sqlDataChoice.data('module');
            let tableVal = sqlDataChoice.data('table');
            let keyVal = sqlDataChoice.data('column-key');
            let valVal = sqlDataChoice.data('column-val');
            let orderVal = sqlDataChoice.data('column-order');
            let sortVal = sqlDataChoice.data('column-sort');

            window.nv_load_sqlchoice('module', moduleVal);
            window.nv_load_sqlchoice('table', tableVal);
            window.nv_load_sqlchoice('column', keyVal + '|' + valVal + '|' + orderVal + '|' + sortVal);
        }

        // Initialize field choice items count
        let existingChoices = $('[data-field-choice]');
        if (existingChoices.length > 0) {
            fieldChoiceItems = existingChoices.length;
        }

        // Datepicker initialization
        if ($('.datepicker').length > 0) {
            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true
            });
        }

        // Field type change event
        $('input[name="field_type"]').on('change', function() {
            let field_type = $(this).val();

            // Ẩn/hiện các section tương ứng
            $('#textfields, #numberfields, #datefields, #choicetypes, #choiceitems, #choicesql, #filefields').addClass('d-none');
            $('#classfields, #editorfields').addClass('d-none');

            if (field_type == 'textbox' || field_type == 'textarea' || field_type == 'editor') {
                $('#textfields').removeClass('d-none');
                if (field_type == 'editor') {
                    $('#editorfields').removeClass('d-none');
                    $('#classfields').addClass('d-none');
                } else {
                    $('#classfields').removeClass('d-none');
                    $('#editorfields').addClass('d-none');
                }
            } else if (field_type == 'number') {
                $('#numberfields').removeClass('d-none');
            } else if (field_type == 'date') {
                $('#datefields').removeClass('d-none');
            } else if (field_type == 'file') {
                $('#filefields').removeClass('d-none');
            } else {
                $('#choicetypes').removeClass('d-none');
                let choicetype = $('select[name="choicetypes"]').val();
                if (choicetype == 'field_choicetypes_sql') {
                    $('#choicesql').removeClass('d-none');
                } else {
                    $('#choiceitems').removeClass('d-none');
                }
            }
        });

        // Choice type change event
        $('select[name="choicetypes"]').on('change', function() {
            let choicetype = $(this).val();
            $('#choiceitems, #choicesql').addClass('d-none');
            if (choicetype == 'field_choicetypes_sql') {
                $('#choicesql').removeClass('d-none');
            } else {
                $('#choiceitems').removeClass('d-none');
            }
        });

        // For admin checkbox event
        $('#for_admin').on('change', function() {
            if ($(this).is(':checked')) {
                $('#row_required, #row_show_register, #row_user_editable, #row_show_profile').addClass('d-none');
                $('#required, #show_register, #user_editable, #show_profile').prop('disabled', true);
            } else {
                $('#row_required, #row_show_register, #row_user_editable, #row_show_profile').removeClass('d-none');
                $('#required, #show_register, #user_editable, #show_profile').prop('disabled', false);
            }
        });

        // Match type radio change
        $('input[name="match_type"]').on('change', function() {
            const match_type = $(this).val();
            $('input[name="match_regex"], input[name="match_callback"]').prop('disabled', true);
            $('input[name="match_' + match_type + '"]').prop('disabled', false);
        });

        // Add field choice button
        $('#add_field_choice').on('click', function() {
            const placeholder = $('[data-field-choice]').first().attr('placeholder') || '';
            nv_choice_fields_additem(placeholder);
        });

        // File type checkbox change
        $('input[name="filetype[]"]').on('change', function() {
            const filetype = $(this).val();
            const checked = $(this).is(':checked');
            
            // Chỉ xử lý khi bỏ check filetype thì uncheck tất cả mime của nó
            // Khi check filetype thì không tự động check mime, để user tự chọn
            if (!checked) {
                $(this).closest('.filetype').find('input[type="checkbox"][data-toggle="mimecheck"]').prop('checked', false);
            }

            // Show/hide photo size options
            if (filetype == 'images') {
                if (checked) {
                    $('.photo_max_size').removeClass('d-none');
                } else {
                    let hasImages = false;
                    $('input[name="filetype[]"]').each(function() {
                        if ($(this).val() == 'images' && $(this).is(':checked')) {
                            hasImages = true;
                        }
                    });
                    if (!hasImages) {
                        $('.photo_max_size').addClass('d-none');
                    }
                }
            }
        });

        // MIME checkbox change
        $('input[data-toggle="mimecheck"]').on('change', function() {
            let allChecked = true;
            let anyChecked = false;
            $(this).closest('.filetype').find('input[data-toggle="mimecheck"]').each(function() {
                if ($(this).is(':checked')) {
                    anyChecked = true;
                } else {
                    allChecked = false;
                }
            });

            const filetypeCheckbox = $(this).closest('.filetype').find('input[name="filetype[]"]');
            filetypeCheckbox.prop('checked', anyChecked);

            // Trigger filetype change event
            if (filetypeCheckbox.val() == 'images') {
                filetypeCheckbox.trigger('change');
            }
        });

        // Uncheck radio in choice items
        $('.uncheckRadio').on('click', 'input[type="radio"]', function() {
            const $radio = $(this);
            if ($radio.data('waschecked') == true) {
                $radio.prop('checked', false);
                $radio.data('waschecked', false);
            } else {
                $('.uncheckRadio input[type="radio"]').data('waschecked', false);
                $radio.data('waschecked', true);
            }
        });
    }

    // Trang câu hỏi bảo mật
    if (nv_func_name == 'question') {
        // Thêm câu hỏi mới
        $('#btn_add_question').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            const title = $('#new_title').val().trim();
            const checkss = btn.data('checkss');

            if (!title) {
                $('#new_title').focus();
                return;
            }

            if (icon.is('.fa-spinner')) {
                return;
            }

            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&nocache=' + new Date().getTime(),
                data: 'add=1&title=' + encodeURIComponent(title) + '&checkss=' + checkss,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        if (res.refresh) {
                            window.location.reload();
                        }
                    } else {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    }
                    if (res.mess) {
                        nukeviet.toast(res.mess, res.status === 'success' ? 'success' : 'error');
                    }
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Enter để thêm câu hỏi
        $('#new_title').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn_add_question').trigger('click');
            }
        });

        // Sửa câu hỏi
        $('[data-action="save"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            const qid = btn.data('qid');
            const title = $('#title_' + qid).val().trim();
            const oldTitle = $('#hidden_' + qid).val();
            const checkss = btn.data('checkss');

            if (!title) {
                $('#title_' + qid).focus();
                return;
            }

            if (title === oldTitle) {
                return;
            }

            if (icon.is('.fa-spinner')) {
                return;
            }

            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&nocache=' + new Date().getTime(),
                data: 'edit=1&qid=' + qid + '&title=' + encodeURIComponent(title) + '&checkss=' + checkss,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        if (res.refresh) {
                            window.location.reload();
                        }
                    } else {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    }
                    if (res.mess) {
                        nukeviet.toast(res.mess, res.status === 'success' ? 'success' : 'error');
                    }
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Xóa câu hỏi
        $('[data-action="delete"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            const qid = btn.data('qid');
            const checkss = btn.data('checkss');

            nukeviet.confirm(nv_is_del_confirm[0], () => {
                if (icon.is('.fa-spinner')) {
                    return;
                }

                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&nocache=' + new Date().getTime(),
                    data: 'del=1&qid=' + qid + '&checkss=' + checkss,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            if (res.refresh) {
                                window.location.reload();
                            }
                        } else {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        }
                        if (res.mess) {
                            nukeviet.toast(res.mess, res.status === 'success' ? 'success' : 'error');
                        }
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Thay đổi thứ tự
        $('[data-action="changeweight"]').on('change', function() {
            const select = $(this);
            const qid = select.data('qid');
            const new_vid = select.val();
            const checkss = select.data('checkss');

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&nocache=' + new Date().getTime(),
                data: 'changeweight=1&qid=' + qid + '&new_vid=' + new_vid + '&checkss=' + checkss,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        if (res.refresh) {
                            window.location.reload();
                        }
                    }
                    if (res.mess) {
                        nukeviet.toast(res.mess, res.status === 'success' ? 'success' : 'error');
                    }
                }
            });
        });
    }
});

// Groups management
$(document).ready(function() {
    // Get alias
    $('#get_alias_btn').on('click', function() {
        get_alias();
        return false;
    });

    $('#groupForm [name=title]').on('change', function() {
        const alias = strip_tags(trim($('#groupForm [name=alias]').val()));
        if (alias == '') {
            get_alias();
        }
    });

    function get_alias() {
        const title = strip_tags(trim($('#groupForm [name=title]').val()));
        if (title != '') {
            const btn = $('#get_alias_btn');
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            
            // Lấy ID từ URL nếu đang edit
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id') || 0;
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(), 'getAlias=1&id=' + id + '&title=' + encodeURIComponent(title), function(res) {
                $('#groupForm [name=alias]').val(res);
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
            });
        }
    }

    // Datepicker for exp_time
    if ($('[name="exp_time"]').length) {
        $('[name="exp_time"]').datepicker({
            showOn: "both",
            dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            buttonImage: null,
            buttonImageOnly: true,
            buttonText: null
        });
    }

    // Color picker
    if ($('[name="group_color"]').length && typeof $().colpick !== 'undefined') {
        $('[name="group_color"]').colpick({
            layout: 'hex',
            submit: 0,
            colorScheme: 'dark',
            onChange: function(hsb, hex, rgb, el, bySetColor) {
                $('[name="group_color_demo"]').css('background-color', '#' + hex);
                if (!bySetColor) $(el).val('#' + hex);
            }
        }).keyup(function() {
            $(this).colpickSetColor(this.value);
        });
    }

    // Xử lý xóa nhóm
    $(document).on('click', 'a.delGroup', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        
        if (confirm(nv_is_del_confirm[0])) {
            icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');
            
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                data: 'del=' + btn.data('id') + '&tokend=' + btn.data('tokend'),
                success: function(res) {
                    if (res == 'OK') {
                        location.reload();
                    } else {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                        nukeviet.toast(res, 'error');
                    }
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });

    // Xử lý thay đổi trạng thái kích hoạt
    $(document).on('change', 'input.actGroup', function() {
        const $this = $(this);
        $this.prop('disabled', true);
        
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
            data: 'act=' + $this.data('id') + '&tokend=' + $this.data('tokend') + '&rand=' + nv_randomPassword(10),
            success: function(res) {
                const parts = res.split('|');
                $this.prop('disabled', false);
                if (parts[0] == 'ERROR') {
                    $this.prop('checked', parts[1] == '1');
                }
            }
        });
    });

    // Xử lý xóa các nhóm không kích hoạt
    $(document).on('click', '[data-toggle="delInactiveGroup"]', function(e) {
        e.preventDefault();
        const btn = $(this);
        
        if (confirm(btn.data('msgconfirm'))) {
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');
            
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                data: 'deleteinactive=1&tokend=' + btn.data('tokend'),
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    nukeviet.toast(res, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });

    // Quản lý thành viên - userlist
    const urlParams = new URLSearchParams(window.location.search);
    const gid = urlParams.get('userlist');
    
    if (gid && $('#pageContent').length && typeof nv_randomPassword !== 'undefined') {
        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));

        // Tìm kiếm người dùng
        $(document).on('click', 'input[name=searchUser]', function() {
            const filtersql = $('#filtersql_val').val() || '';
            nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getuserid&area=uid&filtersql=' + filtersql, 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
            return false;
        });

        // Thêm người dùng vào nhóm
        $(document).on('click', 'input[name=addUser]', function() {
            let uid = $('#ablist input[name=uid]').val();
            uid = intval(uid);
            if (uid == 0) {
                uid = '';
            }
            $('#ablist input[name=uid]').val(uid);
            if (uid == '') {
                alert(nv_is_add_user_confirm[1]);
                $('#ablist input[name=uid]').focus();
                return false;
            }
            
            $('#pageContent input, #pageContent select').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                data: 'gid=' + gid + '&uid=' + uid + '&rand=' + nv_randomPassword(10),
                success: function(res) {
                    if (res == 'OK') {
                        $('#ablist input[name=uid]').val('');
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    } else {
                        nukeviet.toast(res, 'error');
                    }
                }
            });
            return false;
        });

        // Duyệt thành viên
        $(document).on('click', 'a.approved', function() {
            if (confirm(nv_is_add_user_confirm[0])) {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                    data: 'gid=' + gid + '&approved=' + $(this).data('id'),
                    success: function(res) {
                        if (res == 'OK') {
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        } else {
                            nukeviet.toast(res, 'error');
                        }
                    }
                });
            }
            return false;
        });

        // Từ chối thành viên
        $(document).on('click', 'a.denied', function() {
            if (confirm(nv_is_exclude_user_confirm[0])) {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                    data: 'gid=' + gid + '&denied=' + $(this).data('id'),
                    success: function(res) {
                        if (res == 'OK') {
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        } else {
                            nukeviet.toast(res, 'error');
                        }
                    }
                });
            }
            return false;
        });

        // Xóa leader
        $(document).on('click', 'a.deleteleader', function() {
            if (confirm(nv_is_del_confirm[0])) {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                    data: 'gid=' + gid + '&exclude=' + $(this).data('userid'),
                    success: function(res) {
                        if (res == 'OK') {
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        } else {
                            nukeviet.toast(res, 'error');
                        }
                    }
                });
            }
            return false;
        });

        // Giáng cấp
        $(document).on('click', 'a.demote', function() {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                data: 'gid=' + gid + '&demote=' + $(this).data('id'),
                success: function(res) {
                    if (res == 'OK') {
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    } else {
                        nukeviet.toast(res, 'error');
                    }
                }
            });
            return false;
        });

        // Xóa member
        $(document).on('click', 'a.deletemember', function() {
            if (confirm(nv_is_del_confirm[0])) {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                    data: 'gid=' + gid + '&exclude=' + $(this).data('userid'),
                    success: function(res) {
                        if (res == 'OK') {
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        } else {
                            nukeviet.toast(res, 'error');
                        }
                    }
                });
            }
            return false;
        });

        // Thăng cấp
        $(document).on('click', 'a.promote', function() {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups',
                data: 'gid=' + gid + '&promote=' + $(this).data('id'),
                success: function(res) {
                    if (res == 'OK') {
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    } else {
                        nukeviet.toast(res, 'error');
                    }
                }
            });
            return false;
        });
    }
});
