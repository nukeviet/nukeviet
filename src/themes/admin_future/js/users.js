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
            let fid = $(this).data('fid');
            nukeviet.confirm(nv_is_del_confirm[0], () => {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(),
                    data: 'del=1&fid=' + fid + '&checkss=' + nv_check_session,
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
            var match_type = $(this).val();
            $('input[name^="match_"]').prop('disabled', true);
            $('input[name="match_' + match_type + '"]').prop('disabled', false);
        });

        // Add field choice button
        $('#add_field_choice').on('click', function() {
            var placeholder = $('[data-field-choice]').first().attr('placeholder') || '';
            nv_choice_fields_additem(placeholder);
        });

        // File type checkbox change
        $('input[name="filetype[]"]').on('change', function() {
            var filetype = $(this).val();
            var checked = $(this).is(':checked');
            $(this).closest('.filetype').find('input[type="checkbox"][data-toggle="mimecheck"]').prop('checked', checked);

            // Show/hide photo size options
            if (filetype == 'images') {
                if (checked) {
                    $('.photo_max_size').removeClass('d-none');
                } else {
                    var hasImages = false;
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
            var allChecked = true;
            var anyChecked = false;
            $(this).closest('.filetype').find('input[data-toggle="mimecheck"]').each(function() {
                if ($(this).is(':checked')) {
                    anyChecked = true;
                } else {
                    allChecked = false;
                }
            });

            var filetypeCheckbox = $(this).closest('.filetype').find('input[name="filetype[]"]');
            filetypeCheckbox.prop('checked', anyChecked);

            // Trigger filetype change event
            if (filetypeCheckbox.val() == 'images') {
                filetypeCheckbox.trigger('change');
            }
        });

        // Uncheck radio in choice items
        $('.uncheckRadio').on('click', 'input[type="radio"]', function() {
            var $radio = $(this);
            if ($radio.data('waschecked') == true) {
                $radio.prop('checked', false);
                $radio.data('waschecked', false);
            } else {
                $('.uncheckRadio input[type="radio"]').data('waschecked', false);
                $radio.data('waschecked', true);
            }
        });
    }
});
