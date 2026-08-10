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
window.nv_load_sqlchoice = function(choice_name_select, choice_seltected, callback) {
    let getval = "";
    if (choice_name_select == "table") {
        // Load danh sách các bảng khi chọn module
        let choicesql_module = $("select[name=choicesql_module]").val();
        let module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        getval = "&module=" + module_selected;
        $("#choicesql_column").html("");
    } else if (choice_name_select == "column") {
        // Load danh sách các cột khi chọn bảng
        let choicesql_module = $("select[name=choicesql_module]").val();
        let module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '' : choicesql_module;
        let choicesql_table = $("select[name=choicesql_table]").val();
        let table_selected = (choicesql_table == "" || choicesql_table == undefined) ? '' : choicesql_table;
        getval = "&module=" + module_selected + "&table=" + table_selected;
    }
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected, function(res) {
        $('#choicesql_' + choice_name_select).html(res);

        // Gắn sự kiện change cho select mới tạo
        const selectElement = $('#choicesql_' + choice_name_select + ' select');
        selectElement.on('change', function() {
            let next = $(this).data('next');
            if (next) {
                window.nv_load_sqlchoice(next, '');
            }
        });

        if (typeof callback === 'function') {
            callback();
        } else {
            selectElement.trigger('change');
        }
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

    // Nút chọn ngày tháng
    $('[data-toggle="focusDate"]').on('click', function(e) {
        e.preventDefault();
        $('input', $(this).parent()).focus();
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
                data: 'changeweight=1&fid=' + fid + '&new_vid=' + new_vid + '&checkss=' + ($('.table-card').data('checkss') || $('[name="checkss"]').val()),
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
                    data: 'del=1&fid=' + fid + '&checkss=' + ($('.table-card').data('checkss') || $('[name="checkss"]').val()),
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

            window.nv_load_sqlchoice('module', moduleVal, () => {
                window.nv_load_sqlchoice('table', tableVal, () => {
                    window.nv_load_sqlchoice('column', keyVal + '|' + valVal + '|' + orderVal + '|' + sortVal);
                });
            });
        }

        // Initialize field choice items count
        let existingChoices = $('[data-field-choice]');
        if (existingChoices.length > 0) {
            fieldChoiceItems = existingChoices.length;
        }

        // Datepicker initialization
        if ($('.datepicker').length > 0) {
            $('.datepicker').attr('autocomplete', 'off').datepicker({
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
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
                $('select[name="choicetypes"]').trigger('change');
            }
        });

        // Choice type change event
        $('select[name="choicetypes"]').on('change', function() {
            let choicetype = $(this).val();
            $('#choiceitems, #choicesql').addClass('d-none');
            if (choicetype == 'field_choicetypes_sql') {
                $('#choicesql').removeClass('d-none');

                // Load danh sách module cho SQL choice nếu chưa có
                if ($('#choicesql_module select').length == 0) {
                    window.nv_load_sqlchoice('module', '');
                }
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

    // Trang quản lý nhóm thành viên
    if (nv_func_name == 'groups') {
        // Parse URL params once
        const urlParams = new URLSearchParams(window.location.search);
        const gid = urlParams.get('userlist');

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
                const id = urlParams.get('id') || 0;
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(), 'getAlias=1&id=' + id + '&title=' + encodeURIComponent(title), function(res) {
                    $('#groupForm [name=alias]').val(res);
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                });
            }
        }

        /**
         * Quản lý các Popover cho chức năng thay đổi thứ tự
         */
        let popOverALl = [];

        function destroyAllPop() {
            popOverALl.forEach(function(pop) {
                $(pop._element).data('havepop', false);
                pop.dispose();
            });
            popOverALl = [];
        }

        function getPopoverContent(e) {
            const keyID = '#tmpgroup_' + $(e).data('mod');
            let tmpgroup = $(keyID);
            if (tmpgroup.length && tmpgroup.data('num') != $(e).data('num')) {
                tmpgroup.remove();
                tmpgroup = $(keyID);
            }
            if (!tmpgroup.length) {
                $('body').append('<ul id="tmpgroup_' + $(e).data('mod') + '" class="d-none" data-num="' + $(e).data('num') + '"></ul>');
                tmpgroup = $(keyID);
                for (let i = $(e).data('min'); i <= $(e).data('num'); i++) {
                    tmpgroup.append('<li><a href="#" data-value="' + i + '">' + i + '</a></li>');
                }
            }
            return '<div class="dropdown-tool-ctn"><ul class="dropdown-tool" data-mod="' + $(e).data('mod') + '" data-id="' + $(e).data('id') + '">' + tmpgroup.html() + '</ul></div>';
        }

        // Xử lý sự kiện mở popover, active current item và cuộn tới nó
        $(document).on('shown.bs.popover', '[data-toggle="changegroupweight"]', function() {
            const ctn = $('#' + $(this).attr('aria-describedby'));
            const wrapArea = ctn.find('.dropdown-tool-ctn');
            const wrapContent = ctn.find('.dropdown-tool');
            wrapContent.find('[data-value="' + $(this).data('current') + '"]').addClass('active');
            if (wrapArea.height() < wrapContent.height()) {
                const item = wrapContent.find('li:first');
                const scrollTop = ($(this).data('current') - $(this).data('min')) * item.height();
                wrapArea.scrollTop(scrollTop);
            }
        });

        // Xử lý khi click nút thay đổi thứ tự
        $(document).on('click', '[data-toggle="changegroupweight"]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const btn = $(this);
            if (btn.data('havepop')) {
                return;
            }
            destroyAllPop();
            btn.data('havepop', true);
            btn.attr('data-bs-toggle', 'popover');
            btn.attr('data-bs-trigger', 'manual');
            btn.attr('data-bs-content', '');

            const popover = new bootstrap.Popover(btn[0], {
                content: getPopoverContent(this),
                html: true,
                sanitize: false,
                placement: 'bottom'
            });
            popover.show();
            popOverALl.push(popover);
        });

        // Xử lý khi click vào item trong popover để thay đổi thứ tự
        $(document).on('click', '.dropdown-tool a', function(e) {
            e.preventDefault();
            destroyAllPop();
            const $this = $(this);
            const ctn = $this.parent().parent();
            const btn = $('#group_' + ctn.data('mod') + '_' + ctn.data('id'));
            btn.find('span.text').html('<i class="fa-solid fa-spinner fa-spin"></i>' + $this.html());
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                data: {
                    id: ctn.data('id'),
                    cWeight: $this.data('value'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                cache: false,
                success: function (res) {
                    if (res.status === 'success') {
                        location.reload();
                        return;
                    }
                    btn.find('span.text').html(btn.data('current'));
                    btn.prop('disabled', false);
                    nukeviet.toast(res.mess || 'Error response', 'error');
                },
                error: function (xhr, text, err) {
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                    btn.find('span.text').html(btn.data('current'));
                    btn.prop('disabled', false);
                }
            });
        });

        // Tắt hết popover khi click ra ngoài
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.popover').length) {
                destroyAllPop();
            }
        });

        // Pick ngày tháng ô ngày hết hạn nhóm
        if ($('[name="exp_time"]').length) {
            $('[name="exp_time"]').attr('autocomplete', 'off').datepicker({
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

        // Pick màu nhóm
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

            nukeviet.confirm(nv_is_del_confirm[0], () => {
                icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                    data: {
                        del: btn.data('id'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                        if (res.status == 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text, err) {
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    }
                });
            });
        });

        // Xử lý thay đổi trạng thái kích hoạt
        $(document).on('change', 'input.actGroup', function() {
            const btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                data: {
                    act: btn.data('id'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                cache: false,
                success: function (res) {
                    btn.prop('disabled', false);
                    if (res.status === 'success') {
                        btn.prop('checked', res.new_status);
                        return;
                    }
                    btn.prop('checked', btn.is(':checked') ? false : true);
                    nukeviet.toast(res.mess || 'Error response', 'error');
                },
                error: function (xhr, text, err) {
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                    btn.prop('checked', btn.is(':checked') ? false : true);
                    btn.prop('disabled', false);
                }
            });
        });

        // Xử lý xóa các nhóm không kích hoạt
        $(document).on('click', '[data-toggle="delInactiveGroup"]', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            nukeviet.confirm(btn.data('msgconfirm'), () => {
                icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                    data: {
                        deleteinactive: 1,
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                        nukeviet.toast(res.mess, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Quản lý thành viên - userlist
        if (gid && $('#pageContent').length && typeof nv_randomPassword !== 'undefined') {
            $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));

            // Tìm kiếm người dùng
            $(document).on('click', '[name=searchUser]', function() {
                const filtersql = $('#filtersql_val').val() || '';
                nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getuserid&area=uid&filtersql=' + filtersql, 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
                return false;
            });

            // Thêm người dùng vào nhóm
            $(document).on('click', '[name=addUser]', function(e) {
                e.preventDefault();
                const btn = $(this);
                let uid = $('#ablist input[name=uid]').val();
                uid = intval(uid);
                if (uid == 0) {
                    uid = '';
                }
                $('#ablist input[name=uid]').val(uid);
                if (uid == '') {
                    $('#ablist input[name=uid]').focus();
                    return false;
                }

                $('#pageContent input, #pageContent select').attr('disabled', 'disabled');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                    data: {
                        gid: gid,
                        uid: uid,
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        $('#pageContent input, #pageContent select').prop('disabled', false);
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }

                        $('#ablist input[name=uid]').val('');
                        $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    },
                    error: function (xhr, text, err) {
                        $('#pageContent input, #pageContent select').prop('disabled', false);
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });

            // Duyệt thành viên
            $(document).on('click', 'button.approved', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }

                nukeviet.confirm(nv_is_add_user_confirm[0], () => {
                    icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                    $.ajax({
                        type: 'POST',
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                        data: {
                            gid: gid,
                            approved: btn.data('id'),
                            checkss: btn.data('checkss')
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (res) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            if (res.status === 'error') {
                                return nukeviet.toast(res.mess, 'error');
                            }

                            $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        },
                        error: function (xhr, text, err) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nukeviet.toast(text, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                });
            });

            // Từ chối thành viên
            $(document).on('click', 'button.denied', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }

                nukeviet.confirm(nv_is_exclude_user_confirm[0], () => {
                    icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                    $.ajax({
                        type: 'POST',
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                        data: {
                            gid: gid,
                            denied: btn.data('id'),
                            checkss: btn.data('checkss')
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (res) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            if (res.status === 'error') {
                                return nukeviet.toast(res.mess, 'error');
                            }

                            $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        },
                        error: function (xhr, text, err) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nukeviet.toast(text, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                });
            });

            // Xóa leader
            $(document).on('click', 'button.deleteleader', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }

                nukeviet.confirm(nv_is_exclude_user_confirm[0], () => {
                    icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                    $.ajax({
                        type: 'POST',
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                        data: {
                            gid: gid,
                            exclude: btn.data('id'),
                            checkss: btn.data('checkss')
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (res) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            if (res.status === 'error') {
                                return nukeviet.toast(res.mess, 'error');
                            }

                            $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        },
                        error: function (xhr, text, err) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nukeviet.toast(text, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                });
            });

            // Giáng cấp
            $(document).on('click', 'button.demote', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }

                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                    data: {
                        gid: gid,
                        demote: btn.data('id'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }

                        $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });

            // Xóa member
            $(document).on('click', 'button.deletemember', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }

                nukeviet.confirm(nv_is_exclude_user_confirm[0], () => {
                    icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

                    $.ajax({
                        type: 'POST',
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                        data: {
                            gid: gid,
                            exclude: btn.data('id'),
                            checkss: btn.data('checkss')
                        },
                        dataType: 'json',
                        cache: false,
                        success: function (res) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            if (res.status === 'error') {
                                return nukeviet.toast(res.mess, 'error');
                            }

                            $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                            $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                        },
                        error: function (xhr, text, err) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nukeviet.toast(text, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                });
            });

            // Thăng cấp
            $(document).on('click', 'button.promote', function(e) {
                e.preventDefault();
                const btn = $(this);
                const icon = $('i', btn);
                if (icon.is('.fa-spinner')) {
                    return;
                }
                icon.removeClass(btn.data('icon')).addClass('fa-spinner fa-spin-pulse');

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&nocache=' + new Date().getTime(),
                    data: {
                        gid: gid,
                        promote: btn.data('id'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(btn.data('icon'));
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }

                        $('div#pageContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + nv_loadingText + '</span></div></div>');
                        $('div#pageContent').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&listUsers=' + gid + '&random=' + nv_randomPassword(10));
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(btn.data('icon'));
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        }
    }

    // Trang Tài khoản đợi kích hoạt
    if (nv_func_name === 'user_waiting') {
        // Ẩn/hiện mật khẩu
        $(document).on('click', '.btn-eye', function (e) {
            e.preventDefault();
            const fieldId = $(this).data('field');
            const field = $(fieldId);
            const icon = $('i', this);
            if (field.attr('type') === 'password') {
                field.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                field.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Xóa tài khoản chờ kích hoạt
        $(document).on('click', '.btn-del-waiting', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            nukeviet.confirm(nv_is_del_confirm[0], () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_waiting&nocache=' + new Date().getTime(),
                    data: {
                        del: 1,
                        userid: btn.data('userid'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Toggle danh sách nhóm khi thay đổi trạng thái is_official
        $('[name="is_official"]').on('change', function () {
            const ctngroups = $('#ctn-list-groups');
            if (!ctngroups.length) {
                return;
            }
            if ($(this).is(':checked')) {
                ctngroups.removeClass('d-none');
            } else {
                ctngroups.addClass('d-none');
                $('[name="group[]"]').prop('checked', false);
                $('[name="group_default"]').prop('checked', false);
            }
        });

        // Xóa nhóm mặc định
        $(document).on('click', '[data-toggle="cleargdefault"]', function (e) {
            e.preventDefault();
            $('[name="group_default"]').prop('checked', false);
        });

        // Chọn câu hỏi bảo mật từ dropdown
        $(document).on('click', 'a.question', function (e) {
            e.preventDefault();
            $('[name="question"]').val($(this).text()).trigger('change');
        });

        // Khởi tạo datepicker cho các trường ngày tháng
        if ($('.datepicker').length > 0) {
            $('.datepicker').attr('autocomplete', 'off').datepicker({
                showOn: 'focus',
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c',
                showOtherMonths: true,
                beforeShow: function () {
                    setTimeout(function () {
                        $('.ui-datepicker').css('z-index', 999999999);
                    }, 0);
                }
            });
        }

        // Tạo mật khẩu ngẫu nhiên
        $(document).on('click', '[data-toggle="genpass"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            const field1 = $(btn.data('field1'));
            const field2 = $(btn.data('field2'));

            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_add&nocache=' + new Date().getTime(),
                data: {
                    nv_genpass: 1,
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                cache: false,
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (res.status === 'error') {
                        return nukeviet.toast(res.mess, 'error');
                    }
                    field1.val(res.value).trigger('change');
                    if (field2.length) {
                        field2.val(res.value).trigger('change');
                    }
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Xử lý nút "Thêm file" trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="addfilebtn"]', function () {
            const btn = $(this);
            const filelist = btn.parents('.filelist');
            let filenum = $('[name^=custom_fields]', filelist).length;
            const maxnum = parseInt(filelist.data('maxnum')) || 0;
            const modalObj = $('#' + btn.data('modal'));
            const fileAccept = modalObj.data('accept') || '';
            const maxsize = parseInt(modalObj.data('maxsize')) || 0;

            const setAddFileBtn = function (num) {
                if (maxnum && num >= maxnum) {
                    btn.hide();
                } else {
                    btn.show();
                }
            };

            const updateFileInput = function () {
                const input = $('<input type="file"/>');
                if (fileAccept !== '') {
                    input.attr('accept', fileAccept);
                }
                input.on('change', function () {
                    const sFileName = $(this).val();
                    if (sFileName.length > 0) {
                        // Kiểm tra phần mở rộng
                        if (fileAccept !== '') {
                            const fileAcceptArr = fileAccept.split(',');
                            let blnValid = false;
                            for (let j = 0; j < fileAcceptArr.length; j++) {
                                const sCurExtension = fileAcceptArr[j];
                                if (sFileName.toLowerCase().endsWith(sCurExtension.toLowerCase())) {
                                    blnValid = true;
                                    break;
                                }
                            }
                            if (!blnValid) {
                                updateFileInput();
                                nukeviet.toast(modalObj.data('ext-error') + ' ' + fileAcceptArr.join(', '), 'error');
                                return;
                            }
                        }
                        // Kiểm tra dung lượng
                        if (typeof this.files !== 'undefined' && this.files.length > 0 && this.files[0].size > maxsize) {
                            const maxsizeKB = parseFloat(maxsize / 1024).toFixed(2);
                            const sizeKB = parseFloat(this.files[0].size / 1024).toFixed(2);
                            updateFileInput();
                            nukeviet.toast(modalObj.data('size-error') + ' (' + sizeKB + ' KB) ' + modalObj.data('size-error2') + ' (' + maxsizeKB + ' KB)', 'error');
                            return;
                        }
                        // Upload file
                        if (typeof this.files !== 'undefined' && this.files.length > 0) {
                            const data = new FormData();
                            data.append('file', this.files[0]);
                            data.append('field', modalObj.data('field'));
                            data.append('_csrf', modalObj.data('csrf'));
                            data.append('field_fileupload', 1);
                            $.ajax({
                                type: 'POST',
                                url: modalObj.data('url'),
                                enctype: 'multipart/form-data',
                                data: data,
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function (a) {
                                if (a.status === 'error') {
                                    updateFileInput();
                                    return nukeviet.toast(a.mess, 'error');
                                }
                                if (a.status === 'success' || a.status === 'OK') {
                                    const newfile = $('<li class="d-flex align-items-center gap-1 mb-1"></li>');
                                    newfile.append('<input type="checkbox" class="form-check-input ' + filelist.data('oclass') + '" name="custom_fields[' + filelist.data('field') + '][]" value="' + a.file_key + '" checked>');
                                    newfile.append('<button type="button" class="btn btn-success btn-sm btn-file type-' + (a.file_type || 'other') + '" data-url="' + a.file_url + '">' + a.file_value + '</button>');
                                    newfile.append('<button type="button" class="btn btn-link btn-sm" data-toggle="thisfile_del">' + modalObj.data('delete') + '</button>');
                                    $('.items', filelist).append(newfile);
                                    modalObj.modal('hide');
                                    filenum++;
                                    setAddFileBtn(filenum);
                                }
                            });
                        }
                    }
                });
                $('.fileinput', modalObj).html(input);
            };

            updateFileInput();
            modalObj.modal('show');
        });

        // Xóa file đã chọn trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="thisfile_del"]', function () {
            const filelist = $(this).parents('.filelist');
            $(this).parents('li').remove();
            const addBtn = $('[data-toggle="addfilebtn"]', filelist);
            if (addBtn.length) {
                const maxnum = parseInt(filelist.data('maxnum')) || 0;
                if (maxnum && $('[name^=custom_fields]', filelist).length >= maxnum) {
                    addBtn.hide();
                } else {
                    addBtn.show();
                }
            }
        });

        // Xem file đã tải lên
        $(document).on('click', '.btn-file', function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            if ($(this).is('.type-image, .type-pdf')) {
                window.open(url, 'NVFile', 'width=650,height=430,resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
            } else {
                window.location.href = url;
            }
        });
    }

    // Trang Gửi lại email kích hoạt
    if (nv_func_name === 'user_waiting_remail') {
        let resendOffset = 0;
        let emailOffset = 0;
        let emailDelete = '';
        let runInterval;
        let per_email, pause_time;

        // Lấy chuỗi ngôn ngữ đã nhúng trong tpl qua data attribute
        const langResendRun = $('#resend-perload').data('lang-run') || '';
        const langResendNote = $('#resend-perload').data('lang-note') || '';
        const langResendCounter = $('#resend-perload').data('lang-counter') || '';
        const langResendStart = $('#resend-result').data('lang-start') || '';
        const langResendEnd = $('#resend-result').data('lang-end') || '';
        const langResendComplete = $('#resend-perload').data('lang-complete') || '';

        // Lấy giờ hiện tại dạng hh:mm:ss
        function getDisplayTime() {
            const time = new Date();
            let hh = time.getHours();
            let mm = time.getMinutes();
            let ss = time.getSeconds();
            if (hh < 10) hh = '0' + hh;
            if (mm < 10) mm = '0' + mm;
            if (ss < 10) ss = '0' + ss;
            return hh + ':' + mm + ':' + ss;
        }

        // Hàm gửi email theo batch
        function resendEmailRun() {
            resendOffset--;
            if (resendOffset <= 0) {
                // Hiển thị trạng thái đang gửi
                $('#resend-perload').html(langResendRun + '. ' + langResendNote);

                if (runInterval) {
                    clearInterval(runInterval);
                }

                const checkss = $('#resend_checkss').val();

                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_waiting_remail&nocache=' + new Date().getTime(),
                    data: {
                        ajax: 1,
                        per_email: per_email,
                        offset: emailOffset,
                        useriddel: emailDelete,
                        checkss: checkss
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        if (data.messages && data.messages.length > 0) {
                            $('#resend-result').prepend(data.messages.join('<br />') + '<br />');
                        }
                        if (!data.continue) {
                            // Hoàn tất tất cả
                            $('#resend-result').prepend(langResendEnd + ' ' + getDisplayTime() + '<br />');
                            $('#resend-perload').html(langResendComplete);

                            const form = $('#resend-email-form');
                            form.data('busy', false);
                            $('.load', form).addClass('d-none');
                            $('select', form).prop('disabled', false);
                            return;
                        }
                        emailDelete = data.useriddel || '';
                        resendOffset = pause_time;
                        emailOffset += per_email;

                        // Đếm ngược đến lần gửi tiếp theo
                        runInterval = setInterval(function () {
                            resendEmailRun();
                        }, 1000);
                    },
                    error: function (xhr, text) {
                        $('#resend-result').prepend('Error Request: ' + text + '<br />');
                    }
                });
                return;
            }

            // Hiển thị đếm ngược
            $('#resend-perload').html(langResendCounter + ' <strong>' + resendOffset + '</strong>s. ' + langResendNote);
        }

        // Xử lý khi submit form gửi lại email
        $('#resend-email-form').on('submit', function (e) {
            const $this = $(this);
            e.preventDefault();
            if ($this.data('busy')) {
                return;
            }

            per_email = parseInt($('[name="per_email"]', $this).val());
            pause_time = parseInt($('[name="pause_time"]', $this).val());

            $this.data('busy', true);
            $('.load', $this).removeClass('d-none');
            $('select', $this).prop('disabled', true);

            $('#resend-perload').removeClass('d-none');
            $('#resend-result').removeClass('d-none');

            $('#resend-result').html(langResendStart + ' ' + getDisplayTime() + '<br />');

            resendOffset = 0;
            emailOffset = 0;
            emailDelete = '';
            resendEmailRun();
        });
    }

    // Trang Thêm thành viên mới
    if (nv_func_name === 'user_add' || nv_func_name === 'edit') {
        // Ẩn/hiện mật khẩu
        $(document).on('click', '.btn-eye', function (e) {
            e.preventDefault();
            const fieldId = $(this).data('field');
            const field = $(fieldId);
            const icon = $('i', this);
            if (field.attr('type') === 'password') {
                field.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                field.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Toggle danh sách nhóm khi thay đổi trạng thái is_official
        $('[name="is_official"]').on('change', function () {
            const ctngroups = $('#ctn-list-groups');
            if (!ctngroups.length) {
                return;
            }
            if ($(this).is(':checked')) {
                ctngroups.removeClass('d-none');
            } else {
                ctngroups.addClass('d-none');
                $('[name="group[]"]').prop('checked', false);
                $('[name="group_default"]').prop('checked', false);
            }
        });

        // Xóa nhóm mặc định
        $(document).on('click', '[data-toggle="cleargdefault"]', function (e) {
            e.preventDefault();
            $('[name="group_default"]').prop('checked', false);
        });

        // Chọn câu hỏi bảo mật từ dropdown
        $(document).on('click', 'a.question', function (e) {
            e.preventDefault();
            $('[name="question"]').val($(this).text()).trigger('change');
        });

        // Khởi tạo datepicker cho các trường ngày tháng
        if ($('.datepicker').length > 0) {
            $('.datepicker').attr('autocomplete', 'off').datepicker({
                showOn: 'focus',
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c',
                showOtherMonths: true,
                beforeShow: function () {
                    setTimeout(function () {
                        $('.ui-datepicker').css('z-index', 999999999);
                    }, 0);
                }
            });
        }

        // Tạo mật khẩu ngẫu nhiên
        $(document).on('click', '[data-toggle="genpass"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            const field1 = $(btn.data('field1'));
            const field2 = $(btn.data('field2'));

            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_add&nocache=' + new Date().getTime(),
                data: {
                    nv_genpass: 1,
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                cache: false,
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (res.status === 'error') {
                        return nukeviet.toast(res.mess, 'error');
                    }
                    field1.val(res.value).trigger('change');
                    if (field2.length) {
                        field2.val(res.value).trigger('change');
                    }
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Xử lý nút "Thêm file" trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="addfilebtn"]', function () {
            const btn = $(this);
            const filelist = btn.parents('.filelist');
            let filenum = $('[name^=custom_fields]', filelist).length;
            const maxnum = parseInt(filelist.data('maxnum')) || 0;
            const modalObj = $('#' + btn.data('modal'));
            const fileAccept = modalObj.data('accept') || '';
            const maxsize = parseInt(modalObj.data('maxsize')) || 0;

            const setAddFileBtn = function (num) {
                if (maxnum && num >= maxnum) {
                    btn.hide();
                } else {
                    btn.show();
                }
            };

            const updateFileInput = function () {
                const input = $('<input type="file"/>');
                if (fileAccept !== '') {
                    input.attr('accept', fileAccept);
                }
                input.on('change', function () {
                    const sFileName = $(this).val();
                    if (sFileName.length > 0) {
                        if (fileAccept !== '') {
                            const fileAcceptArr = fileAccept.split(',');
                            let blnValid = false;
                            for (let j = 0; j < fileAcceptArr.length; j++) {
                                if (sFileName.toLowerCase().endsWith(fileAcceptArr[j].toLowerCase())) {
                                    blnValid = true;
                                    break;
                                }
                            }
                            if (!blnValid) {
                                updateFileInput();
                                nukeviet.toast(modalObj.data('ext-error') + ' ' + fileAcceptArr.join(', '), 'error');
                                return;
                            }
                        }
                        if (typeof this.files !== 'undefined' && this.files.length > 0 && this.files[0].size > maxsize) {
                            const maxsizeKB = parseFloat(maxsize / 1024).toFixed(2);
                            const sizeKB = parseFloat(this.files[0].size / 1024).toFixed(2);
                            updateFileInput();
                            nukeviet.toast(modalObj.data('size-error') + ' (' + sizeKB + ' KB) ' + modalObj.data('size-error2') + ' (' + maxsizeKB + ' KB)', 'error');
                            return;
                        }
                        if (typeof this.files !== 'undefined' && this.files.length > 0) {
                            const data = new FormData();
                            data.append('file', this.files[0]);
                            data.append('field', modalObj.data('field'));
                            data.append('_csrf', modalObj.data('csrf'));
                            data.append('field_fileupload', 1);
                            $.ajax({
                                type: 'POST',
                                url: modalObj.data('url'),
                                enctype: 'multipart/form-data',
                                data: data,
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function (a) {
                                if (a.status === 'error') {
                                    updateFileInput();
                                    return nukeviet.toast(a.mess, 'error');
                                }
                                if (a.status === 'success' || a.status === 'OK') {
                                    const newfile = $('<li class="d-flex align-items-center gap-1 mb-1"></li>');
                                    newfile.append('<input type="checkbox" class="form-check-input ' + filelist.data('oclass') + '" name="custom_fields[' + filelist.data('field') + '][]" value="' + a.file_key + '" checked>');
                                    newfile.append('<button type="button" class="btn btn-success btn-sm btn-file type-' + (a.file_type || 'other') + '" data-url="' + a.file_url + '">' + a.file_value + '</button>');
                                    newfile.append('<button type="button" class="btn btn-link btn-sm" data-toggle="thisfile_del">' + modalObj.data('delete') + '</button>');
                                    $('.items', filelist).append(newfile);
                                    modalObj.modal('hide');
                                    filenum++;
                                    setAddFileBtn(filenum);
                                }
                            });
                        }
                    }
                });
                $('.fileinput', modalObj).html(input);
            };

            updateFileInput();
            modalObj.modal('show');
        });

        // Xóa file đã chọn trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="thisfile_del"]', function () {
            const filelist = $(this).parents('.filelist');
            $(this).parents('li').remove();
            const addBtn = $('[data-toggle="addfilebtn"]', filelist);
            if (addBtn.length) {
                const maxnum = parseInt(filelist.data('maxnum')) || 0;
                if (maxnum && $('[name^=custom_fields]', filelist).length >= maxnum) {
                    addBtn.hide();
                } else {
                    addBtn.show();
                }
            }
        });

        // Xem file đã tải lên
        $(document).on('click', '.btn-file', function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            if ($(this).is('.type-image, .type-pdf')) {
                window.open(url, 'NVFile', 'width=650,height=430,resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
            } else {
                window.location.href = url;
            }
        });

        // Xóa ảnh đại diện hiện tại
        $(document).on('click', '[data-toggle="deletephoto"]', function (e) {
            e.preventDefault();
            $('[name="delpic"]').val(1);
            $('#current-photo').addClass('d-none');
            $('#change-photo').removeClass('d-none');
        });
    }

    // Trang Quản lý tài khoản OAuth của thành viên
    if (nv_func_name === 'edit_oauth') {
        const oauthCard = $('[data-checkss]');

        // Xóa một kết nối OAuth
        $(document).on('click', '[data-toggle="delete-one-oauth"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    data: {
                        del: 1,
                        userid: oauthCard.data('userid'),
                        opid: btn.data('opid'),
                                                checkss: oauthCard.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });

        // Xóa tất cả kết nối OAuth
        $(document).on('click', '[data-toggle="delete-all-oauth"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    data: {
                        delall: 1,
                        userid: oauthCard.data('userid'),
                        checkss: oauthCard.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });
    }

    // Trang quản lý xác thực hai bước
    if (nv_func_name === 'edit_2step') {
        // Tắt xác thực hai bước
        $(document).on('click', '[data-toggle="turnoff2step"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&userid=' + btn.data('userid') + '&nocache=' + new Date().getTime(),
                    data: {
                        turnoff2step: 1,
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });

        // Tạo lại mã dự phòng
        $(document).on('click', '[data-toggle="resetbackupcodes"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&userid=' + btn.data('userid') + '&nocache=' + new Date().getTime(),
                    data: {
                        resetbackupcodes: 1,
                        sendmail: $('[name="sendmail"]').is(':checked') ? 1 : 0,
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });
    }

    // Trang Danh sách tài khoản
    if (nv_func_name === 'main') {
        const tableCard = $('.table-responsive-lg');
        const checkss = tableCard.data('checkss');

        // Khởi tạo datepicker cho ô lọc ngày đăng ký
        if ($('.datepicker-search').length > 0) {
            $('.datepicker-search').attr('autocomplete', 'off').datepicker({
                showOn: 'focus',
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                beforeShow: function () {
                    setTimeout(function () {
                        $('.ui-datepicker').css('z-index', 999999999);
                    }, 0);
                }
            });
        }

        // Toggle icon/tooltip/aria-label khi mở rộng / thu gọn bộ lọc nâng cao
        $('#search-adv').on('show.bs.collapse hide.bs.collapse', function (e) {
            const isExpanding = e.type === 'show';
            const labelExpand = $(this).data('label-expand');
            const labelCollapse = $(this).data('label-collapse');
            const label = isExpanding ? labelCollapse : labelExpand;
            const $btn = $('[data-bs-target="#search-adv"]');
            const $span = $btn.closest('[data-bs-toggle="tooltip"]');
            const $icon = $btn.find('i');

            $icon.toggleClass('fa-expand', !isExpanding).toggleClass('fa-compress', isExpanding);
            $btn.attr('aria-label', label);
            const tooltip = bootstrap.Tooltip.getInstance($span[0]);
            if (tooltip) {
                $span.attr('data-bs-title', label);
                tooltip.setContent({ '.tooltip-inner': label });
            }
        });

        // Check all / uncheck all
        $(document).on('change', '#check_all', function () {
            const checked = $(this).is(':checked');
            $('.idcheck').prop('checked', checked);
        });

        // Từng checkbox ảnh hưởng đến check_all
        $(document).on('change', '.idcheck', function () {
            const total = $('.idcheck').length;
            const checked = $('.idcheck:checked').length;
            $('#check_all').prop('checked', total === checked).prop('indeterminate', checked > 0 && checked < total);
        });

        // Toggle trạng thái hoạt động (checkbox cá nhân)
        $(document).on('change', '[data-toggle="setactive"]', function () {
            const checkbox = $(this);
            const userid = checkbox.data('userid');
            checkbox.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setactive&nocache=' + new Date().getTime(),
                data: { userid: userid, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    checkbox.prop('disabled', false);
                    if (res.status === 'error') {
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        nvToast(res.mess || nv_is_change_act_confirm[2], 'error');
                    }
                },
                error: function (xhr, text) {
                    checkbox.prop('disabled', false);
                    checkbox.prop('checked', !checkbox.is(':checked'));
                    nvToast(text, 'error');
                }
            });
        });

        // Xóa user (nút trash cá nhân)
        $(document).on('click', '[data-toggle="row-del"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(),
                    data: { userid: btn.data('userid'), checkss: checkss },
                    dataType: 'json',
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });

        // Set official
        $(document).on('click', '[data-toggle="set-official"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setofficial&nocache=' + new Date().getTime(),
                data: { userid: btn.data('userid'), checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    if (res.status === 'error') {
                        return nvToast(res.mess, 'error');
                    }
                    location.reload();
                },
                error: function (xhr, text) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nvToast(text, 'error');
                }
            });
        });

        // Xem trang cá nhân user trong popup
        $(document).on('click', '[data-toggle="view-user"]', function (e) {
            e.preventDefault();
            const link = $(this).data('link');
            nv_open_browse(link + '/s', 'VIEWUSER', 550, 500, 'resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,status=no');
        });

        // Yêu cầu thay đổi mật khẩu — mở modal
        $(document).on('click', '[data-toggle="pass-reset-request"]', function (e) {
            e.preventDefault();
            const userid = $(this).data('userid');
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { psr: 1, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    $('#pass-reset-modal .userid').val(res.userid);
                    $('#pass-reset-modal .username').text(res.username);
                    $('#pass-reset-modal .currentpass-created-time').text(res.pass_creation_time);
                    $('#pass-reset-modal .currentpass-request-status').text(res.pass_reset_request);
                    $('#pass-reset-modal .btn-pass-reset-submit').prop('disabled', false);
                    const modal = new bootstrap.Modal(document.getElementById('pass-reset-modal'));
                    modal.show();
                }
            });
        });

        // Gửi yêu cầu thay đổi mật khẩu
        $(document).on('click', '.btn-pass-reset-submit', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            const userid = $('#pass-reset-modal .userid').val();
            const type = btn.data('type');
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $('#pass-reset-modal .btn-pass-reset-submit').prop('disabled', true);
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { psr: 1, type: type, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    $('#pass-reset-modal .btn-pass-reset-submit').prop('disabled', false);
                    nvToast(res.mess || '', res.status === 'OK' ? 'success' : 'error');
                    bootstrap.Modal.getInstance(document.getElementById('pass-reset-modal')).hide();
                },
                error: function (xhr, text) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    $('#pass-reset-modal .btn-pass-reset-submit').prop('disabled', false);
                    nvToast(text, 'error');
                }
            });
        });

        // Yêu cầu thay đổi email — mở modal
        $(document).on('click', '[data-toggle="email-reset-request"]', function (e) {
            e.preventDefault();
            const userid = $(this).data('userid');
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { esr: 1, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    $('#email-reset-modal .userid').val(res.userid);
                    $('#email-reset-modal .username').text(res.username);
                    $('#email-reset-modal .currentemail-created-time').text(res.email_creation_time);
                    $('#email-reset-modal .currentemail-request-status').text(res.email_reset_request);
                    $('#email-reset-modal .btn-email-reset-submit').prop('disabled', false);
                    const modal = new bootstrap.Modal(document.getElementById('email-reset-modal'));
                    modal.show();
                }
            });
        });

        // Gửi yêu cầu thay đổi email
        $(document).on('click', '.btn-email-reset-submit', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            const userid = $('#email-reset-modal .userid').val();
            const type = btn.data('type');
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $('#email-reset-modal .btn-email-reset-submit').prop('disabled', true);
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { esr: 1, type: type, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    $('#email-reset-modal .btn-email-reset-submit').prop('disabled', false);
                    nvToast(res.mess || '', res.status === 'OK' ? 'success' : 'error');
                    bootstrap.Modal.getInstance(document.getElementById('email-reset-modal')).hide();
                },
                error: function (xhr, text) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    $('#email-reset-modal .btn-email-reset-submit').prop('disabled', false);
                    nvToast(text, 'error');
                }
            });
        });

        // Buộc đăng nhập lại
        $(document).on('click', '[data-toggle="forced-relogin"]', function (e) {
            e.preventDefault();
            const userid = $(this).data('userid');
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { forcedrelogin: 1, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    nvToast(res.mess, res.status === 'OK' ? 'success' : 'error');
                }
            });
        });

        // Hủy yêu cầu xóa tài khoản
        $(document).on('click', '[data-toggle="cancel-deletion"]', function (e) {
            e.preventDefault();
            const userid = $(this).data('userid');
            const checkss = $('.table-card').data('checkss') || $('[name="checkss"]').val();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&userid=' + userid + '&nocache=' + new Date().getTime(),
                data: { canceldeletion: 1, checkss: checkss },
                dataType: 'json',
                success: function (res) {
                    nvAlert(res.mess, function () {
                        location.reload();
                    });
                }
            });
        });

        // Thực hiện hành động hàng loạt
        $('#mainusersaction').on('click', function () {
            const btn = $(this);
            const listid = $('.idcheck:checked').map(function () {
                return $(this).val();
            }).get().join(',');

            if (!listid) {
                nvToast(btn.data('msgnocheck'), 'warning');
                return;
            }

            const action = $('#mainuseropt').val();
            btn.prop('disabled', true);

            if (action === 'del') {
                nvConfirm(nv_is_del_confirm[0], function () {
                    $.ajax({
                        type: 'POST',
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(),
                        data: { userid: listid, checkss: checkss },
                        dataType: 'json',
                        success: function (res) {
                            btn.prop('disabled', false);
                            if (res.status === 'error') {
                                return nvToast(res.mess, 'error');
                            }
                            location.reload();
                        },
                        error: function (xhr, text) {
                            btn.prop('disabled', false);
                            nvToast(text, 'error');
                        }
                    });
                }, function () {
                    btn.prop('disabled', false);
                });
            } else {
                const setactive = action === 'active' ? 1 : 0;
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setactive&nocache=' + new Date().getTime(),
                    data: { userid: listid, setactive: setactive, checkss: checkss },
                    dataType: 'json',
                    success: function (res) {
                        btn.prop('disabled', false);
                        if (res.status === 'error') {
                            return nvToast(res.mess, 'error');
                        }
                        location.reload();
                    },
                    error: function (xhr, text) {
                        btn.prop('disabled', false);
                        nvToast(text, 'error');
                    }
                });
            }
        });

        // Xuất dữ liệu
        $(document).on('click', '[data-toggle="data-export"]', function () {
            const btn = $(this);
            const icon = $('i', btn);
            const orig = icon.data('icon');
            const noteMsg = btn.data('note');
            const completeMsg = btn.data('complete');

            if (icon.is('.fa-spinner')) {
                return;
            }

            nvToast(noteMsg, 'warning');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            btn.prop('disabled', true);

            function doExport(setExport) {
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&nocache=' + new Date().getTime(),
                    data: {
                        step: 1,
                        set_export: setExport,
                        method: $('select[name=method]').val(),
                        value: $('input[name=value]').val(),
                        usactive: $('select[name=usactive]').val()
                    },
                    success: function (response) {
                        if (response === 'OK_GETFILE') {
                            doExport(0);
                        } else if (response === 'OK_COMPLETE') {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                            btn.prop('disabled', false);
                            nvToast(completeMsg, 'success');
                            setTimeout(function () {
                                window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                            }, 2000);
                        } else {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                            btn.prop('disabled', false);
                            nvToast(response, 'error');
                        }
                    },
                    error: function (xhr, text) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        btn.prop('disabled', false);
                        nvToast(text, 'error');
                    }
                });
            }

            doExport(1);
        });
    }

    // Trang Kiểm duyệt thông tin thành viên
    if (nv_func_name === 'editcensor') {
        // Duyệt thông tin từ danh sách
        $(document).on('click', '[data-toggle="approve-censor"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nukeviet.confirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=editcensor&nocache=' + new Date().getTime(),
                    data: {
                        approved: 1,
                        userid: btn.data('userid'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }
                        nukeviet.toast(res.mess, 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Từ chối / xóa thông tin chỉnh sửa
        $(document).on('click', '[data-toggle="deny-censor"]', function (e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nukeviet.confirm(btn.data('msgconfirm'), function () {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=editcensor&nocache=' + new Date().getTime(),
                    data: {
                        del: 1,
                        userid: btn.data('userid'),
                        checkss: btn.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (res.status === 'error') {
                            return nukeviet.toast(res.mess, 'error');
                        }
                        nukeviet.toast(res.mess, 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nukeviet.toast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Khởi tạo datepicker cho các trường ngày tháng
        if ($('.datepicker').length > 0) {
            $('.datepicker').attr('autocomplete', 'off').datepicker({
                showOn: 'focus',
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c',
                showOtherMonths: true,
                beforeShow: function () {
                    setTimeout(function () {
                        $('.ui-datepicker').css('z-index', 999999999);
                    }, 0);
                }
            });
        }

        // Xử lý nút "Thêm file" trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="addfilebtn"]', function () {
            const btn = $(this);
            const filelist = btn.parents('.filelist');
            let filenum = $('[name^=custom_fields]', filelist).length;
            const maxnum = parseInt(filelist.data('maxnum')) || 0;
            const modalObj = $('#' + btn.data('modal'));
            const fileAccept = modalObj.data('accept') || '';
            const maxsize = parseInt(modalObj.data('maxsize')) || 0;

            const setAddFileBtn = function (num) {
                if (maxnum && num >= maxnum) {
                    btn.hide();
                } else {
                    btn.show();
                }
            };

            const updateFileInput = function () {
                const input = $('<input type="file"/>');
                if (fileAccept !== '') {
                    input.attr('accept', fileAccept);
                }
                input.on('change', function () {
                    const sFileName = $(this).val();
                    if (sFileName.length > 0) {
                        if (fileAccept !== '') {
                            const fileAcceptArr = fileAccept.split(',');
                            let blnValid = false;
                            for (let j = 0; j < fileAcceptArr.length; j++) {
                                if (sFileName.toLowerCase().endsWith(fileAcceptArr[j].toLowerCase())) {
                                    blnValid = true;
                                    break;
                                }
                            }
                            if (!blnValid) {
                                updateFileInput();
                                nukeviet.toast(modalObj.data('ext-error') + ' ' + fileAcceptArr.join(', '), 'error');
                                return;
                            }
                        }
                        if (typeof this.files !== 'undefined' && this.files.length > 0 && this.files[0].size > maxsize) {
                            const maxsizeKB = parseFloat(maxsize / 1024).toFixed(2);
                            const sizeKB = parseFloat(this.files[0].size / 1024).toFixed(2);
                            updateFileInput();
                            nukeviet.toast(modalObj.data('size-error') + ' (' + sizeKB + ' KB) ' + modalObj.data('size-error2') + ' (' + maxsizeKB + ' KB)', 'error');
                            return;
                        }
                        if (typeof this.files !== 'undefined' && this.files.length > 0) {
                            const data = new FormData();
                            data.append('file', this.files[0]);
                            data.append('field', modalObj.data('field'));
                            data.append('_csrf', modalObj.data('csrf'));
                            data.append('field_fileupload', 1);
                            $.ajax({
                                type: 'POST',
                                url: modalObj.data('url'),
                                enctype: 'multipart/form-data',
                                data: data,
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function (a) {
                                if (a.status === 'error') {
                                    updateFileInput();
                                    return nukeviet.toast(a.mess, 'error');
                                }
                                if (a.status === 'success' || a.status === 'OK') {
                                    const newfile = $('<li class="d-flex align-items-center gap-1 mb-1"></li>');
                                    newfile.append('<input type="checkbox" class="form-check-input ' + filelist.data('oclass') + '" name="custom_fields[' + filelist.data('field') + '][]" value="' + a.file_key + '" checked>');
                                    newfile.append('<button type="button" class="btn btn-success btn-sm btn-file type-' + (a.file_type || 'other') + '" data-url="' + a.file_url + '">' + a.file_value + '</button>');
                                    newfile.append('<button type="button" class="btn btn-link btn-sm" data-toggle="thisfile_del">' + modalObj.data('delete') + '</button>');
                                    $('.items', filelist).append(newfile);
                                    modalObj.modal('hide');
                                    filenum++;
                                    setAddFileBtn(filenum);
                                }
                            });
                        }
                    }
                });
                $('.fileinput', modalObj).html(input);
            };

            updateFileInput();
            modalObj.modal('show');
        });

        // Xóa file đã chọn trong trường tùy biến kiểu file
        $(document).on('click', '[data-toggle="thisfile_del"]', function () {
            const filelist = $(this).parents('.filelist');
            $(this).parents('li').remove();
            const addBtn = $('[data-toggle="addfilebtn"]', filelist);
            if (addBtn.length) {
                const maxnum = parseInt(filelist.data('maxnum')) || 0;
                if (maxnum && $('[name^=custom_fields]', filelist).length >= maxnum) {
                    addBtn.hide();
                } else {
                    addBtn.show();
                }
            }
        });

        // Xem file đã tải lên
        $(document).on('click', '.btn-file', function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            if ($(this).is('.type-image, .type-pdf')) {
                window.open(url, 'NVFile', 'width=650,height=430,resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
            } else {
                window.location.href = url;
            }
        });
    }

    // Trang lấy ID tài khoản (popup)
    if (nv_func_name === 'getuserid') {
        // Khởi tạo datepicker cho các ô ngày tháng
        if ($('.datepicker-get').length > 0) {
            $('.datepicker-get').attr('autocomplete', 'off').datepicker({
                dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                yearRange: '-90:+0'
            });
        }

        // Toggle hiện/ẩn tùy chọn tìm kiếm nâng cao
        $('#btn_toggle_other').on('click', function() {
            $('#search_other').toggleClass('d-none');
        });

        // Chặn submit form mặc định, load kết quả vào #resultdata qua AJAX
        $('#formgetuid').on('submit', function(e) {
            e.preventDefault();
            $('#resultdata').load($(this).attr('action') + '&' + $(this).serialize());
        });

        // Intercept link sort/phân trang trong #resultdata để load lại qua AJAX
        $(document).on('click', '#resultdata a[href]:not([data-toggle])', function(e) {
            e.preventDefault();
            $('#resultdata').load($(this).attr('href'));
        });

        // Chọn user → điền vào input của opener và đóng popup
        $(document).on('click', '[data-toggle="select-user"]', function(e) {
            e.preventDefault();
            const value = $(this).data('value');
            const area = $(this).data('area');
            const element = window.opener.document.getElementById(area);
            if (element) {
                element.value = value;
                element.focus();
                element.dispatchEvent(new Event('change'));
            }
            window.close();
        });
    }
});
