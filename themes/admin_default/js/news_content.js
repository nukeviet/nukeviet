/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var timer_check_takeover = 0;

function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}

function formatRepo(repo) {
    if (repo.loading) return repo.text;
    return repo.title;
}

function formatRepoSelection(repo) {
    return repo.title || repo.text;
}

function nv_add_element(idElment, key, value) {
    var span = $('<span/>')
        .attr('title', value)
        .addClass('uiToken removable')
        .text(value)
        .on('dblclick', function() {
            $(this).remove();
        });

    var input = $('<input/>')
        .attr('type', 'hidden')
        .attr('value', key)
        .attr('name', idElment + '[]')
        .attr('autocomplete', 'off');

    var a = $('<a/>')
        .attr('href', 'javascript:void(0);')
        .addClass('remove uiCloseButton uiCloseButtonSmall')
        .on('click', function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });

    span.append(input).append(a);
    $("#" + idElment).append(span);
    return false;
}

function nv_timer_check_takeover(id) {
    clearTimeout(timer_check_takeover);
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(), 'id=' + id + '&check_edit=1', function(res) {
        res = res.split("_");
        if (res[0] != 'OK') {
            // Thông báo bị chiếm quyền sửa
            alert(res[1]);
            $('.submit-post').remove();
        } else {
            timer_check_takeover = setTimeout(function() {
                nv_timer_check_takeover(id);
            }, 10000);
        }
    });
    return false;
}

$(document).ready(function() {
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

    $("input[name='catids[]']").click(function() {
        var catid = $("input:radio[name=catid]:checked").val();
        var radios_catid = $("input:radio[name=catid]");
        var catids = [];
        $("input[name='catids[]']").each(function() {
            if ($(this).prop('checked')) {
                $("#catright_" + $(this).val()).show();
                catids.push($(this).val());
            } else {
                $("#catright_" + $(this).val()).hide();
                if ($(this).val() == catid) {
                    radios_catid.filter("[value=" + catid + "]").prop("checked", false);
                }
            }
        });

        if (catids.length > 1) {
            for (i = 0; i < catids.length; i++) {
                $("#catright_" + catids[i]).show();
            };
            catid = parseInt($("input:radio[name=catid]:checked").val() + "");
            if (!catid) {
                radios_catid.filter("[value=" + catids[0] + "]").prop("checked", true);
            }
        }
    });
    $("#publ_date,#exp_date").datepicker({
        showOn: "both",
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        buttonImage: nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly: true
    });

    var cachetopic = {};
    $("#AjaxTopicText").autocomplete({
        minLength: 2,
        delay: 500,
        source: function(request, response) {
            var term = request.term;
            if (term in cachetopic) {
                response(cachetopic[term]);
                return;
            }
            $.getJSON(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=topicajax", request, function(data, status, xhr) {
                cachetopic[term] = data;
                response(data);
            });
        }
    });

    var cachesource = {};
    $("#AjaxSourceText").autocomplete({
        minLength: 2,
        delay: 500,
        source: function(request, response) {
            var term = request.term;
            if (term in cachesource) {
                response(cachesource[term]);
                return;
            }
            $.getJSON(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=sourceajax", request, function(data, status, xhr) {
                cachesource[term] = data;
                response(data);
            });
        }
    });

    // Keywords autocomplete
    $("#keywords-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }

        if (event.keyCode == 13) {
            var keywords_add = $("#keywords-search").val();
            keywords_add = trim(keywords_add);
            if (keywords_add != '') {
                nv_add_element('keywords', keywords_add, keywords_add);
                $(this).val('');
            }
            return false;
        }

    }).autocomplete({
        source: function(request, response) {
            $.getJSON(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tagsajax", {
                term: extractLast(request.term)
            }, response);
        },
        search: function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus: function() {
            //no action
        },
        select: function(event, ui) {
            // add placeholder to get the comma-and-space at the end
            if (event.keyCode != 13) {
                nv_add_element('keywords', ui.item.value, ui.item.value);
                $(this).val('');
            }
            return false;
        }
    });

    $("#keywords-search").blur(function() {
        // add placeholder to get the comma-and-space at the end
        var keywords_add = $("#keywords-search").val();
        keywords_add = trim(keywords_add);
        if (keywords_add != '') {
            nv_add_element('keywords', keywords_add, keywords_add);
            $(this).val('');
        }
        return false;
    });
    $("#keywords-search").bind("keyup", function(event) {
        var keywords_add = $("#keywords-search").val();
        if (keywords_add.search(',') > 0) {
            keywords_add = keywords_add.split(",");
            for (i = 0; i < keywords_add.length; i++) {
                var str_keyword = trim(keywords_add[i]);
                if (str_keyword != '') {
                    nv_add_element('keywords', str_keyword, str_keyword);
                }
            }
            $(this).val('');
        }
        return false;
    });
    // Keywords autocomplete end

    // Tags autocomplete
    $("#tags-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }

        if (event.keyCode == 13) {
            var tags_add = $("#tags-search").val();
            tags_add = trim(tags_add);
            if (tags_add != '') {
                nv_add_element('tags', tags_add, tags_add);
                $(this).val('');
            }
            return false;
        }

    }).autocomplete({
        source: function(request, response) {
            $.getJSON(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tagsajax", {
                term: extractLast(request.term)
            }, response);
        },
        search: function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus: function() {
            //no action
        },
        select: function(event, ui) {
            // add placeholder to get the comma-and-space at the end
            if (event.keyCode != 13) {
                nv_add_element('tags', ui.item.value, ui.item.value);
                $(this).val('');
            }
            return false;
        }
    });

    $("#tags-search").blur(function() {
        // add placeholder to get the comma-and-space at the end
        var tags_add = $("#tags-search").val();
        tags_add = trim(tags_add);
        if (tags_add != '') {
            nv_add_element('tags', tags_add, tags_add);
            $(this).val('');
        }
        return false;
    });
    $("#tags-search").bind("keyup", function(event) {
        var tags_add = $("#tags-search").val();
        if (tags_add.search(',') > 0) {
            tags_add = tags_add.split(",");
            for (i = 0; i < tags_add.length; i++) {
                var str_keyword = trim(tags_add[i]);
                if (str_keyword != '') {
                    nv_add_element('tags', str_keyword, str_keyword);
                }
            }
            $(this).val('');
        }
        return false;
    });
    // Tags autocomplete end

    // internal_authors autocomplete
    $("#author-search").bind("keydown", function(event) {
        if ((event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) || event.keyCode == 13) {
            event.preventDefault();
        }
    }).autocomplete({
        source: function(request, response) {
            var aIDs = $("[name^=internal_authors]").map(function() {
                return $(this).val()
            }).get().join();
            $.ajax({
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=authors&searchAjax=1",
                type: 'GET',
                dataType: 'json',
                data: {
                    term: extractLast(request.term),
                    aids: aIDs
                },
                success: function(data) {
                    response($.map(data, function(value, key) {
                        return {
                            label: value,
                            value: key
                        }
                    }))
                }
            })
        },
        search: function() {
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label)
        },
        select: function(event, ui) {
            event.preventDefault();
            nv_add_element('internal_authors', ui.item.value, ui.item.label);
            $(this).val('');
        }
    }).on("focusout", function(event) {
        event.preventDefault();
        $(this).val('')
    }).bind("keyup blur", function(event) {
        event.preventDefault()
    });
    // internal_authors autocomplete end

    // hide message_body after the first one
    $(".message_list .message_body:gt(1)").hide();

    // hide message li after the 5th
    $(".message_list li:gt(5)").hide();

    // toggle message_body
    $(".message_head").click(function() {
        $(this).next(".message_body").slideToggle(500);
        return false;
    });

    // collapse all messages
    $(".collpase_all_message").click(function() {
        $(".message_body").slideUp(500);
        return false;
    });

    // Show all messages
    $(".show_all_message").click(function() {
        $(".message_body").slideDown(1000);
        return false;
    });

    $("#topicid").select2({
        language: "vi",
        width: '100%',
        ajax: {
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&get_topic_json=1',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

    // Control content adv tab
    $('#adv-form').on('hidden.bs.collapse', function() {
        $('#adv-form-arrow').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
        $.cookie(nv_module_name + '_advtabcontent', 'HIDE', {
            expires: 7
        });
    });
    $('#adv-form').on('shown.bs.collapse', function() {
        $('#adv-form-arrow').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
        $.cookie(nv_module_name + '_advtabcontent', 'SHOW', {
            expires: 7
        });
    });
    if ($.cookie(nv_module_name + '_advtabcontent') == 'SHOW') {
        $('#adv-form').collapse('show');
    } else {
        $('#adv-form').collapse('hide');
    }

    // Duy trì trạng thái sửa bài viết
    if (typeof CFG.is_edit_news != "undefined" && CFG.is_edit_news == true) {
        timer_check_takeover = setTimeout(function() {
            nv_timer_check_takeover(CFG.id);
        }, 10000);
    }

    // Toggle trạng thái bắt buộc của nội dung bài viết
    function checkReqBHtml() {
        const form = $('#form-news-content');
        const reqBHtml = (trim($('[name="sourcetext"]', form).val()) == ''|| !$('[name="external_link"]', form).is(':checked')) ? true : false;
        if (reqBHtml) {
            $('#content_bodytext_required').show();
            return;
        }
        $('#content_bodytext_required').hide();
    }
    $('[name="external_link"]').on('change', function() {
        checkReqBHtml();
    });
    $('#AjaxSourceText').on('change', function() {
        checkReqBHtml();
    });

    // Kiểm tra form đăng bài viết
    function validateContentForm(e) {
        let form = $('#form-news-content');
        $(".has-error", form).removeClass("has-error");

        // Tiêu đề bài viết
        let iptTitle = $('[name="title"]', form);
        if (trim(iptTitle.val()) == '') {
            e.preventDefault();
            $(".tooltip-current", form).removeClass("tooltip-current");
            $(iptTitle).addClass("tooltip-current").attr("data-current-mess", $(iptTitle).attr("data-mess"));
            nv_validErrorShow(iptTitle);
            return false;
        }

        // Chuyên mục
        var catid = $(form).find(".news_checkbox:checked").val();
        if (typeof catid == "undefined" || catid <= 0) {
            e.preventDefault();
            $(form).find("#show_error").css('display', 'block');
            $("#show_error", form).html(form.data('ecat'));
            $('html,body').animate({
                scrollTop: $("#show_error").offset().top
            }, 200);
            return false;
        }

        const reqBHtml = (trim($('[name="sourcetext"]', form).val()) == ''|| !$('[name="external_link"]', form).is(':checked')) ? true : false;

        // Nội dung bài viết
        let editorid = form.data('mdata') + '_bodyhtml';
        if (typeof CKEDITOR != "undefined" && CKEDITOR.instances[editorid]) {
            if (reqBHtml && trim(CKEDITOR.instances[editorid].getData()) == '') {
                e.preventDefault();
                $(form).find("#show_error").css('display', 'block');
                $("#show_error", form).html(form.data('ebodytext'));
                $('html,body').animate({
                    scrollTop: $("#show_error").offset().top
                }, 200);
                return false;
            }
        }
        if (typeof window.nveditor != "undefined" && window.nveditor[editorid]) {
            if (reqBHtml && trim(window.nveditor[editorid].getData()) == '') {
                e.preventDefault();
                $(form).find("#show_error").css('display', 'block');
                $("#show_error", form).html(form.data('ebodytext'));
                $('html,body').animate({
                    scrollTop: $("#show_error").offset().top
                }, 200);
                return false;
            }
        }

        return true;
    }

    $('#form-news-content').on('submit', function(e) {
        validateContentForm(e);
    });

    // Xử lý từ chối bài viết
    const mReject = $('#modal-confirm-reject');
    $('.submit-reject').on('click', function(e) {
        e.preventDefault();
        // Kiểm tra form trước khi nhập lí do từ chối
        const checkForm = validateContentForm(e);
        if (checkForm) {
            mReject.append('<input type="hidden" id="reject_status_tmp" name="status' + $(this).data('type') + '" value="1">');
            mReject.modal('show');
        }
    });
    mReject.on('shown.bs.modal', function() {
        $('#reject_reason').focus();
    });
    mReject.on('hide.bs.modal', function() {
        const iptSubmit = $('#reject_status_tmp');
        if (iptSubmit.length) {
            iptSubmit.remove();
        }
    });
    $('[name="save_reject"]').on('click', function(e) {
        if (trim($('#reject_reason').val()) == '') {
            e.preventDefault();
            $('#reject_reason').focus();
            alert($(this).data('error'));
            return false;
        }
    });
});

function nv_validErrorHidden(a) {
    $(a).parent().parent().removeClass("has-error")
}

function nv_validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
    $(a).tooltip({
        title: function() {
            return $(a).attr("data-current-mess")
        }
    });
    $(a).focus().tooltip("show")
}
