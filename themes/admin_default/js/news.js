/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_show_list_cat(parentid) {
    if (document.getElementById('module_show_list')) {
        $('#module_show_list').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_cat&parentid=' + parentid + '&nocache=' + new Date().getTime());
    }
    return;
}

function nv_del_cat(catid) {
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid, function(res) {
        nv_del_cat_result(res);
    });
    return false;
}

function nv_del_cat_result(res) {
    var r_split = res.split('_');
    if (r_split[0] == 'OK') {
        var parentid = parseInt(r_split[1]);
        nv_show_list_cat(parentid);
    } else if (r_split[0] == 'CONFIRM') {
        if (confirm(nv_is_del_confirm[0])) {
            var catid = r_split[1];
            var delallcheckss = r_split[2];
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
                nv_del_cat_result(res);
            });
        }
    } else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
        alert(r_split[2]);
    } else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
        if (confirm(r_split[4])) {
            var catid = r_split[2];
            var delallcheckss = r_split[3];
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
                $("#edit").html(res);
            });
            parent.location = '#edit';
        }
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

function nv_chang_topic(topicid, mod) {
    var nv_timer = nv_settimeout_disable('id_' + mod + '_' + topicid, 5000);
    var new_vid = $('#id_' + mod + '_' + topicid).val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        clearTimeout(nv_timer);
        nv_show_list_topic();
    });
    return;
}

function nv_show_list_topic() {
    if (document.getElementById('module_show_list')) {
        $('#module_show_list').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_topic&nocache=' + new Date().getTime());
    }
    return;
}

function nv_del_topic(topicid) {
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid, function(res) {
        nv_del_topic_result(res);
    });
}

function nv_del_topic_result(res) {
    var r_split = res.split('_');
    if (r_split[0] == 'OK') {
        nv_show_list_topic();
    } else if (r_split[0] == 'ERR') {
        if (r_split[1] == 'ROWS') {
            if (confirm(r_split[4])) {
                var topicid = r_split[2];
                var checkss = r_split[3];
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid + '&checkss=' + checkss, function(res) {
                    nv_del_topic_result(res);
                });
            }
        } else {
            alert(r_split[1]);
        }
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

function nv_chang_sources(sourceid, mod) {
    var nv_timer = nv_settimeout_disable('id_' + mod + '_' + sourceid, 5000);
    var new_vid = $('#id_' + mod + '_' + sourceid).val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'sourceid=' + sourceid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        clearTimeout(nv_timer);
        nv_show_list_source();
    });
    return;
}

function nv_show_list_source() {
    if (document.getElementById('module_show_list')) {
        $('#module_show_list').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_source&nocache=' + new Date().getTime());
    }
    return;
}

function nv_del_source(sourceid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source&nocache=' + new Date().getTime(), 'sourceid=' + sourceid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                nv_show_list_source();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_del_block_cat(bid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                nv_show_list_block_cat();
            } else if (r_split[0] == 'ERR') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_chang_block_cat(bid, mod) {
    var nv_timer = nv_settimeout_disable('id_' + mod + '_' + bid, 5000);
    var new_vid = $('#id_' + mod + '_' + bid).val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=chang_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        clearTimeout(nv_timer);
        nv_show_list_block_cat();
    });
    return;
}

function nv_show_list_block_cat() {
    if (document.getElementById('module_show_list')) {
        $('#module_show_list').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block_cat&nocache=' + new Date().getTime());
    }
    return;
}

function nv_chang_block(bid, id, mod) {
    if (mod == 'delete' && !confirm(nv_is_del_confirm[0])) {
        return false;
    }
    var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
    var new_vid = $('#id_weight_' + id).val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'id=' + id + '&bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        nv_chang_block_result(res);
    });
    return;
}

function nv_chang_block_result(res) {
    var r_split = res.split('_');
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    var bid = parseInt(r_split[1]);
    nv_show_list_block(bid);
    return;
}

function nv_show_list_block(bid) {
    if (document.getElementById('module_show_list')) {
        $('#module_show_list').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block&bid=' + bid + '&nocache=' + new Date().getTime());
    }
    return;
}

function nv_del_block_list(oForm, bid) {
    var del_list = '';
    var fa = oForm['idcheck[]'];
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                del_list = del_list + ',' + fa[i].value;
            }
        }
    } else {
        if (fa.checked) {
            del_list = del_list + ',' + fa.value;
        }
    }

    if (del_list != '') {
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'del_list=' + del_list + '&bid=' + bid, function(res) {
                nv_chang_block_result(res);
            });
        }
    }
}

function nv_main_action(oForm, checkss, msgnocheck) {
    var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + fa[i].value + ',';
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + fa.value + ',';
        }
    }

    if (listid != '') {
        var action = document.getElementById('action').value;
        if (action == 'delete') {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
                    nv_del_content_result(res);
                });
            }
        } else {
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + action + '&listid=' + listid + '&checkss=' + checkss;
        }
    } else {
        alert(msgnocheck);
    }
}

function nv_del_content(id, checkss, base_adminurl, detail) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
            nv_del_content_result(res);
        });
    }
    return false;
}

function nv_check_movecat(oForm, msgnocheck) {
    var fa = oForm['catidnews'];
    if (fa.value == 0) {
        alert(msgnocheck);
        return false;
    }
}

function nv_del_content_result(res) {
    var r_split = res.split('_');
    if (r_split[0] == 'OK') {
        window.location.href = window.location.href;
    } else if (r_split[0] == 'ERR') {
        alert(r_split[1]);
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

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

function checkallfirst() {
    $(this).one("click", checkallsecond);
    $('input:checkbox').each(function() {
        $(this).attr('checked', 'checked');
    });
}

function checkallsecond() {
    $(this).one("click", checkallfirst);
    $('input:checkbox').each(function() {
        $(this).removeAttr('checked');
    });
}

function check_add_first() {
    $(this).one("dblclick", check_add_second);
    $("input[name='add_content[]']:checkbox").prop("checked", true);
}

function check_add_second() {
    $(this).one("dblclick", check_add_first);
    $("input[name='add_content[]']:checkbox").prop("checked", false);
}

function check_app_first() {
    $(this).one("dblclick", check_app_second);
    $("input[name='app_content[]']:checkbox").prop("checked", true);
}

function check_app_second() {
    $(this).one("dblclick", check_app_first);
    $("input[name='app_content[]']:checkbox").prop("checked", false);
}

function check_pub_first() {
    $(this).one("dblclick", check_pub_second);
    $("input[name='pub_content[]']:checkbox").prop("checked", true);
}

function check_pub_second() {
    $(this).one("dblclick", check_pub_first);
    $("input[name='pub_content[]']:checkbox").prop("checked", false);
}

function check_edit_first() {
    $(this).one("dblclick", check_edit_second);
    $("input[name='edit_content[]']:checkbox").prop("checked", true);
}

function check_edit_second() {
    $(this).one("dblclick", check_edit_first);
    $("input[name='edit_content[]']:checkbox").prop("checked", false);
}

function check_del_first() {
    $(this).one("dblclick", check_del_second);
    $("input[name='del_content[]']:checkbox").prop("checked", true);
}

function check_del_second() {
    $(this).one("dblclick", check_del_first);
    $("input[name='del_content[]']:checkbox").prop("checked", false);
}

function check_admin_first() {
    $(this).one("dblclick", check_admin_second);
    $("input[name='admin_content[]']:checkbox").prop("checked", true);
}

function check_admin_second() {
    $(this).one("dblclick", check_admin_first);
    $("input[name='admin_content[]']:checkbox").prop("checked", false);
}

$(function() {
    $('#checkall').click(function() {
        $('input:checkbox').each(function() {
            $(this).attr('checked', 'checked');
        });
    });
    $('#uncheckall').click(function() {
        $('input:checkbox').each(function() {
            $(this).removeAttr('checked');
        });
    });

    // Content: Add file
    $('body').on('click', '[data-toggle=add_file]', function() {
        var item = $(this).parents('.item'),
            new_item = item.clone(),
            new_id = 'file_' + nv_randomPassword(12);
        $('[name^=files]', new_item).val('').attr('id', new_id);
        $('[data-toggle=selectfile]', new_item).attr('data-target', new_id);
        item.after(new_item)
    });

    // Content: Delete file
    $('body').on('click', '[data-toggle=del_file]', function() {
        var item = $(this).parents('.item'),
            num = $('#filearea .item').length;
        if (num > 1) {
            item.remove()
        } else {
            $('[name^=files]', item).val('')
        }
    });

    // Topic
    $('#delete-topic').click(function() {
        var list = [];
        $('input[name=newsid]:checked').each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.topic_nocheck);
            return false;
        }
        if (confirm(LANG.topic_delete_confirm)) {
            $.ajax({
                type: 'POST',
                url: 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicdelnews',
                data: 'list=' + list,
                success: function(data) {
                    alert(data);
                    window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid=' + CFG.topicid;
                }
            });
        }
        return false;
    });

    // Tag Search
    $('[data-toggle=nv_search_tag]').on('submit', function(e) {
        e.preventDefault();
        var q = $('[name=q]', this).val(),
            url = $(this).attr('action');
        q = trim(strip_tags(q));
        $('[name=q]', this).val(q);
        if (q.length < 3) {
            window.location.href = url
        } else {
            window.location.href = url + "&q=" + rawurlencode(q)
        }
    });

    // Add Tag
    $('[data-toggle=add_tags]').on('click', function(e) {
        e.preventDefault();
        var title = $(this).data('title'),
            fc = $(this).data('fc'),
            dat = fc + '=1';
        if (fc == 'editTag' | fc == 'tagLinks') {
            dat += '&tid=' + $(this).data('tid')
        }

        $.ajax({
            type: 'POST',
            cache: !1,
            url: script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=tags',
            data: dat,
            dataType: "html",
            success: function(b) {
                $('#addTag .modal-title').text(title);
                $('#addTag .modal-body').html(b);
                $('#addTag').modal({
                    backdrop: 'static'
                })
            }
        });
    });

    // Delete tag
    $('[data-toggle=nv_del_tag]').on('click', function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            var tid = $(this).data('tid'),
                form = $(this).parents('form'),
                checkss = form.data('checkss');
            $.ajax({
                type: 'POST',
                cache: !1,
                url: script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=tags&num=' + nv_randomPassword(10),
                data: 'del_tid=' + tid + '&checkss=' + checkss,
                dataType: "html",
                success: function(b) {
                    window.location.href = window.location.href
                }
            });
        }
    });

    // Delete check tags
    $('[data-toggle=nv_del_check_tags]').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form'),
            checkss = form.data('checkss'),
            tids = '';
        if ($('[name^=idcheck]:checked', form).length) {
            $('[name^=idcheck]:checked', form).each(function() {
                if (tids != '') tids += ',';
                tids += $(this).val()
            })
        }
        if (tids == '') {
            alert($(this).data('msgnocheck'));
            return !1
        }

        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: 'POST',
                cache: !1,
                url: script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=tags&num=' + nv_randomPassword(10),
                data: 'del_listid=' + tids + '&checkss=' + checkss,
                dataType: "html",
                success: function(b) {
                    window.location.href = window.location.href
                }
            });
        }
    });

    // News content
    $('.submit-post').hover(function() {
        if ($('[name="keywords[]"]').length == 0) {
            if ($('#message-tags').length == 0) {
                $('#message').append('<div id="message-tags" class="alert alert-danger">' + LANG.content_tags_empty + '</div>');
            }
        } else {
            $('#message-tags').remove();
        }
        if ($('[name="alias"]').val() == '') {
            if ($('#message-alias').length == 0) {
                $('#message').append('<div id="message-alias" class="alert alert-danger">' + LANG.alias_empty_notice + '.</div>');
            }
        } else {
            $('#message-alias').remove();
        }
    });

    // Add to topic
    $('#update-topic').click(function() {
        var listid = [];
        $('input[name=idcheck]:checked').each(function() {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            alert(LANG.topic_nocheck);
            return false;
        }
        var topic = $('select[name=topicsid]').val();
        $.ajax({
            type: 'POST',
            url: 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addtotopics',
            data: 'listid=' + listid + '&topicsid=' + topic,
            success: function(data) {
                alert(data);
                window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid=' + topic;
            }
        });
        return false;
    });

    // Cat
    $('a.viewinstantrss').click(function(e) {
        e.preventDefault();
        modalShow($(this).data('modaltitle'), '<div><input type="text" class="form-control w500" value="' + $(this).attr('href') + '" data-toggle="selectall"/></div>');
    });
    var popOverALl = new Array();
    // Thay đổi thứ tự số chuyên mục: Thứ tự, số liên kết, ngày mới
    $(document).delegate('[data-toggle="changecat"]', 'click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        popOverALl.push(this);
        if (!$(this).data('havepop')) {
            $(this).data('havepop', true);
            $(this).popover({
                container: "body",
                html: true,
                placement: "bottom",
                content: getPopoverContent(this),
                trigger: "manual"
            });
            $(this).popover('show');
            $(this).on('shown.bs.popover', function() {
                var $this = $(this);
                var ctn = $('#' + $this.attr('aria-describedby'));
                var wrapArea = ctn.find('.dropdown-cattool-ctn');
                var wrapContent = ctn.find('.dropdown-cattool');
                wrapContent.find('[data-value="' + $this.data('current') + '"]').addClass('active');
                if (wrapArea.height() < wrapContent.height()) {
                    var item = wrapContent.find('li:first');
                    var scrollTop = ($this.data('current') - $this.data('min')) * item.height();
                    wrapArea.scrollTop(scrollTop);
                }
            });
        }
    });
    $(document).delegate('.dropdown-cattool a', 'click', function(e) {
        e.preventDefault();
        destroyAllPop();
        var $this = $(this);
        var ctn = $this.parent().parent();
        var btn = $('#cat_' + ctn.data('mod') + '_' + ctn.data('catid'));
        if (ctn.data('mod') == 'status' && $this.data('value') == 0 && !confirm(btn.data('cmess'))) {
            return 0;
        }
        btn.find('span.text').html('<i class="fa fa-spinner fa-spin fa-fw"></i>' + $this.html());
        btn.prop('disabled', true);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&nocache=' + new Date().getTime(), 'catid=' + ctn.data('catid') + '&mod=' + ctn.data('mod') + '&new_vid=' + $this.data('value'), function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            var parentid = parseInt(r_split[1]);
            nv_show_list_cat(parentid);
            return;
        });
    });
    // Các thao tác với popover
    $(document).delegate('div.popover', 'click', function(e) {
        e.stopPropagation();
    });
    $(window).on('click', function() {
        destroyAllPop();
    });

    function destroyAllPop() {
        $.each(popOverALl, function(k, v) {
            $(v).popover('destroy');
            $(v).data('havepop', false);
        });
        popOverALl = new Array();
    }

    // Setting Instant Articles
    $(document).delegate('[data-toggle="selectall"]', 'focus', function() {
        $(this).select();
    });
    $('.showhidepass').click(function(e) {
        e.preventDefault();
        var tg = $($(this).data('target'));
        if (tg.prop('type') == 'text') {
            tg.prop('type', 'password');
        } else {
            tg.prop('type', 'text');
        }
    });
    $('.genrandpass').click(function(e) {
        e.preventDefault();
        $($(this).data('target')).prop('type', 'text');
        $($(this).data('target')).val(nv_randomPassword(10));
    });

    if ($("#from_date, #to_date").length) {
        $("#from_date, #to_date").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showOn: 'focus'
        });
    }

    $('#to-btn').click(function() {
        $("#to_date").datepicker('show');
    });

    $('#from-btn').click(function() {
        $("#from_date").datepicker('show');
    });

    // Hiển thị lịch sử bài viết
    $('[data-btn="showhistory"]').on('click', function(e) {
        e.preventDefault();
        $('#md-history').data('loadurl', $(this).data('loadurl')).modal('show');
    });
    $('#md-history').on('show.bs.modal', function() {
        var $this = $(this);
        $('.table-responsive', $this).html('<div class="panel-body text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>').load($this.data('loadurl'));
    });

    // Khôi phục lại lịch sử
    $(document).delegate('[data-btn="restorehistory"]', 'click', function(e) {
        e.preventDefault();
        var $this = $(this);
        if (!confirm($this.data('msg'))) {
            return;
        }
        $.ajax({
            type: 'POST',
            url: $this.attr('href') + '&nocache=' + new Date().getTime(),
            data: {
                restorehistory: $this.data('tokend'),
                id: $this.data('id')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                if (!respon.success) {
                    alert(respon.text);
                    return;
                }
                window.location = respon.url;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Request Error!!!');
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    });

    // Xóa báo cáo lỗi
    $('.report_del_action, .report_del_mail_action').on('click', function(e) {
        e.preventDefault();
        var url = $(this).parents('.list-report').data('url'),
            rid = $(this).parents('.item').data('id'),
            action = $(this).is('.report_del_action') ? 'del_action' : 'del_mail_action',
            conf = confirm($(this).parents('.list-report').data('del-confirm'));
        if (conf) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    'action': action,
                    'rid': rid
                },
                cache: false,
                success: function(respon) {
                    window.location.href = window.location.href;
                }
            })
        }
    });

    // Xóa hàng loạt báo cáo lỗi
    $('.report_del_check_action').on('click', function(e) {
        e.preventDefault();
        var url = $(this).parents('.list-report').data('url'),
            list = [];
        $('.checkitem:checked').each(function() {
            list.push($(this).val());
        });
        if (!list.length) {
            alert($(this).data('not-checked'));
            return !1
        }
        if (confirm($(this).parents('.list-report').data('del-confirm'))) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    'action': 'multidel',
                    'list': list
                },
                cache: false,
                success: function(respon) {
                    window.location.href = window.location.href;
                }
            })
        }
    });

    // Chọn/bỏ chọn tất cả
    $('.list-report .checkall').on('change', function(e) {
        $('.list-report .checkall, .list-report .checkitem').prop('checked', $(this).is(':checked'))
    });
    $('.list-report .checkitem').on('change', function(e) {
        $('.list-report .checkall').prop('checked', $('.list-report .checkitem:not(:checked').length == 0)
    });

    //Kích hoạt khai báo phiên bản ngôn ngữ
    $('#enable_localization').on('change', function() {
        $('#localization_sector').collapse($(this).is(':checked') ? 'show' : 'hide')
    });

    // Content: Add local version
    $('body').on('click', '[data-toggle=add_local]', function() {
        var item = $(this).parents('.localitem'),
            new_item = item.clone();
        $('[name^=locallang], [name^=locallink]', new_item).val('');
        item.after(new_item)
    });

    // Content: remove local version
    $('body').on('click', '[data-toggle=del_local]', function() {
        var item = $(this).parents('.localitem'),
            locallist = $(this).parents('.locallist');
        if ($('.localitem', locallist).length > 1) {
            item.remove()
        } else {
            $('[name^=locallang], [name^=locallink]', item).val('');
            $('#enable_localization').trigger('click')
        }
    });

});

function nv_sort_content(id, w) {
    $("#order_articles").dialog("open");
    $("#order_articles_title").text($("#id_" + id).attr("title"));
    $("#order_articles_id").val(id, w);
    $("#order_articles_number").val(w);
    $("#order_articles_new").val(w);
    return false;
}

function getPopoverContent(e) {
    var tmpcat;
    if ($(e).data('mod') == "status") {
        tmpcat = $("#cat_list_status");
    } else if ($(e).data('mod') == 'viewcat') {
        if ($(e).data('mode') == 'full') {
            tmpcat = $("#cat_list_full");
        } else {
            tmpcat = $("#cat_list_nosub");
        }
    } else {
        var keyID = "#tmpcat_" + $(e).data('mod');
        tmpcat = $(keyID);
        if (tmpcat.length && tmpcat.data('num') != $(e).data('num')) {
            tmpcat.remove();
            tmpcat = $(keyID);
        }
        if (!tmpcat.length) {
            $('body').append('<ul id="tmpcat_' + $(e).data('mod') + '" class="hidden" data-num="' + $(e).data('num') + '"></ul>');
            tmpcat = $(keyID);
            for (i = $(e).data('min'); i <= $(e).data('num'); i++) {
                tmpcat.append('<li><a href="#" data-value="' + i + '">' + i + '</a></li>');
            }
        }
    }
    return '<div class="dropdown-cattool-ctn"><ul class="dropdown-cattool" data-mod="' + $(e).data('mod') + '" data-catid="' + $(e).data('catid') + '">' + tmpcat.html() + '</ul></div>';
}

function nv_change_voice_weight(id, tokend) {
    var new_weight = $('#change_weight_' + id).val();
    $('#change_weight_' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voices&nocache=' + new Date().getTime(),
        'changeweight=' + tokend + '&id=' + id + '&new_weight=' + new_weight,
        function(res) {
            $('#change_weight_' + id).prop('disabled', false);
            var r_split = res.split("_");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            location.reload();
        });
}

function nv_change_voice_status(id, tokend) {
    $('#change_status' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voices&nocache=' + new Date().getTime(),
        'changestatus=' + tokend + '&id=' + id,
        function(res) {
            $('#change_status' + id).prop('disabled', false);
            if (res != 'OK') {
                alert(nv_is_change_act_confirm[2]);
                location.reload();
            }
        });
}

function nv_delele_voice(id, tokend) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voices&nocache=' + new Date().getTime(),
            'delete=' + tokend + '&id=' + id,
            function(res) {
                var r_split = res.split("_");
                if (r_split[0] == 'OK') {
                    location.reload();
                } else {
                    alert(nv_is_del_confirm[2]);
                }
            });
    }
}
