/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) {
        return map[m];
    });
}

$(function() {
    $('[data-toggle=activelang]').on('change', function() {
        var that = $(this),
            url = that.val();
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: url,
            success: function(result) {
                location.reload()
            }
        })
    });

    $('[data-toggle=setup_delete]').on('click', function() {
        if (confirm(nv_is_del_confirm[0])) {
            var that = $(this),
                url = that.data('url');
            that.prop('disabled', true);
            $.ajax({
                type: 'GET',
                cache: !1,
                url: url,
                success: function(result) {
                    location.reload()
                }
            })
        }
    });

    $('[data-toggle=change_weight]').on('change', function() {
        var that = $(this),
            new_weight = that.val(),
            keylang = that.data('keylang');
        that.prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            data: 'changeweight=1&keylang=' + keylang + '&new_weight=' + new_weight,
            dataType: "json",
            success: function(result) {
                if (result.status == 'error') {
                    alert(result.mess);
                    that.prop('disabled', false);
                } else if (result.status == 'OK') {
                    location.reload()
                }
            }
        })
    });

    $('[data-toggle=setup_new]').on('click', function() {
        var that = $(this),
            url = that.data('url');
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: url,
            dataType: "json",
            success: function(result) {
                alert(result.mess);
                if (result.status == 'error') {
                    that.prop('disabled', false);
                } else if (result.status == 'OK') {
                    window.location.href = result.redirect
                }
            }
        })
    });

    $('.del-item').on('change', function() {
        var item = $(this).parents('.item');
        if ($(this).is(':checked')) {
            $('[type=text]', item).prop('readonly', true);
            $('[name^=isdel]', item).val('1')
        } else {
            $('[type=text]', item).prop('readonly', false);
            $('[name^=isdel]', item).val('0')
        }
    });

    $('body').on('click', '.add-new', function() {
        var item = $(this).parents('.item'),
            newitem = item.clone();
        $('[name^=langid], [name^=isdel]', newitem).val('0');
        $('[name^=langkey], [name^=langvalue]', newitem).prop('readonly', false).attr('value', '').val('');
        $('.has-error', newitem).removeClass('has-error');
        $('.delitem', newitem).remove();
        $('.del-new', newitem).show();
        item.after(newitem)
    });

    $('body').on('click', '.del-new', function() {
        $(this).parents('.item').remove()
    });

    $('body').on('change', '[name^=langkey]', function() {
        var that = $(this),
            val = that.val(),
            item = that.parents('.item');
        if (val == '') {
            $('.invalid-feedback', item).text(that.data('empty-error'));
            that.parent().addClass('has-error')
        } else {
            that.parent().removeClass('has-error');
            item.siblings().each(function() {
                if ($('[name^=langkey]', this).val() == val) {
                    $('.invalid-feedback', item).text(that.data('duplicate-error'));
                    that.parent().addClass('has-error');
                    return !1
                }
            })
        }
    });

    $('body').on('change', '[name^=langvalue]', function() {
        var val = trim($(this).val());
        $(this).val(val);
        if (val == '') {
            $(this).parent().addClass('has-error')
        } else {
            $(this).parent().removeClass('has-error')
        }
    });

    if ($("#sortable").length) {
        $("#sortable").sortable().disableSelection()
    };

    $('#lang-edit-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $('.has-error', form).removeClass('has-error');
        var keys = [],
            values = [],
            ids = [],
            isdels = [],
            pozauthor = {},
            obj, par, langkey, langvalue, langid, langisdel,
            isError = false;
        $('.item', form).each(function() {
            obj = $('[name^=langkey]', this);
            par = obj.parent();
            langkey = obj.val();
            if (langkey == '') {
                $('.invalid-feedback', par).text(obj.data('empty-error'));
                par.addClass('has-error');
                isError = true;
                obj.focus();
                return !1
            }
            if (keys.indexOf(langkey) != -1) {
                $('.invalid-feedback', par).text(obj.data('duplicate-error'));
                par.addClass('has-error');
                isError = true;
                obj.focus();
                return !1
            } else {
                keys.push(langkey)
            }

            values.push(escapeHtml(trim($('[name^=langvalue]', this).val())))
            ids.push(parseInt($('[name^=langid]', this).val()));
            isdels.push(parseInt($('[name^=isdel]', this).val()));
        });

        if (!isError) {
            $('[name^=pozauthor]', form).each(function() {
                pozauthor[$(this).data('key')] = escapeHtml(trim($(this).val()))
            });

            var url = form.attr('action') + '&savedata=' + $('[name=savedata]', form).val(),
                jsn = JSON.stringify({
                    'pozauthor': pozauthor,
                    'keys': keys,
                    'values': values,
                    'ids': ids,
                    'isdels': isdels
                });
            if ($('[name=write]', form).length && $('[name=write]', form).is(':checked')) {
                url += '&write=1'
            }
            $('input,button', this).prop('disabled', true);
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: jsn,
                contentType: 'application/json;charset=UTF-8',
                dataType: "json",
                success: function(result) {
                    if (result.mess) {
                        alert(result.mess)
                    }
                    if (result.status == 'error') {
                        $('input,button', this).prop('disabled', false);
                    } else if (result.status == 'OK') {
                        window.location.href = result.redirect
                    }
                }
            })
        }
    });

    $('[data-toggle=lang_export]').on('click', function() {
        var that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: that.data('url'),
            dataType: "json",
            success: function(result) {
                if (result.mess) {
                    alert(result.mess)
                }
                that.prop('disabled', false)
            }
        })
    });

    $('#check-lang [name=typelang]').on('change', function() {
        var lang = $(this).val(),
            sourcelang = $('#check-lang [name=sourcelang]').val();
        if (lang == '' || lang == sourcelang) {
            $('#check-lang [type=submit]').prop('disabled', true)
        } else {
            $('#check-lang [type=submit]').prop('disabled', false)
        }
    });

    $('#check-lang [name=sourcelang]').on('change', function() {
        var sourcelang = $(this).val(),
            lang = $('#check-lang [name=typelang]').val();
        $('#check-lang [name=typelang] option').prop('disabled', false);
        $('#check-lang [name=typelang] option[value=' + sourcelang + ']').prop('disabled', true);
        if (lang == sourcelang) {
            $('#check-lang [name=typelang]').val('')
        }
    });

    $('[data-toggle=lang_setting_form]').on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            data = that.serialize();
        $('input', that).prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: that.attr('action'),
            data: data,
            success: function(result) {
                alert(result);
                $('input', that).prop('disabled', false)
            }
        })
    });

    $('.read-lang').on('click', function() {
        var that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: that.data('url') + '&nocache=' + (new Date).getTime(),
            success: function(result) {
                alert(result);
                location.reload()
            }
        })
    });

    $('.write-lang').on('click', function() {
        var that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: that.data('url') + '&nocache=' + (new Date).getTime(),
            success: function(result) {
                alert(result);
                that.prop('disabled', false)
            }
        })
    });

    $('.delete-lang').on('click', function() {
        var that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: 'GET',
            cache: !1,
            url: that.data('url') + '&nocache=' + (new Date).getTime(),
            success: function(result) {
                alert(result);
                location.reload()
            }
        })
    });

    $('.delete-lang-files').on('click', function() {
        if (confirm(nv_is_del_confirm[0])) {
            var that = $(this);
            that.prop('disabled', true);
            $.ajax({
                type: 'GET',
                cache: !1,
                url: that.data('url') + '&nocache=' + (new Date).getTime(),
                dataType: "json",
                success: function(result) {
                    alert(result.mess);
                    location.reload()
                }
            })
        }
    })
})
