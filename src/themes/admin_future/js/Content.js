/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

'use strict';

$(function () {
    // ============================================
    // BÀI VIẾT (CONTENT)
    // ============================================

    // Ẩn hiện schema about tuỳ thuộc vào schema type
    $('#content_schema_type').on('change', function () {
        if ($(this).val() === 'webpage') {
            $('#schema_about_container').removeClass('d-none');
        } else {
            $('#schema_about_container').addClass('d-none');
        }
    });

    // Đếm ký tự tiêu đề / mô tả
    if ($('#idtitle').length) {
        $("#titlelength").text($("#idtitle").val().length);
        $("#idtitle").on('keyup paste', function() {
            $("#titlelength").text($(this).val().length);
        });

        $("#descriptionlength").text($("#description").val().length);
        $("#description").on('keyup paste', function() {
            $("#descriptionlength").text($(this).val().length);
        });

        // Tự động lấy alias bài viết (khi rời input)
        if ($('[data-toggle="getaliaspage"]').data('auto-alias') === 1) {
            $('#idtitle').on('change', function() {
                $('[data-toggle="getaliaspage"]').trigger('click');
            });
        }
    }

    // Xử lý nút lấy alias (Content)
    $('[data-toggle="getaliaspage"]').on('click', function(e) {
        e.preventDefault();
        var title = $('#idtitle').val().replace(/(<([^>]+)>)/ig,"");
        var btn = $(this);
        var icon = $('i', btn);
        var id = btn.data('id') || 0;
        if (title !== '') {
            icon.addClass('fa-spin');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content-alias',
                data: {
                    title: title,
                    checkss: btn.data('checkss'),
                    id: id
                },
                success: function(res) {
                    icon.removeClass('fa-spin');
                    if (res.status === 'success') {
                        $('#idalias').val(res.alias);
                    } else if (res.mess) {
                        alert(res.mess);
                    }
                }
            });
        }
    });

    // Xóa Bài Viết
    $('[data-toggle="nv_del_page"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        var checkss = btn.data('checkss');
        var id = btn.data('id');
        if (confirm(nv_is_del_confirm[0])) {
            icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content-del',
                data: {
                    checkss: checkss,
                    id: id
                },
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    var r = typeof res === 'string' ? res.split('_') : [];
                    if ((r.length && r[0] === 'OK') || res.status === 'success') {
                        window.location.reload();
                    } else {
                        alert(res.mess ? res.mess : (r[1] ? r[1] : 'Error'));
                    }
                }
            });
        }
    });

    // Kích hoạt bài viết
    $('[data-toggle="changeActive"]').on('change', function() {
        var id = $(this).data('id');
        var checkss = $(this).data('checkss');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content-change-status',
            data: { id: id, checkss: checkss },
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.mess || 'Error');
                    window.location.reload();
                }
            }
        });
    });

    // Thay đổi thứ tự bài viết
    $('[data-toggle="changeWeiPage"]').on('change', function() {
        var id = $(this).data('id');
        var weight = $(this).val();
        var checkss = $(this).data('checkss');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content-change-weight',
            data: { id: id, new_weight: weight, checkss: checkss },
            success: function(res) {
                if (res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res.mess || 'Error');
                    window.location.reload();
                }
            }
        });
    });

    // ============================================
    // CHUYÊN MỤC (CATEGORY)
    // ============================================

    // Thay đổi thứ tự chuyên mục
    $('[data-toggle="changeWeiCat"]').on('change', function() {
        var catid = $(this).data('catid');
        var weight = $(this).val();
        var checkss = $(this).data('checkss');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat-change-weight',
            data: { catid: catid, new_weight: weight, checkss: checkss },
            success: function(res) {
                if (res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res.mess || 'Error');
                    window.location.reload();
                }
            }
        });
    });

    // Kích hoạt chuyên mục
    $('[data-toggle="changeCatActive"]').on('change', function() {
        var catid = $(this).data('catid');
        var checkss = $(this).data('checkss');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat-change-status',
            data: { catid: catid, checkss: checkss },
            success: function(res) {
                if (res.status !== 'success') {
                    alert(res.mess || 'Error');
                    window.location.reload();
                }
            }
        });
    });

    // Xóa chuyên mục
    $('[data-toggle="nv_del_cat"]').on('click', function(e) {
        e.preventDefault();
        var catid = $(this).data('catid');
        var checkss = $(this).data('checkss');
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat-del',
                data: {
                    catid: catid,
                    checkss: checkss
                },
                success: function(res) {
                    var r = typeof res === 'string' ? res.split('_') : [];
                    if ((r.length && r[0] === 'OK') || res.status === 'success') {
                        window.location.reload();
                    } else {
                        alert(res.mess ? res.mess : (r[1] ? r[1] : 'Error'));
                    }
                }
            });
        }
    });

    // Sinh alias chuyên mục tự động
    var cat_alias_timer;
    $("#cat_title").on("keyup", function() {
        var title = $(this).val();
        if (title !== "") {
            var catid = $(this).data('catid') || 0;
            var checkss = $(this).data('checkss') || '';
            clearTimeout(cat_alias_timer);
            cat_alias_timer = setTimeout(function() {
                $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat-alias&' + nv_lang_variable + '=' + nv_lang_data, {
                    title: title,
                    checkss: checkss,
                    catid: catid
                }, function(res) {
                    if (res.status === 'success') {
                        $("#cat_alias").val(res.alias);
                    }
                });
            }, 500);
        }
    });
});
