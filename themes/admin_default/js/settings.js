/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_is_del_cron(cronid, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.get(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cronjobs_del&id=" + cronid + "&checkss=" + checkss + "&nocache=" + new Date().getTime(), function(res) {
            if (res == 1) {
                alert(nv_is_del_confirm[1]);
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function dkim_list_load() {
    $.ajax({
        type: 'POST',
        cache: !1,
        url: $('#dkimaddForm').attr('action'),
        data: 'dkimlist=1',
        success: function(data) {
            $('#dkim_list').html(data);
            $('#dkim-sector').attr('data-loaded', 'true');
        }
    })
}

function cert_list_load() {
    $.ajax({
        type: 'POST',
        cache: !1,
        url: $('#certAddForm').attr('action'),
        data: 'certlist=1',
        success: function(data) {
            $('#cert_list').html(data);
            $('#cert-sector').attr('data-loaded', 'true');
        }
    })
}

function ip_list_load(url, type) {
    $.ajax({
        type: 'GET',
        cache: !1,
        url: url,
        success: function(data) {
            if (type) {
                $('#noflips').html(data)
            } else {
                $('#banips').html(data)
            }
        }
    })
}

function country_cdn_list_load() {
    $.ajax({
        type: 'GET',
        cache: !1,
        url: $('#country-cdn-sector').data('url'),
        success: function(data) {
            $('#country-cdn-sector').attr('data-loaded', 'true');
            $('#country-cdn-sector').html(data)
        }
    })
}

function any_origin_check() {
    if ($('[name=any_origin]').is(':checked')) {
        $('[name=cors_origins]').prop('readonly', true)
    } else {
        $('[name=cors_origins]').prop('readonly', false)
    }
}

$(document).ready(function() {
    // General
    $('[data-toggle="popover"]').popover();

    // Custom values
    $('body').on('click', '.add-item', function() {
        var item = $(this).parents('.item'),
            new_item = item.clone();
        $('input[type=text]', new_item).val('');
        item.after(new_item)
    });

    $('body').on('click', '.del-item', function() {
        var item = $(this).parents('.item'),
            list = $(this).parents('.list');
        if ($('.item', list).length > 1) {
            item.remove()
        } else {
            $('input[type=text]', item).val('')
        }
    });

    // CDN
    $('#country-cdn-sector').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            country_cdn_list_load()
        }
    });

    $('body').on('change', '[name^=ccdn]', function(e) {
        e.preventDefault();
        if ($(this).val() != '') {
            $(this).parents('.country').addClass('c-selected')
        } else {
            $(this).parents('.country').removeClass('c-selected')
        }
        $(this).parents('form').submit()
    });

    $('body').on('click', '[data-toggle=add_cdn]', function(e) {
        e.preventDefault();
        var cdnlist = $(this).parents('.cdn-list'),
            item = $(this).parents('.item'),
            newitem = item.clone();
        $('[name^=cdn_url], [name^=cdn_countries]', newitem).val('');
        $('[name^=cdn_is_default]', newitem).val('0');
        $('[data-toggle=cdn_default]', newitem).prop('checked', false);
        newitem.appendTo(cdnlist)
    });
    $('body').on('click', '[data-toggle=remove_cdn]', function(e) {
        e.preventDefault();
        var cdnlist = $(this).parents('.cdn-list'),
            item = $(this).parents('.item');
        if ($('.item', cdnlist).length > 1) {
            item.remove()
        } else {
            $('[name^=cdn_url], [name^=cdn_countries]', item).val('');
            $('[name^=cdn_is_default]', item).val('0');
            $('[data-toggle=cdn_default]', item).prop('checked', false)
        }
    });
    $('body').on('change', '[data-toggle=cdn_default]', function(e) {
        var item = $(this).parents('.item');
        if ($(this).is(':checked')) {
            $('[name^=cdn_is_default]', item).val('1');
            $('[data-toggle=cdn_default]', item.siblings()).prop('checked', false);
            $('[name^=cdn_is_default]', item.siblings()).val('0');
        } else {
            $('[name^=cdn_is_default]', item).val('0');
        }
    });

    // Ssettings
    $('[data-toggle=view_sconfig_file]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'getSconfigContents=1',
            dataType: "html",
            success: function(b) {
                $('#sConfigModal .modal-body>pre>code').text(b);
                $('#sConfigModal').modal('show')
            }
        })
    });
    $('#sample-form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            data = $(this).serialize(),
            rewrite_supporter = $('[name=rewrite_supporter]', this).val(),
            lang = $('option[value=' + rewrite_supporter + ']', this).data('highlight-lang');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "html",
            success: function(b) {
                $('#sDefaultModal .modal-body>pre>code').removeAttr('class').addClass(lang).text(b);
                $('#sDefaultModal').modal('show')
            }
        })
    });
    $('#sConfigModal, #sDefaultModal').on('show.bs.modal', function(e) {
        hljs.debugMode();
        hljs.highlightAll();
    });

    if ($('#ssetings-form [name=any_origin]').length) {
        $('[name=any_origin]').on('change', function(e) {
            any_origin_check()
        });
        any_origin_check()
    }

    // System
    if ($("#site_timezone").length) {
        $("#site_timezone").select2()
    }

    if ($("#reopening_date").length) {
        $("#reopening_date").datepicker({
            showOn: "focus",
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        });
    }

    $('#system-settings [data-toggle=phone_note]').on('click', function() {
        modalShow($(this).attr('title'), $('#system-settings .phone_note_content').html());
        return !1;
    });

    $('#change-site-mode [name=closed_site]').on('change', function() {
        if ($(this).val() != '0') {
            $("#reopening_time").slideDown()
        } else {
            $("#reopening_time").slideUp()
        }
    });

    $('[data-toggle="controlrw1"]').change(function() {
        var rewrite_optional = $(this).is(':checked');
        if (rewrite_optional) {
            $('#tr_rewrite_op_mod').slideDown();
        } else {
            $('#tr_rewrite_op_mod').slideUp();
            $('[name="rewrite_op_mod"]').find('option').prop('selected', false);
        }
    });
    $('[data-toggle="controlrw"]').change(function() {
        var lang_multi = $('[name="lang_multi"]').is(':checked');
        var rewrite_enable = $('[name="rewrite_enable"]').is(':checked');
        if ($('#lang-geo').length) {
            if (lang_multi) {
                $('#lang-geo').slideDown();
            } else {
                $('#lang-geo').slideUp();
            }
        }
        if (!lang_multi && rewrite_enable) {
            $('#tr_rewrite_optional').slideDown();
        } else {
            $('#tr_rewrite_optional').slideUp();
            $('[name="rewrite_optional"]').prop('checked', false);
        }
        $('[data-toggle="controlrw1"]').change();
    });

    // Smtp
    $('body').on('click', '[data-toggle=dkim_read]', function() {
        var domain = $(this).data('domain');
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#dkimaddForm').attr('action'),
            data: {
                'dkimread': 1,
                'domain': domain
            },
            success: function(data) {
                $('#sign-read .modal-title').text(domain);
                $('#sign-read .modal-body').html(data);
                $('#sign-read').modal('show')
            }
        })
    });

    $('body').on('click', '[data-toggle=cert_read]', function() {
        var email = $(this).data('email');
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#certAddForm').attr('action'),
            data: {
                'smimeread': 1,
                'email': email
            },
            success: function(data) {
                $('#sign-read .modal-title').text(email);
                $('#sign-read .modal-body').html(data);
                $('#sign-read').modal('show')
            }
        })
    });

    $('body').on('click', '[data-toggle=smimedownload]', function() {
        var form = $(this).parents('form'),
            passphrase = prompt(form.data('prompt'), "");
        if (passphrase != null && passphrase != '') {
            $('[name=passphrase]', form).val(passphrase);
            form.trigger('submit')
        }
    });

    $('body').on('click', '[data-toggle=dkimdel]', function() {
        var item = $(this).parents('.item');
        if (confirm(item.data('confirm')) == true) {
            $.ajax({
                type: 'POST',
                cache: false,
                url: $('#dkimaddForm').attr('action'),
                data: {
                    'dkimdel': 1,
                    'domain': item.data('domain')
                },
                success: function() {
                    dkim_list_load();
                    $('[data-dismiss=modal]', item).trigger('click')
                }
            })
        }
    });

    $('body').on('click', '[data-toggle=smimedel]', function() {
        var form = $(this).parents('form');
        if (confirm(form.data('confirm')) == true) {
            $.ajax({
                type: 'POST',
                cache: false,
                url: form.attr('action'),
                data: {
                    'smimedel': 1,
                    'email': form.data('email')
                },
                success: function() {
                    cert_list_load();
                    $('[data-dismiss=modal]', form).trigger('click')
                }
            })
        }
    });

    $('body').on('click', '[data-toggle=dkimverify]', function() {
        var that = $(this),
            item = that.parents('.item');
        $('.ic', that).hide();
        $('.load', that).show();
        that.prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#dkimaddForm').attr('action'),
            data: {
                'dkimverify': 1,
                'domain': item.data('domain')
            },
            dataType: "json",
            success: function(a) {
                that.prop('disabled', false);
                $('.load', that).hide();
                $('.ic', that).hide();
                alert(a.mess);
                if ('OK' == a.status) {
                    dkim_list_load();
                    $('[data-dismiss=modal]', item).trigger('click')
                }
            }
        })
    });

    $('#dkim-sector').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            dkim_list_load()
        }
    });

    $('#cert-sector').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            cert_list_load()
        }
    });

    $("#dkimaddForm").on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            domain = $('[name=domain]', that).val();
        if ('' == domain) {
            $('[name=domain]', that).focus();
            return !1
        }
        var data = that.serialize();
        $('input, button', that).prop('disabled', true);
        $.ajax({
            url: that.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button', that).prop('disabled', false);
            $('[name=domain]', that).val('');
            if ('error' == a.status) {
                alert(a.mess);
            } else if ('OK' == a.status) {
                dkim_list_load();
                var myLdBtn = setInterval(function() {
                    if ($('#dkim_list [data-toggle="dkim_read"][data-domain="' + domain + '"]').length) {
                        clearInterval(myLdBtn);
                        $('#dkim_list [data-toggle="dkim_read"][data-domain="' + domain + '"]').trigger('click')
                    }
                }, 500)
            }
        });
    });

    $('#certAddForm [name=pkcs12]').on('change', function() {
        if ('' != $(this).val()) {
            var form = $(this).parents('form'),
                passphrase = prompt(form.data('prompt'), ""),
                data;
            if (passphrase != null && passphrase != '') {
                $('[name=passphrase]', form).val(passphrase);
                form.trigger('submit')
            } else {
                $(this).val('')
            }
        }
    })

    $("#certAddForm").on('submit', function(e) {
        e.preventDefault();
        var data = new FormData(this),
            th = $(this);
        if ('' == $('[name=pkcs12]', th).val()) {
            return !1
        }
        $('input, button', th).prop('disabled', true);
        $.ajax({
            url: th.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button', th).prop('disabled', false);
            if ('error' == a.status) {
                alert(a.mess);
            } else if ('overwrite' == a.status) {
                if (confirm(a.mess) == true) {
                    $('[name=overwrite]', th).val('1');
                    th.submit()
                }
            } else {
                $('[type=file]', th).val('');
                $('[name=overwrite]', th).val('0');
                cert_list_load()
            }
        })
    });

    $('#certOtherAddForm').on('submit', function(e) {
        e.preventDefault();
        var th = $(this),
            data = th.serialize();
        $('input, button, textarea', th).prop('disabled', true);
        $.ajax({
            url: th.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button, textarea', th).prop('disabled', false);
            if ('error' == a.status) {
                alert(a.mess);
            } else if ('overwrite' == a.status) {
                if (confirm(a.mess) == true) {
                    $('[name=overwrite]', th).val('1');
                    th.submit()
                }
            } else {
                $('textarea', th).val('');
                $('[name=overwrite]', th).val('0');
                th.slideUp();
                cert_list_load()
            }
        })
    });

    $('[data-toggle=cert_other_add_show]').on('click', function() {
        if ($('#certOtherAddForm').is(':visible')) {
            $('#certOtherAddForm').slideUp()
        } else {
            $('#certOtherAddForm').slideDown()
        }
    });

    $('#sendmail-settings [data-toggle=smtp_test]').on('click', function() {
        var that = $('#sendmail-settings'),
            url = that.attr('action'),
            checkss = $('[name=checkss]', that).val(),
            data = that.serialize();
        $('input,button,textarea', that).prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                if (result.status == 'error') {
                    $('input,button,textarea', that).prop('disabled', false);
                    alert(result.mess);
                    if (result.input) {
                        if ($('[name^=' + result.input + ']', that).length) {
                            $('[name^=' + result.input + ']', that).focus()
                        }
                    }
                } else if (result.status == 'OK') {
                    $.ajax({
                        type: 'POST',
                        cache: !1,
                        url: url,
                        data: 'submittest=1&checkss=' + checkss,
                        timeout: 3E4
                    }).done(function(e) {
                        alert(e);
                        $('input,button,textarea', that).prop('disabled', false)
                    }).fail(function(jqXHR, textStatus) {
                        if (textStatus === 'timeout') {
                            $('input,button,textarea', that).prop('disabled', false);
                            alert('Failed from timeout')
                        }
                    });
                }
            }
        })
    });

    $('#sendmail-settings [data-toggle=form_reset]').on('click', function() {
        $('#sendmail-settings')[0].reset();
        $('#sendmail-settings [name=mailer_mode][value=' + $('#sendmail-settings').data('mailer-mode-default') + ']').trigger('change')
    });

    $("#sendmail-settings [name=mailer_mode]").on('change', function() {
        var type = $(this).val();
        if (type == "smtp") {
            $("#sendmail-settings .smtp").show();
        } else {
            $("#sendmail-settings .smtp").hide();
        }
    });

    // Add/edit/delete IP
    $('body').on('change', '.ip-version', function() {
        var form = $(this).parents('form');
        if ($(this).val() == '4') {
            $(".ip-mask [data-version='4']", form).prop('disabled', false).removeAttr('style');
            $(".ip-mask [data-version='6']", form).prop('disabled', true).prop('selected', false).css({
                'display': 'none'
            })
        } else {
            $(".ip-mask [data-version='6']", form).prop('disabled', false).removeAttr('style');
            $(".ip-mask [data-version='4']", form).prop('disabled', true).prop('selected', false).css({
                'display': 'none'
            })
        }
    });

    $('body').on('submit', '.ip-action', function(e) {
        e.preventDefault();
        var that = $(this),
            data = that.serialize();
        $('input,button,textarea,select', that).prop('disabled', true);
        $.ajax({
            url: that.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                alert(a.mess);
                $('input,button,textarea,select', that).prop('disabled', false);
            } else if (a.status == 'OK') {
                ip_list_load(a.url, a.type);
                $('#page-tool').modal('hide')
            }
        });
    });

    $('body').on('click', '[data-toggle=edit_ip]', function() {
        var that = $(this),
            list = that.parents('.list');
        $.ajax({
            type: 'GET',
            cache: !1,
            url: list.data('url') + '&id=' + that.data('id'),
            success: function(result) {
                $('#page-tool .modal-title').text(that.attr('title'));
                $('#page-tool .modal-body').html(result);
                $('#page-tool').modal('show')
            }
        })
    });

    $('body').on('click', '[data-toggle=del_ip]', function() {
        var that = $(this),
            list = that.parents('.list');
        if (confirm(list.data('confirm'))) {
            $.ajax({
                type: 'POST',
                cache: !1,
                url: list.data('del-url'),
                data: '&id=' + that.data('id') + '&checkss=' + list.data('checkss'),
                dataType: "json",
                success: function(a) {
                    ip_list_load(a.url, a.type)
                }
            })
        }
    });

    $('body').on('click', '[data-toggle=add_ip]', function() {
        var that = $(this),
            list = that.parents('.list');
        $.ajax({
            type: 'GET',
            cache: !1,
            url: list.data('url'),
            success: function(result) {
                $('#page-tool .modal-title').text(that.attr('title'));
                $('#page-tool .modal-body').html(result);
                $('#page-tool').modal('show')
            }
        })
    });



    // Security
    $('#settingTabs [data-toggle=pill]').on('show.bs.tab', function() {
        $('#settingSelect').val($(this).attr('aria-controls'));
        if ($(this).is('[data-loaded]')) {
            if ($(this).attr('data-loaded') == 'false') {
                $(this).attr('data-loaded', 'true');
                ip_list_load($(this).data('load-url'), $(this).data('type'))
            }
        }
    }).on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });

    if ($('[name=gselectedtab]').length) {
        var gselectedtab = $('[name=gselectedtab]').val();
        if (gselectedtab == '1' || gselectedtab == '3') {
            if ($('[aria-offsets=' + gselectedtab + ']').attr('data-loaded') == 'false') {
                $('[aria-offsets=' + gselectedtab + ']').attr('data-loaded', 'true');
                ip_list_load($('[aria-offsets=' + gselectedtab + ']').data('load-url'), $('[aria-offsets=' + gselectedtab + ']').data('type'))
            }
        }
    }

    $('#settingSelect').on('change', function() {
        $('#settingTabs [aria-controls=' + $(this).val() + ']').trigger('click')
    });

    $('#secForm').on('change', '.parameter', function() {
        var parameters = '';
        $('#secForm .parameter:checked').each(function() {
            parameters += $(this).val() + ','
        });
        if ('' != parameters) {
            parameters = parameters.substr(0, parameters.length - 1)
        }
        $('#secForm [name^=parameters]').val(parameters)
    });

    $('#secForm').on('click', '.add-variable', function() {
        var item = $(this).parents('.item'),
            new_item = item.clone();
        $('[name^=parameters], [name^=end_url_variables]', new_item).val('');
        $('.parameter', new_item).prop('checked', false);
        item.after(new_item)
    });

    $('#secForm').on('click', '.del-variable', function() {
        var item = $(this).parents('.item'),
            list = $(this).parents('.list');
        if ($('.item', list).length > 1) {
            item.remove()
        } else {
            $('[name^=parameters], [name^=end_url_variables]', item).val('');
            $('.parameter', item).prop('checked', false);
        }
    });

    $('[data-toggle=selAllAs]').on('click', function() {
        var form = $(this).parents('form');
        $('select', form).val($(this).data('type')).trigger('change')
    });

    $('[data-toggle=selAllCaptComm]').on('click', function() {
        var form = $(this).parents('form'),
            val = $(this).val();
        if (val != '-1') {
            $('[name^=captcha_area_comm]', form).val(val);
            $(this).val('-1')
        }
    });

    $('#captcha-general-settings [name=recaptcha_sitekey], #captcha-general-settings [name=recaptcha_secretkey]').on('change', function() {
        $('#modcapt-settings select').trigger('change')
    });

    $('[name^=captcha_type]').on('change', function() {
        var form = $('#captcha-general-settings'),
            val = $(this).val(),
            sitekey = $('[name=recaptcha_sitekey]', form).val(),
            secretkey = $('[name=recaptcha_secretkey]', form).val();
        if (val != 'recaptcha' || (val == 'recaptcha' && sitekey != '' && secretkey != '')) {
            $(this).next().slideUp()
        } else {
            $(this).next().slideDown()
        }
    });

    $('#cors-settings [name=crosssite_restrict], #cors-settings [name=crossadmin_restrict]').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).parents('.item').find('.collapse').collapse('show')
        } else {
            $(this).parents('.item').find('.collapse').collapse('hide')
        }
    });

    $('#csp-settings [name=nv_csp_act], #rp-settings [name=nv_rp_act]').on('change', function() {
        if ($(this).is(':checked')) {
            $($(this).data('target')).collapse('show')
        } else {
            $($(this).data('target')).collapse('hide')
        }
    });

    $('#csp-settings [data-toggle=none]').on('click', function() {
        if ($(this).is(':checked')) {
            var conf = confirm($(this).parents('form').data('confirm'));
            if (conf == true) {
                $('[name^=directives]', $(this).parents('.directive')).not('[data-toggle=none]').prop('disabled', true)
            } else {
                $(this).prop('checked', false)
            }
        } else {
            $('[name^=directives]', $(this).parents('.directive')).prop('disabled', false)
        }
    });

    if ($.fn.datepicker) {
        $(".datepicker").datepicker({
            showOn: "focus",
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        });
    }

    // Site setting
    $('#site-settings [name^=theme_type]').on('change', function() {
        var form = $(this).parents('form'),
            types = [];
        $('[name^=theme_type]:checked').each(function() {
            types.push($(this).val());
        });
        if ($.inArray('m', types) !== -1) {
            $('.mobile_theme-wrap, .switch_mobi_des-wrap', form).slideDown()
        } else {
            $('.mobile_theme-wrap, .switch_mobi_des-wrap', form).slideUp()
        }
        if ($.inArray('r', types) === -1 && $.inArray('d', types) === -1) {
            $('[name^=theme_type][value=r]', form).prop('checked', true)
        }
    });

    // FTP setting
    $('#autodetectftp').on('click', function() {
        var that = $(this),
            form = that.parents('form'),
            ftp_server = $('input[name="ftp_server"]', form).val(),
            ftp_user_name = $('input[name="ftp_user_name"]', form).val(),
            ftp_user_pass = $('input[name="ftp_user_pass"]', form).val(),
            ftp_port = $('input[name="ftp_port"]', form).val();
        if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
            alert(form.data('error'));
            return !1;
        }
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: {
                'ftp_server': ftp_server,
                'ftp_port': ftp_port,
                'ftp_user_name': ftp_user_name,
                'ftp_user_pass': ftp_user_pass,
                'autodetect': 1
            },
            dataType: "json",
            success: function(c) {
                that.prop('disabled', false);
                if ('error' == c.status) {
                    alert(c.mess)
                } else if('OK' == c.status) {
                    $('#ftp_path').val(c.mess);
                }
            }
        });
    });

    $('#ssl_https').change(function() {
        var val = parseInt($(this).data('val')),
            mode = parseInt($(this).val());
        if (mode != 0 && val == 0 && !confirm($(this).data('confirm'))) {
            $(this).val('0');
            return;
        }
    });

    // formSearchPlugin Submit
    $('#formSearchPlugin [name=a]').on('change', function() {
        $('#formSearchPlugin').submit()
    })

    // nv_change_plugin_weight
    $('[data-toggle=change_plugin_weight]').on('change', function(e) {
        e.preventDefault();
        var pid = $(this).data('pid');
        var new_weight = $(this).val();
        nv_settimeout_disable($(this).attr('id'), 3000);
        $.post(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'changeweight=1&pid=' + pid + '&new_weight=' + new_weight, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            location.reload();
        });
    });

    // nv_del_plugin
    $('[data-toggle=nv_del_plugin]').on('click', function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'del=1&pid=' + $(this).data('pid'), function(res) {
                location.reload();
            })
        }
    });

    // Tích hợp plugin mới
    var mdPCfg = $('#mdPluginConfig');
    $('[data-click="plintegrate"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var icon = $('.fa', $this);
        if ($('[data-click="plintegrate"] .fa-spin').length > 0) {
            return;
        }
        icon.addClass('fa-spin');
        // Trường hợp là plugin thuần hệ thống
        if ($this.data('hm') == '' && $this.data('rm') == '') {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
                data: {
                    integrate: 1,
                    hook_key: $this.data('hkey'),
                    file_key: $this.data('fkey')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spin');
                    if (respon.message == '') {
                        location.reload();
                        return;
                    }
                    alert(respon.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    icon.removeClass('fa-spin');
                    console.log(jqXHR, textStatus, errorThrown);
                    alert('Request Error!!!');
                }
            });
            return;
        }
        // Trường hợp là plugin trao đổi dữ liệu module => Gọi form tích hợp
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
            data: {
                loadform: 1,
                hook_key: $this.data('hkey'),
                file_key: $this.data('fkey')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spin');
                if (respon.message != '') {
                    alert(respon.message);
                    return;
                }
                window.nv_plugin_data = respon;

                var opts, show;

                mdPCfg.data('hook_key', $this.data('hkey'));
                mdPCfg.data('file_key', $this.data('fkey'));
                $('[data-area="title"]', mdPCfg).html(respon.tag);

                // Xác định module nguồn còn khả dụng
                opts = '';
                show = 0;
                if (respon.hook_mod != '' && respon.hook_mods.length > 0) {
                    for (var i = 0; i < respon.hook_mods.length; i++) {
                        var avail = 1;
                        for (var j = 0; j < respon.exists.length; j++) {
                            if (respon.exists[j].hook_mod == respon.hook_mods[i].key && respon.exists[j].receive_mods.length >= respon.receive_mods.length) {
                                avail = 0;
                            }
                        }
                        if (avail) {
                            opts += '<option value="' + respon.hook_mods[i].key + '">' + respon.hook_mods[i].title + '</option>';
                            show = 1;
                        }
                    }
                }
                $('[name="hook_module"]', mdPCfg).html(opts);
                if (show) {
                    $('[data-area="hook_module"]', mdPCfg).removeClass('hidden');
                } else {
                    $('[data-area="hook_module"]', mdPCfg).addClass('hidden');
                }

                // Gọi event change module nguồn để load ra module đích
                $('[name="hook_module"]', mdPCfg).trigger('change');

                mdPCfg.modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                icon.removeClass('fa-spin');
                console.log(jqXHR, textStatus, errorThrown);
                alert('Request Error!!!');
            }
        });
    });

    // Xử lý load module đích sau khi chọn module nguồn
    $('[name="hook_module"]', mdPCfg).on('change', function(e) {
        e.preventDefault();

        // Xác định module đích còn khả dụng
        var opts = ''
        var show = 0;
        var hook_mod = '';
        if (!$('[data-area="hook_module"]', mdPCfg).is('.hidden')) {
            hook_mod = $('[name="hook_module"]', mdPCfg).val();
        }

        if (nv_plugin_data.receive_mod != '' && nv_plugin_data.receive_mods.length > 0) {
            for (var i = 0; i < nv_plugin_data.receive_mods.length; i++) {
                var avail = 1;
                for (var j = 0; j < nv_plugin_data.exists.length; j++) {
                    if (nv_plugin_data.exists[j].hook_mod == hook_mod && $.inArray(nv_plugin_data.receive_mods[i].key, nv_plugin_data.exists[j].receive_mods) > -1) {
                        avail = 0;
                    }
                }
                if (avail) {
                    opts += '<option value="' + nv_plugin_data.receive_mods[i].key + '">' + nv_plugin_data.receive_mods[i].title + '</option>';
                    show = 1;
                }
            }
        }
        $('[name="receive_module"]', mdPCfg).html(opts);
        if (show) {
            $('[data-area="receive_module"]', mdPCfg).removeClass('hidden');
        } else {
            $('[data-area="receive_module"]', mdPCfg).addClass('hidden');
        }
    });

    // Tích hợp plugin trao đổi dữ liệu module
    $('[data-toggle="submitIntegratePlugin"]').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);

        btn.prop('disable', true);

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
            data: {
                integrate: 1,
                hook_key: mdPCfg.data('hook_key'),
                file_key: mdPCfg.data('file_key'),
                hook_module: $('[name="hook_module"]', mdPCfg).val(),
                receive_module: $('[name="receive_module"]', mdPCfg).val()
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                btn.prop('disable', false);
                if (respon.message == '') {
                    location.reload();
                    return;
                }
                alert(respon.message);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                btn.prop('disable', false);
                console.log(jqXHR, textStatus, errorThrown);
                alert('Request Error!!!');
            }
        });
    });

    $('[data-toggle=seccode_create]').on('click', function() {
        $($(this).data('target')).val(nv_randomPassword(32))
    });

    $('[data-toggle=seccode_remove]').on('click', function() {
        $($(this).data('target')).val('')
    });

    if ($('[data-toggle=clipboard]').length && ClipboardJS) {
        var clipboard = new ClipboardJS('[data-toggle=clipboard]');
        clipboard.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });
    }
});
