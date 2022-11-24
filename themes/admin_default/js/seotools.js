/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function formatStringAsUriComponent(s) {
    // replace html with whitespace
    s = s.replace(/<\/?[^>]*>/gm, " ");

    // remove entities
    s = s.replace(/&[\w]+;/g, "");

    // remove 'punctuation'
    s = s.replace(/[\.\,\"\'\?\!\;\:\#\$\%\&\(\)\*\+\-\/\<\>\=\@\[\]\\^\_\{\}\|\~]/g, "");

    // replace multiple whitespace with single whitespace
    s = s.replace(/\s{2,}/g, " ");

    // trim whitespace at start and end of title
    return s.replace(/^\s+|\s+$/g, "");
}

$(document).ready(function() {
    // RPC ping
    $("#rpc .col3").click(function() {
        var a = $(this).attr("title");
        a != "" && alert(a);
        return !1
    });

    // ogp_image setting
    $("#metatags-manage .selectimg").click(function() {
        var area = $(this).attr('data-name');
        var path = "";
        var currentpath = "images";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    // Lọc tên của metatag
    $('[name^=metaGroupsValue]').on('input', function() {
        $(this).val($(this).val().replace(/[^a-zA-Z0-9-_.:]+/g, ''));
    });

    // Lọc số
    $('.number').on('input', function() {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    // Các meta dựng sẵn
    $('#metatags-manage').on('show.bs.dropdown', '.metaGroupsValue-dropdown', function () {
        var item = $(this).parents('.item'),
            metaGroupsName = $('[name^=metaGroupsName]', item).val(),
            id = (metaGroupsName == 'name') ? 'meta-name-list' : (metaGroupsName == 'property' ? 'meta-property-list' : 'meta-http-equiv-list');
        $('.metaGroupsValue-opt', this).html($('#' + id).html())
    });

    //
    $('#metatags-manage').on('click', '.groupvalue', function(e) {
        e.preventDefault();
        var item = $(this).parents('.item');
        $('[name^=metaGroupsValue]', item).val($(this).text())
    });

    // Thêm dòng meta-tag
    $('#metatags-manage').on('click', '.add-meta-tag', function() {
        var item = $(this).parents('.item'),
            newitem = item.clone();
        $('[name^=metaGroupsName] option:selected', newitem).prop('selected', false);
        $('[name^=metaGroupsValue], [name^=metaContents]', newitem).val('');
        $('.metaGroupsValue-opt', newitem).text('');
        item.after(newitem)
    });
    // Xóa dòng meta-tag
    $('#metatags-manage').on('click', '.del-meta-tag', function() {
        var items = $(this).parents('.items'),
            item = $(this).parents('.item');
        if ($('.item', items).length > 1) {
            item.remove()
        } else {
            $('[name^=metaGroupsName] option:selected', item).prop('selected', false);
            $('[name^=metaGroupsValue], [name^=metaContents]', item).val('');
            $('.metaGroupsValue-opt', item).text('');
        }
    });
    // Các gias trị của hệ thống
    $('#metatags-manage').on('click', '.metacontent', function(e) {
        e.preventDefault();
        var item = $(this).parents('.item'),
            val = $('[name^=metaContents]', item).val() + $(this).text();
            $('[name^=metaContents]', item).val(val)
    });
});
