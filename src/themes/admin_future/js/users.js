/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

"use strict";

// Fields module functionality
var items = 1;

// Load danh sách fields
function nv_show_list_field() {
    $.ajax({
        type: 'GET',
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&qlist=1&nocache=' + new Date().getTime(),
        success: function(data) {
            $('#module_show_list').html(data);
            initFieldsEvents();
        }
    });
}

// Khởi tạo các sự kiện cho fields list
function initFieldsEvents() {
    // Sự kiện thay đổi weight
    $('#module_show_list').on('change', 'select[id^="id_weight_"]', function() {
        var fid = $(this).data('fid');
        var new_vid = $(this).val();
        nv_chang_field(fid, new_vid);
    });

    // Sự kiện edit field
    $('#module_show_list').on('click', '[data-action="edit"]', function(e) {
        e.preventDefault();
        var fid = $(this).data('fid');
        nv_edit_field(fid);
    });

    // Sự kiện delete field
    $('#module_show_list').on('click', '[data-action="delete"]', function(e) {
        e.preventDefault();
        var fid = $(this).data('fid');
        nv_del_field(fid);
    });
}

// Thay đổi thứ tự field
function nv_chang_field(fid, new_vid) {
    if (confirm(nv_is_change)) {
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields',
            data: 'changeweight=1&fid=' + fid + '&new_vid=' + new_vid + '&checkss=' + nv_check_session,
            success: function(data) {
                if (data == 'OK') {
                    nv_show_list_field();
                } else {
                    nvToast(nv_Lang.error_save, 'error');
                }
            }
        });
    } else {
        nv_show_list_field();
    }
}

// Edit field
function nv_edit_field(fid) {
    window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&fid=' + fid;
}

// Delete field
function nv_del_field(fid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields',
            data: 'del=1&fid=' + fid + '&checkss=' + nv_check_session,
            success: function(data) {
                if (data == 'OK') {
                    nv_show_list_field();
                } else {
                    nvToast(nv_Lang.error_delete, 'error');
                }
            }
        });
    }
}

// Load SQL choice data
function nv_load_sqlchoice(choice_name_select, choice_seltected) {
    var getval = "";
    if (choice_name_select == "table") {
        var choicesql_module = $("select[name=choicesql_module]").val();
        var module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        getval = "&module=" + module_selected;
        $("#choicesql_column").html("");
    } else if (choice_name_select == "column") {
        var choicesql_module = $("select[name=choicesql_module]").val();
        var module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        var choicesql_table = $("select[name=choicesql_table]").val();
        var table_selected = (choicesql_table == "" || choicesql_table == undefined) ? '' : choicesql_table;
        getval = "&module=" + module_selected + "&table=" + table_selected;
    }
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected, function(res) {
        $('#choicesql_' + choice_name_select).html(res);
        
        // Gắn sự kiện change cho select mới tạo
        $('#choicesql_' + choice_name_select + ' select').on('change', function() {
            var next = $(this).data('next');
            if (next) {
                nv_load_sqlchoice(next, '');
            }
        });
    });
}

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
    items++;
    var html = '<tr class="text-center">';
    html += '<td>' + items + '</td>';
    html += '<td><input class="form-control" type="text" value="" name="field_choice[' + items + ']" placeholder="' + placeholder + '"></td>';
    html += '<td><input class="form-control" type="text" value="" name="field_choice_text[' + items + ']"></td>';
    html += '<td><input class="form-check-input" type="radio" name="default_value_choice" value="' + items + '"></td>';
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
    
    // Fields page initialization
    if ($("input[name=fid]").length > 0) {
        if ($("input[name=fid]").val() == 0) {
            nv_show_list_field();
        }
        nv_load_current_date();
        
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
            var field_type = $(this).val();
            
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
                var choicetype = $('select[name="choicetypes"]').val();
                if (choicetype == 'field_choicetypes_sql') {
                    $('#choicesql').removeClass('d-none');
                } else {
                    $('#choiceitems').removeClass('d-none');
                }
            }
        });
        
        // Choice type change event
        $('select[name="choicetypes"]').on('change', function() {
            var choicetype = $(this).val();
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
            var placeholder = $(this).closest('form').find('input[name^="field_choice["]').first().attr('placeholder') || '';
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

