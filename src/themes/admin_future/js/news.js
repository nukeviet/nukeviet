/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function get_alias(mod, id) {
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&mod=' + mod + '&id=' + id, function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
    return false;
}

$(function () {
    // Select 2
    if ($('.select2').length) {
        $('.select2').select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });
    }

    // Chọn ngày tháng
    if ($('.datepicker').length) {
        $('.datepicker').datepicker({
            dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl'
        });
    }
    // Nút chọn ngày tháng
    $('[data-toggle="focusDate"]').on('click', function(e) {
        e.preventDefault();
        $('input', $(this).parent()).focus();
    });

    // Mở rộng thu gọn tìm kiếm tin tức
    const postAdvBtn = document.getElementById('search-adv');
    if (postAdvBtn) {
        let form = $('#form-search-post');
        postAdvBtn.addEventListener('hide.bs.collapse', () => {
            $('[name="adv"]', form).val('0');
        });
        postAdvBtn.addEventListener('show.bs.collapse', () => {
            $('[name="adv"]', form).val('1');
        });
    }

    // Xóa 1 bài viết
    $('[data-toggle="delArticle"]').on('click', function (e) {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checksess'),
                    id: btn.data('id')
                },
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    var r_split = res.split('_');
                    if (r_split[0] == 'OK') {
                        location.reload();
                    } else if (r_split[0] == 'ERR') {
                        nvToast(r_split[1], 'error');
                    } else {
                        nvToast(nv_is_del_confirm[2], 'error');
                    }
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Chọn 1/nhiều bài viết và thực hiện các chức năng
    $('[data-toggle="actionArticle"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.is(':disabled')) {
            return;
        }
        let ctn = $(btn.data('ctn')), listid = [];
        $('[data-toggle="checkSingle"]:checked', ctn).each(function () {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            nvAlert(nv_please_check);
            return;
        }
        let action = $('#element_action').val();

        if (action == 'delete') {
            nvConfirm(nv_is_del_confirm[0], () => {
                btn.prop('disabled', true);
                $('#element_action').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(),
                    data: {
                        checkss: $('body').data('checksess'),
                        listid: listid.join(',')
                    },
                    success: function (res) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        var r_split = res.split('_');
                        if (r_split[0] == 'OK') {
                            location.reload();
                        } else if (r_split[0] == 'ERR') {
                            nvToast(r_split[1], 'error');
                        } else {
                            nvToast(nv_is_del_confirm[2], 'error');
                        }
                    },
                    error: function (xhr, text, err) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        } else {
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + action + '&listid=' + listid.join(',') + '&checkss=' + $('body').data('checksess');
        }
    });

    // Sắp xếp bài viết tùy chỉnh
    let mdSortArt = $('#mdSortArticle');
    $('[data-toggle="sortArticle"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);

        $('#mdSortArticleLabel').text(btn.data('title'));
        $('#sortArticleCurrent').val(btn.data('weight'));
        $('#sortArticleNew').val(btn.data('weight'));

        mdSortArt.data('id', btn.data('id'));
        mdSortArt.data('checksess', btn.data('checksess'));
        mdSortArt.data('weight', btn.data('weight'));
        const md = bootstrap.Modal.getOrCreateInstance(mdSortArt[0]);
        md.show();
    });
    if (mdSortArt.length) {
        mdSortArt.on('shown.bs.modal', function () {
            $('#sortArticleNew').focus();
        });

        $('#sortArticleSave').on('click', function (e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
                data: {
                    order_articles_new: $('#sortArticleNew').val(),
                    order_articles_id: mdSortArt.data('id'),
                    order_articles_checkss: mdSortArt.data('checksess')
                },
                success: function (res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (res == 'OK') {
                        location.reload();
                        return;
                    }
                    nvToast(nv_is_change_act_confirm[2], 'error');
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }

    // Sao chép liên kết bài viết
    if ($('[data-toggle="copyArticleUrl"]').length) {
        var clipboard = new ClipboardJS('[data-toggle="copyArticleUrl"]');
        clipboard.on('success', function (e) {
            nvToast($(e.trigger).data('message'), 'success');
        });
    }

    // Lịch sử bài viết
    const mdHistory = $('#mdHistoryArticle');
    $('[data-toggle="historyArticle"]').on('click', function (e) {
        e.preventDefault();
        mdHistory.data('loadurl', $(this).data('loadurl'));
        (bootstrap.Modal.getOrCreateInstance(mdHistory[0])).show();
    });
    if (mdHistory.length) {
        mdHistory.on('show.bs.modal', function () {
            $('.modal-body', mdHistory).html('<div class="text-center"><i class="fa-solid fa-spinner fa-spin-pulse fa-2x"></i></div>').load(mdHistory.data('loadurl'));
        });
    }

    // Khôi phục lại lịch sử
    $(document).on('click', '[data-toggle="restoreHistory"]', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(btn.data('msg'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: btn.attr('href') + '&nocache=' + new Date().getTime(),
                data: {
                    restorehistory: $('body').data('checksess'),
                    id: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    window.location = respon.url;
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa 1 tag
    $('[data-toggle=nv_del_tag]').on('click', function (e) {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    del_tid: btn.data('tid')
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa nhiều tag
    $('[data-toggle=nv_del_check_tags]').on('click', function (e) {
        e.preventDefault();

        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        let listid = [];
        $('[data-toggle="checkSingle"][data-type="tag"]:checked').each(function () {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            nvAlert(nv_please_check);
            return;
        }
        listid = listid.join(',');
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    del_listid: listid
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xử lý thêm nhiều tag
    let mdTagMulti = $('#mdTagMulti');
    if (mdTagMulti.length) {
        mdTagMulti.on('hidden.bs.modal', function () {
            $('[name="mtitle"]', mdTagMulti).val('');
        });
    }

    // Xử lý thêm sửa 1 tag
    let mdTagSingle = $('#mdTagSingle');
    if (mdTagSingle.length) {
        $('[data-toggle="titlelength"]', mdTagSingle).html($('[name="title"]', mdTagSingle).val().length);
        $('[name="title"]', mdTagSingle).bind("keyup paste", function () {
            $('[data-toggle="titlelength"]', mdTagSingle).html($(this).val().length);
        });

        $('[data-toggle="descriptionlength"]', mdTagSingle).html($('[name="description"]', mdTagSingle).val().length);
        $('[name="description"]', mdTagSingle).bind("keyup paste", function () {
            $('[data-toggle="descriptionlength"]', mdTagSingle).html($(this).val().length);
        });

        function cleanFormTag() {
            $('.is-invalid', mdTagSingle).removeClass('is-invalid');
            $('.is-valid', mdTagSingle).removeClass('is-valid');

            $('[name="tid"]', mdTagSingle).val('0');
            $('[name="keywords"]', mdTagSingle).val('');
            $('[name="title"]', mdTagSingle).val('');
            $('[name="description"]', mdTagSingle).val('');
            $('[name="image"]', mdTagSingle).val('');
            $('[data-toggle="titlelength"]', mdTagSingle).text('0');
            $('[data-toggle="descriptionlength"]', mdTagSingle).text('0');
            $('[data-toggle="selectfile"]', mdTagSingle).data('currentpath', $('[data-toggle="selectfile"]', mdTagSingle).data('path'));
        }

        $('[data-toggle=add_tags]').on('click', function (e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.length && icon.is('.fa-spinner')) {
                return;
            }
            let md = bootstrap.Modal.getOrCreateInstance(mdTagSingle[0]);

            $('.modal-title', mdTagSingle).text(btn.data('mtitle'));
            if (btn.data('fc') == 'editTag') {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    data: {
                        checkss: $('body').data('checksess'),
                        loadEditTag: 1,
                        tid: btn.data('tid')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (respon) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (!respon.success) {
                            nvToast(respon.text, 'error');
                            return;
                        }
                        $('[name="tid"]', mdTagSingle).val(btn.data('tid'));
                        $('[name="keywords"]', mdTagSingle).val(respon.data.keywords);
                        $('[name="title"]', mdTagSingle).val(respon.data.title);
                        $('[name="description"]', mdTagSingle).val(respon.data.description);
                        $('[name="image"]', mdTagSingle).val(respon.data.image);
                        $('[data-toggle="selectfile"]', mdTagSingle).data('currentpath', respon.data.currentpath);

                        $('[name="title"]', mdTagSingle).trigger('keyup');
                        $('[name="description"]', mdTagSingle).trigger('keyup');

                        md.show();
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
                return;
            }
            cleanFormTag();
            md.show();
        });
    }

    // Xử lý thêm nhiều tag
    let mdTagLinks = $('#mdTagLinks');
    if (mdTagLinks.length) {
        mdTagLinks.on('hidden.bs.modal', function () {
            $('.modal-body', mdTagLinks).html('');
        });

        let md = bootstrap.Modal.getOrCreateInstance(mdTagLinks[0]);

        $('[data-toggle=link_tags]').on('click', function (e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            $('[data-toggle="tags_id_check_del"]', mdTagLinks).data('tid', btn.data('tid'));
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    tid: btn.data('tid'),
                    tagLinks: 1
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    $('.modal-body', mdTagLinks).html(respon.html);
                    md.show();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        mdTagLinks.on('click', '[data-toggle="tag_keyword_edit"]', function (e) {
            e.preventDefault();

            let item = $('[data-item="' + $(this).data('id') + '"]', mdTagLinks);
            $('.show-keywords', item).addClass('d-none');
            $('.edit-keywords', item).removeClass('d-none');
        });

        mdTagLinks.on('click', '[data-toggle="tag_keyword_close"]', function (e) {
            e.preventDefault();

            let item = $('[data-item="' + $(this).data('id') + '"]', mdTagLinks);
            $('.show-keywords', item).removeClass('d-none');
            $('.edit-keywords', item).addClass('d-none');
        });

        mdTagLinks.on('click', '[data-toggle="keyword_change"]', function (e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            let item = $('[data-item="' + btn.data('id') + '"]', mdTagLinks);
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    id: btn.data('id'),
                    tid: btn.data('tid'),
                    keyword: $('[name="keyword"]', item).val(),
                    keywordEdit: 1
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }

                    $('[data-toggle="badgeKeyword"]', item).removeClass('text-bg-success text-bg-warning').addClass('text-bg-success').html('<i class="fa-solid fa-check"></i> ' + respon.keyword);
                    $('.show-keywords', item).removeClass('d-none');
                    $('.edit-keywords', item).addClass('d-none');
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        mdTagLinks.on('click', '[data-toggle=tags_id_check_del]', function (e) {
            e.preventDefault();

            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            let listid = [];
            $('[data-toggle="checkSingle"][data-type="link"]:checked').each(function () {
                listid.push($(this).val());
            });
            if (listid.length < 1) {
                nvAlert(nv_please_check);
                return;
            }
            listid = listid.join(',');
            nvConfirm(nv_is_del_confirm[0], () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    data: {
                        checkss: $('body').data('checksess'),
                        tagsIdDel: 1,
                        ids: listid,
                        tid: btn.data('tid')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (respon) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (!respon.success) {
                            nvToast(respon.text, 'error');
                            return;
                        }
                        location.reload();
                    },
                    error: function (xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });
    }

    // Điều khiển chọn chuyên mục và chuyên mục chính khi viết bài
    $('[data-toggle="contentCatids"]').on('change', function () {
        const ctn = $(this).closest('[data-toggle="catids"]');
        let catids = [];

        // Xác định chuyên mục
        $('[name="catids[]"]', ctn).each(function () {
            if ($(this).is(':checked')) {
                catids.push($(this).val());
            }
        });

        // Ẩn chuyên mục chính
        $('[name="catid"]', ctn).each(function () {
            if (catids.includes($(this).val()) && catids.length > 1) {
                $(this).removeClass('invisible');
            } else {
                $(this).addClass('invisible').prop('checked', false);
            }
        });

        // Chọn chuyên mục chính đầu tiên
        if (catids.length > 1 && $('[name="catid"]:checked', ctn).length < 1) {
            $('[name="catid"]', ctn).filter(':not(.invisible)').first().prop('checked', true);
        }
    });

    // Từ khóa tại trang đăng tin
    const iptKeywords = $('#newcontent_keywords');
    if (iptKeywords.length) {
        iptKeywords.select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            tags: true,
            tokenSeparators: [',', ';'],
            ajax: {
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=keywordsajax&nocache=' + new Date().getTime(),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                        checkss: $('body').data('checksess')
                    };
                },
                cache: false
            },
            minimumInputLength: 2,
            placeholder: iptKeywords.data('placeholder')
        });
    }

    // Tag tại trang đăng tin
    const iptTags = $('#newcontent_tags');
    if (iptTags.length) {
        iptTags.select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            tags: true,
            tokenSeparators: [',', ';'],
            ajax: {
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tagsajax&nocache=' + new Date().getTime(),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                        checkss: $('body').data('checksess')
                    };
                },
                cache: false
            },
            minimumInputLength: 2,
            placeholder: iptTags.data('placeholder')
        });
    }

    // Tạo tag tự động dựa vào nội dung bài viết
    $('[data-toggle="tags_auto_create"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        const form = btn.closest('form');
        const mdata = form.data('mdata');
        let text = '';

        // Lấy mô tả ngắn gọn
        if (form.data('editor-hometext')) {
            if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[mdata + '_hometext']) {
                text += strip_tags(CKEDITOR.instances[mdata + '_hometext'].getData());
            } else if (typeof window.nveditor != "undefined" && window.nveditor[mdata + '_hometext']) {
                text += strip_tags(window.nveditor[mdata + '_hometext'].getData());
            }
        } else {
            text += strip_tags($('[name=hometext]', form).val());
        }

        // Lấy nội dung bài đăng
        text += ' ';
        if (form.data('editor')) {
            if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[mdata + '_bodyhtml']) {
                text += strip_tags(CKEDITOR.instances[mdata + '_bodyhtml'].getData());
            } else if (typeof window.nveditor != "undefined" && window.nveditor[mdata + '_bodyhtml']) {
                text += strip_tags(window.nveditor[mdata + '_bodyhtml'].getData());
            }
        } else {
            text += strip_tags($('[name=bodyhtml]', form).val());
        }
        text = trim(text.replace(/\n|\r/g, ' '));
        if (text != '') {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(),
                data: {
                    getTagsFromContent: 1,
                    checkss: $('body').data('checksess'),
                    content: text
                },
                dataType: 'json',
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    let html = '';
                    respon.forEach(keyword => {
                        html += '<option value="' + keyword + '" selected>' + keyword + '</option>';
                    });
                    iptTags.html(html).trigger('change');
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });

    // Tạo từ khóa tự động dựa vào nội dung bài viết
    $('[data-toggle="keywords_auto_create"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        const form = btn.closest('form');
        const mdata = form.data('mdata');
        let text = '';

        // Lấy mô tả ngắn gọn
        if (form.data('editor-hometext')) {
            if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[mdata + '_hometext']) {
                text += strip_tags(CKEDITOR.instances[mdata + '_hometext'].getData());
            } else if (typeof window.nveditor != "undefined" && window.nveditor[mdata + '_hometext']) {
                text += strip_tags(window.nveditor[mdata + '_hometext'].getData());
            }
        } else {
            text += strip_tags($('[name=hometext]', form).val());
        }

        // Lấy nội dung bài đăng
        text += ' ';
        if (form.data('editor')) {
            if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[mdata + '_bodyhtml']) {
                text += strip_tags(CKEDITOR.instances[mdata + '_bodyhtml'].getData());
            } else if (typeof window.nveditor != "undefined" && window.nveditor[mdata + '_bodyhtml']) {
                text += strip_tags(window.nveditor[mdata + '_bodyhtml'].getData());
            }
        } else {
            text += strip_tags($('[name=bodyhtml]', form).val());
        }
        text = trim(text.replace(/\n|\r/g, ' '));
        if (text != '') {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(),
                data: {
                    getKeywordsFromContent: 1,
                    checkss: $('body').data('checksess'),
                    content: text
                },
                dataType: 'json',
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    let html = '';
                    respon.forEach(keyword => {
                        html += '<option value="' + keyword + '" selected>' + keyword + '</option>';
                    });
                    iptKeywords.html(html).trigger('change');
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });

    // Tác giả tại trang đăng tin
    const iptInnerAuthor = $('#newcontent_internal_authors');
    if (iptInnerAuthor.length) {
        iptInnerAuthor.select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            ajax: {
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authors&nocache=' + new Date().getTime(),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        searchAjax: 1,
                        q: params.term,
                        page: params.page || 1,
                        checkss: $('body').data('checksess')
                    };
                },
                cache: false
            },
            minimumInputLength: 2,
            placeholder: iptKeywords.data('placeholder')
        });
    }

    // Xử lý đính kèm ở trang viết bài
    const ctnAttachFile = $('#newcontent-fileattach');
    if (ctnAttachFile.length) {
        // Thêm file
        ctnAttachFile.on('click', '[data-toggle=add_file]', function () {
            let item = $(this).closest('.item');
            let new_item = item.clone();
            let new_id = 'file_' + nv_randomPassword(12);
            $('[name^=files]', new_item).val('').attr('id', new_id);
            $('[data-toggle=selectfile]', new_item).attr('data-target', new_id);
            item.after(new_item);
        });

        // Xóa file
        ctnAttachFile.on('click', '[data-toggle=del_file]', function () {
            let item = $(this).closest('.item');
            if ($('.item', ctnAttachFile).length > 1) {
                item.remove();
            } else {
                $('[name^=files]', item).val('');
            }
        });
    }

    // Tắt mở khai báo phiên bản ngôn ngữ khác
    const iptContentLocale = $('#enable_localization');
    if (iptContentLocale.length) {
        iptContentLocale.on('change', function () {
            const collapse = bootstrap.Collapse.getOrCreateInstance('#localization_sector');
            if ($(this).is(':checked')) {
                collapse.show();
            } else {
                collapse.hide();
            }
        });

        const ctnContentLocale = $('#localization_sector');

        // Thêm phiên bản ngôn ngữ
        ctnContentLocale.on('click', '[data-toggle=add_local]', function () {
            var item = $(this).closest('.localitem'),
                new_item = item.clone();
            $('[name^=locallang], [name^=locallink]', new_item).val('');
            item.after(new_item);
        });

        // Xóa phiên bản ngôn ngữ
        ctnContentLocale.on('click', '[data-toggle=del_local]', function () {
            var item = $(this).closest('.localitem'),
                locallist = $(this).closest('.locallist');
            if ($('.localitem', locallist).length > 1) {
                item.remove();
            } else {
                $('[name^=locallang], [name^=locallink]', item).val('');
                iptContentLocale.trigger('click')
            }
        });
    }

    // Select dòng sự kiện tại trang viết bài
    const iptTopicId = $('#newcontent_topicid');
    if (iptTopicId.length) {
        iptTopicId.select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            ajax: {
                delay: 250,
                cache: false,
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(),
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        get_topic_json: 1,
                        page: params.page || 1,
                        checkss: $('body').data('checksess')
                    };
                }
            },
            minimumInputLength: 2
        });
    }

    // Tự tìm nguồn tin khi viết bài
    const iptSource = $('#newcontent_sourceid');
    if (iptSource.length) {
        let cachesource = {};
        iptSource.autocomplete({
            minLength: 2,
            delay: 250,
            source: function (request, response) {
                var term = request.term;
                if (term in cachesource) {
                    response(cachesource[term]);
                    return;
                }
                $.getJSON(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sourceajax&nocache=' + new Date().getTime(), request, function (data) {
                    cachesource[term] = data;
                    response(data);
                });
            },
            appendTo: iptSource.closest('div')
        });
    }

    // Lấy liên kết tĩnh
    const btnContentAutoAlias = $('[data-toggle="getaliaspost"]');
    if (btnContentAutoAlias.length) {
        btnContentAutoAlias.on('click', function() {
            get_alias();
        });
        $("#idtitle").on('change', function() {
            if (btnContentAutoAlias.data('auto-alias')) {
                get_alias();
            }
        });
    }

    // Điều khiển các tính năng mở rộng
    const ctnContentAdv = $('#newcontent-advanced-options');
    if (ctnContentAdv.length) {
        const collapseId = '#newcontent-advanced-body';

        if ($.cookie(nv_module_name + '_advtabcontent') == 'SHOW') {
            bootstrap.Collapse.getOrCreateInstance(collapseId).show();
        }
        document.querySelector(collapseId).addEventListener('hidden.bs.collapse', () => {
            $.cookie(nv_module_name + '_advtabcontent', 'HIDE', {
                expires: 7
            });
        });
        document.querySelector(collapseId).addEventListener('shown.bs.collapse', () => {
            $.cookie(nv_module_name + '_advtabcontent', 'SHOW', {
                expires: 7
            });
        });
    }

    const formContent = $('#form-news-content');
    if (formContent.length) {
        // Xử lý đếm số kí tự
        $("#titlelength").html($("#idtitle").val().length);
        $("#idtitle").bind("keyup paste", function() {
            $("#titlelength").html($(this).val().length);
        });
        $("#titlesitelength").html($("#idtitlesite").val().length);
        $("#idtitlesite").bind("keyup paste", function() {
            $("#titlesitelength").html($(this).val().length);
        });
        $("#descriptionlength").html($("#description").val().length);
        $("#description").bind("keyup paste", function() {
            $("#descriptionlength").html($(this).val().length);
        });

        // Chọn ngày tháng
        flatpickr.l10ns[nv_lang_interface].amPM = [nv_js_am, nv_js_pm];
        const fmt = nv_jsdate_post.replace(/dd/g, 'd').replace(/mm/g, 'n').replace(/yyyy/g, 'Y') + ' H:i';
        $("#publ_date").flatpickr({
            enableTime: true,
            dateFormat: fmt,
            ariaDateFormat: fmt,
            locale: nv_lang_interface,
            onOpen: function (selectedDates, dateStr, instance) {
                if (instance.input.value.length == 0) {
                    instance.setDate(new Date());
                }
            }
        });
        $("#exp_date").flatpickr({
            enableTime: true,
            dateFormat: fmt,
            ariaDateFormat: fmt,
            locale: nv_lang_interface
        });

        /**
         * Định kì gửi dữ liệu lên máy chủ để:
         * - Duy trì trạng thái sửa bài nếu đang sửa bài mỗi 10s
         * - Lưu dữ liệu định kì 30s 1 lần, lần đầu sau 2 phút vào viết bài mà có nhập liệu hoặc sửa bài
         * Không lưu nếu đang tự khôi phục lịch sử
         */
        let contentTimer = null;
        let contentInterval = 0, contentIntervalInit = 0;
        if (!formContent.data('auto-submit')) {
            if (formContent.data('is-edit')) {
                contentInterval = 10000;
                contentIntervalInit = 10000;
            } else if (formContent.data('auto-save')) {
                if (formContent.data('draft-id')) {
                    contentInterval = 30000;
                    contentIntervalInit = 30000;
                } else {
                    contentInterval = 30000;
                    contentIntervalInit = 120000;
                }
            }
        }

        if (contentIntervalInit > 0) {
            function contentRun() {
                clearTimeout(contentTimer);

                // Cập nhật trình soạn thảo vào textarea ở hometext
                if (typeof window.nveditor != "undefined" && window.nveditor[formContent.data('mdata') + '_hometext']) {
                    $('[name="hometext"]', formContent).val(window.nveditor[formContent.data('mdata') + '_hometext'].getData());
                } else if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[formContent.data('mdata') + '_hometext']) {
                    $('[name="hometext"]', formContent).val(CKEDITOR.instances[formContent.data('mdata') + '_hometext'].getData());
                }
                // Cập nhật trình soạn thảo vào textarea ở bodyhtml
                if (typeof window.nveditor != "undefined" && window.nveditor[formContent.data('mdata') + '_bodyhtml']) {
                    $('[name="bodyhtml"]', formContent).val(window.nveditor[formContent.data('mdata') + '_bodyhtml'].getData());
                } else if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml']) {
                    $('[name="bodyhtml"]', formContent).val(CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml'].getData());
                }
                const formData = new FormData(formContent[0]);
                formData.append('last_data_saved', formContent.data('last-data-saved'));
                formData.append('ajax_content', 1);

                if (formContent.data('is-edit')) {
                    formData.append('check_edit', 1);
                }

                $.ajax({
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    cache: false,
                    success: function(respon) {
                        if (respon.status == 'compromised') {
                            // Bị chiếm quyền sửa
                            nvAlert(respon.mess);
                            $('.submit-post', formContent).remove();
                            return;
                        }
                        contentTimer = setTimeout(contentRun, contentInterval);
                        if (respon.status == 'success') {
                            formContent.data('last-data-saved', respon.last_data_saved);
                        }
                    },
                    error: function (xhr, text, err) {
                        console.log(xhr, text, err);
                        nvToast(err, 'error');
                        contentTimer = setTimeout(contentRun, contentInterval);
                    }
                });
            }
            contentTimer = setTimeout(contentRun, contentIntervalInit);
        }

        // Kiểm tra form đăng bài trước khi submit
        function getBodyHtml() {
            let bodyhtml = '';
            if (typeof window.nveditor != "undefined" && window.nveditor[formContent.data('mdata') + '_bodyhtml']) {
                bodyhtml = trim(window.nveditor[formContent.data('mdata') + '_bodyhtml'].getData());
            } else if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml']) {
                bodyhtml = trim(CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml'].getData());
            } else {
                bodyhtml = trim($('[name="bodyhtml"]', formContent).val());
            }
            return trim(bodyhtml);
        }
        function setFocusBodyHtml() {
            let scroll = false;
            if (typeof window.nveditor != "undefined" && window.nveditor[formContent.data('mdata') + '_bodyhtml']) {
                window.nveditor[formContent.data('mdata') + '_bodyhtml'].editing.view.focus();
                scroll = true;
            } else if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml']) {
                CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml'].focus();
                scroll = true;
            } else {
                $('[name="bodyhtml"]', formContent).focus();
            }
            if (scroll) {
                $('html, body').animate({
                    scrollTop: $('[data-toggle="container-bodyhtml"]', formContent).offset().top - 80
                }, 200);
            }
        }
        function isRequiredBodyHtml() {
            let required = false;
            if (trim($('[name="sourcetext"]', formContent).val()) == '') {
                required = true;
            }
            if (!$('[name="external_link"]', formContent).is(':checked')) {
                required = true;
            }
            if (required) {
                $('[data-toggle="required-bodyhtml"]', formContent).removeClass('d-none');
            } else {
                $('[data-toggle="required-bodyhtml"]', formContent).addClass('d-none');
                $('[data-toggle="container-bodyhtml"]', formContent).removeClass('is-invalid');
                $('[name="bodyhtml"]', formContent).removeClass('is-invalid');
            }
            return required;
        }
        function validateContentForm(e) {
            // Kiểm tra tiêu đề
            const iptTitle = $('[name="title"]', formContent);
            let errorCount = 0;
            if (trim(iptTitle.val()).length == 0) {
                iptTitle.addClass('is-invalid');
                if (errorCount++ == 0) {
                    iptTitle.focus();
                }
            }

            // Kiểm tra nội dung bài đăng
            if ((getBodyHtml()).length == 0 && isRequiredBodyHtml()) {
                $('[data-toggle="container-bodyhtml"]', formContent).addClass('is-invalid');
                $('[name="bodyhtml"]', formContent).addClass('is-invalid');
                if (errorCount++ == 0) {
                    setFocusBodyHtml();
                }
            }

            // Kiểm tra chuyên mục
            const catids = $('[name="catids[]"]', formContent);
            const catidsChecked = $('[name="catids[]"]:checked', formContent);
            if (catidsChecked.length == 0) {
                $('.catids-items', formContent).addClass('is-invalid');
                catids.addClass('is-invalid');
                if (errorCount++ == 0) {
                    $(catids[0]).focus();
                }
            }
            return errorCount;
        }

        formContent.on('submit', function(e) {
            let errorCount = validateContentForm(e);
            if (errorCount > 0) {
                e.preventDefault();
            } else if (contentTimer) {
                clearTimeout(contentTimer);
                contentTimer = null;
            }
        });
        // Gỡ bỏ lỗi đỏ ở chuyên mục khi chọn
        $('[name="catids[]"]', formContent).on('change keyup', function(e) {
            const catidsChecked = $('[name="catids[]"]:checked', formContent);
            if (catidsChecked.length > 0) {
                $('.catids-items', formContent).removeClass('is-invalid');
                $('[name="catids[]"]', formContent).removeClass('is-invalid');
            }
        });
        // Gỡ bỏ lỗi đỏ trong nội dung ô nhập thông thường
        $('[type="text"]', formContent).on('change keyup', function(e) {
            if (!$(this).is(':visible') || (e.type == "keyup" && e.which == 13)) {
                return;
            }
            if (trim($(this).val()) == '' && $(this).is('.required')) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid is-valid');
            }
        });
        // Gỡ bỏ lỗi đỏ ở nội dung bài đăng
        if (typeof window.nveditor != "undefined" && window.nveditor[formContent.data('mdata') + '_bodyhtml']) {
            const editor = window.nveditor[formContent.data('mdata') + '_bodyhtml'];
            editor.model.document.on('change:data', () => {
                if (trim(editor.getData()) == '' && isRequiredBodyHtml()) {
                    $('[data-toggle="container-bodyhtml"]', formContent).addClass('is-invalid');
                    $('[name="bodyhtml"]', formContent).addClass('is-invalid');
                } else {
                    $('[data-toggle="container-bodyhtml"]', formContent).removeClass('is-invalid');
                    $('[name="bodyhtml"]', formContent).removeClass('is-invalid');
                }
            });
        } else if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml']) {
            const editor = CKEDITOR.instances[formContent.data('mdata') + '_bodyhtml'];
            editor.on('change', function (e) {
                if (trim(e.editor.getData()) == '' && isRequiredBodyHtml()) {
                    $('[data-toggle="container-bodyhtml"]', formContent).addClass('is-invalid');
                    $('[name="bodyhtml"]', formContent).addClass('is-invalid');
                } else {
                    $('[data-toggle="container-bodyhtml"]', formContent).removeClass('is-invalid');
                    $('[name="bodyhtml"]', formContent).removeClass('is-invalid');
                }
            });
        } else {
            $('[name="bodyhtml"]', formContent).on('change keyup', function() {
                if (trim($(this).val()) == '' && isRequiredBodyHtml()) {
                    $(this).addClass('is-invalid');
                    $('[data-toggle="container-bodyhtml"]', formContent).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid is-valid');
                    $('[data-toggle="container-bodyhtml"]', formContent).removeClass('is-invalid is-valid');
                }
            });
        }

        // Xử lý nút từ chối bài viết
        const mReject = $('#modal-confirm-reject');
        const mdReject = bootstrap.Modal.getOrCreateInstance(mReject[0]);
        $('.submit-reject').on('click', function(e) {
            e.preventDefault();
            // Kiểm tra form trước khi nhập nguyên nhân từ chối
            const checkForm = validateContentForm(e);
            if (checkForm <= 0) {
                mReject.append('<input type="hidden" id="reject_status_tmp" name="status' + $(this).data('type') + '" value="1">');
                mdReject.show();
            }
        });
        mReject[0].addEventListener('shown.bs.modal', () => {
            $('#reject_reason').focus();
        });
        mReject[0].addEventListener('hide.bs.modal', () => {
            const iptSubmit = $('#reject_status_tmp');
            if (iptSubmit.length) {
                iptSubmit.remove();
            }
        });
        $('[name="save_reject"]').on('click', function(e) {
            if (trim($('#reject_reason').val()) == '') {
                e.preventDefault();
                $('#reject_reason').focus();
                nukeviet.toast($(this).data('error'), 'error');
                return false;
            }
        });

        // Check real-time khi hover vào các nút submit
        $('.submit-post', formContent).hover(function() {
            const notices = [];
            const eleNotice = $('#realtime-notice');
            if ($('[name="tags[]"]', formContent).val().length == 0) {
                notices.push(formContent.data('notice-empty-tags'));
            }
            if (trim($('[name="alias"]', formContent).val()) == '') {
                notices.push(formContent.data('notice-empty-alias'));
            }

            if (notices.length) {
                eleNotice.html(notices.join('<br />')).removeClass('d-none');
            } else {
                eleNotice.addClass('d-none');
            }
        });

        // Kiểm tra và điều khiển trạng thái "bắt buộc" đối với nội dung chi tiết
        $('[name="sourcetext"]', formContent).on('change keyup paste', function() {
            isRequiredBodyHtml();
        });
        $('[name="external_link"]', formContent).on('change', function() {
            isRequiredBodyHtml();
        });
    }

    // Trang thông báo chuyển hướng
    const redirectPage = $('#redriect-page');
    if (redirectPage.length) {
        if (redirectPage.data('autosave-key') && redirectPage.data('autosave-key').length > 0) {
            if (typeof (Storage) !== 'undefined' && localStorage.getItem(redirectPage.data('autosave-key'))) {
                localStorage.removeItem(redirectPage.data('autosave-key'));
            }
        }

        if (redirectPage.data('go-back')) {
            setTimeout('history.back()', redirectPage.data('go-back-time'));
        }
    }

    // Xóa hàng loạt báo cáo lỗi
    $('[data-toggle="report_del_check_action"]').on('click', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        let listid = [];
        $('[data-toggle="checkSingle"]:checked').each(function () {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            nvAlert(btn.data('not-checked'));
            return;
        }
        const ctn = btn.closest('.list-report');

        nvConfirm(ctn.data('del-confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    action: 'multidel',
                    list: listid
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa 1 báo cáo lỗi
    $('[data-toggle="report_del_action"]').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        const ctn = btn.closest('.list-report');
        nvConfirm(ctn.data('del-confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    action: btn.data('send-mail') == 'yes' ? 'del_mail_action' : 'del_action',
                    rid: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    const iptRelated = $('#newcontent_related_ids');
    if (iptRelated.length) {
        iptRelated.select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            ajax: {
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(),
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        id: iptRelated.data('id'),
                        q: params.term,
                        page: params.page || 1,
                        checkss: $('body').data('checksess'),
                        get_article_json: 1
                    };
                },
                cache: false
            },
            minimumInputLength: 2,
            placeholder: iptRelated.data('placeholder')
        });
    }

    // Hủy một bản nháp
    $('[data-toggle="draft_cancel"]').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        const ctn = btn.closest('.list');
        nvConfirm(ctn.data('del-confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=drafts&nocache=' + new Date().getTime(),
                data: {
                    delete: $('body').data('checksess'),
                    id: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Chọn 1/nhiều bản nháp và thực hiện các chức năng
    $('[data-toggle="actionDrafts"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (btn.is(':disabled')) {
            return;
        }
        let listid = [];
        $('[data-toggle="checkSingle"]:checked').each(function () {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            nvAlert(nv_please_check);
            return;
        }
        let action = $('#element_action').val();
        const ctn = btn.closest('.list');

        if (action == 'cancel') {
            nvConfirm(ctn.data('del-confirm'), () => {
                btn.prop('disabled', true);
                $('#element_action').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=drafts&nocache=' + new Date().getTime(),
                    data: {
                        delete: $('body').data('checksess'),
                        listid: listid.join(',')
                    },
                    success: function (respon) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        if (!respon.success) {
                            nvToast(respon.text, 'error');
                            return;
                        }
                        location.reload();
                    },
                    error: function (xhr, text, err) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        nvToast(err, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        }
    });
});

$(window).on('load', function() {
    // Tự khôi phục bài đăng
    const formContent = $('#form-news-content');
    if (formContent.length && formContent.data('auto-submit')) {
        setTimeout(function() {
            if ($('[name="status1"]', formContent).length) {
                $('[name="status1"]', formContent).trigger('click');
            } else if ($('[name="statussave"]', formContent).length) {
                $('[name="statussave"]', formContent).trigger('click');
            } else {
                $('[type="submit"]:first', formContent).trigger('click');
            }
        }, 2000);
    }
});
