/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var gAudioContext = new AudioContext(),
    playObj = null,
    playfile = null,
    refresh_interval = null;

function resizeTextArea(element) {
    $(element).height("auto").height($(element)[0].scrollHeight);
}

function getFileSize(fileobj) {
    try {
        var fileSize = 0;
        //for IE
        if (navigator.userAgent.match(/msie/i)) {
            //before making an object of ActiveXObject, 
            //please make sure ActiveX is enabled in your IE browser
            var objFSO = new ActiveXObject("Scripting.FileSystemObject");
            var filePath = $(fileobj)[0].value;
            var objFile = objFSO.getFile(filePath);
            var fileSize = objFile.size; //size in b
            fileSize = fileSize / 1048576; //size in mb 
        }
        //for FF, Safari, Opeara and Others
        else {
            fileSize = $(fileobj)[0].files[0].size //size in b
            fileSize = fileSize / 1048576; //size in mb 
        }
        fileSize = fileSize.toFixed(2);
        return fileSize;
    } catch (e) {
        alert("Error is :" + e);
    }
}

function getAudioContext() {
    if (!gAudioContext) {
        gAudioContext = new AudioContext();
    }
    return gAudioContext;
}

function fetchBlob(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.onload = function() {
        callback(this.response);
    };
    xhr.onerror = function() {
        alert('Failed to fetch ' + url);
    };
    xhr.send();
}

function readBlob(blob, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = new Uint8Array(e.target.result);
        callback(data);
    };
    reader.readAsArrayBuffer(blob);
}

function playAmrBlob(blob, callback) {
    readBlob(blob, function(data) {
        playAmrArray(data);
    });
}

function playAmrArray(array) {
    var samples = AMR.decode(array);
    if (!samples) {
        $('.fa-stop').removeClass('fa-stop').addClass('fa-play');
        $('.playing').removeClass('animation');
        alert('Failed to decode!');
        return;
    }
    playPcm(samples);
}

function playPcm(samples) {
    var ctx = getAudioContext();
    var src = ctx.createBufferSource();
    var buffer = ctx.createBuffer(1, samples.length, 8000);
    if (buffer.copyToChannel) {
        buffer.copyToChannel(samples, 0, 0)
    } else {
        var channelBuffer = buffer.getChannelData(0);
        channelBuffer.set(samples);
    }

    src.buffer = buffer;
    src.connect(ctx.destination);
    src.onended = function() {
        playObj = null;
        playfile = null;
        $('.fa-stop').removeClass('fa-stop').addClass('fa-play');
        $('.playing').removeClass('animation')
    };
    src.start();
    playObj = src;
}

function playerStart(audio) {
    fetchBlob(audio, function(blob) {
        playfile = audio;
        playAmrBlob(blob);
    });
}

function playerStop() {
    if (playObj) {
        playObj.stop()
    }
    playObj = null;
    playfile = null;
    $('.fa-stop').removeClass('fa-stop').addClass('fa-play');
    $('.playing').removeClass('animation');
}

function playerToggle(obj, audio) {
    if (audio != playfile) {
        if (playObj !== null) {
            playerStop();
            setTimeout(() => {
                $('.fa-play', obj).removeClass('fa-play').addClass('fa-stop');
                $('.playing', obj).addClass('animation');
                playerStart(audio)
            }, 100);
        } else {
            $('.fa-play', obj).removeClass('fa-play').addClass('fa-stop');
            $('.playing', obj).addClass('animation');
            playerStart(audio)
        }
    } else {
        playerStop()
    }
}

function conv_refresh(url, user_id, silent) {
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url,
        data: {
            'conversation_refresh': 1,
            'user_id': user_id
        },
        dataType: "json",
        success: function(b) {
            $('#loading, #conversation-content').toggleClass('hidden');
            if (b.status == "error") {
                if (!silent) {
                    alert(b.mess)
                }
            } else {
                $('#conversation-content').html(b.mess);
                $(".message-box").animate({
                    scrollTop: $(".message-box")[0].scrollHeight
                }, 'slow');
                /*setTimeout(() => {
                    $(".message-box").scrollTop($(".message-box")[0].scrollHeight)
                }, 400);*/
            }
        }
    })
}

function update_message_box(url, user_id, refresh) {
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url + '&nocache=' + new Date().getTime(),
        data: {
            'get_conversation': 1,
            'user_id': user_id,
            'refresh': refresh
        },
        dataType: "json",
        success: function(b) {
            if (b.status == "error") {
                alert(b.mess);
            } else if (b.status == "success") {
                $('#conversation-content').html(b.mess)
            }
        }
    })
}

function wait_modal_show() {
    var $div = $('<div class="modal fade wait_modal" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-center"><img src="' + nv_base_siteurl + nv_assets_dir + '/images/load_bar.gif' + '" width="33" heigh="8" alt="Please wait..."/></div></div></div></div>').appendTo('body');
    $div.modal({
        backdrop: 'static',
        keyboard: false
    }).on('hidden.bs.modal', function(e) {
        $div.remove()
    }).modal('show')
}

function wait_modal_hide() {
    $('.wait_modal').modal('hide')
}

function follower_getprofile(url, user_id) {
    wait_modal_show();
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url,
        data: {
            'get_follower_profile': 1,
            'user_id': user_id
        },
        dataType: "json",
        success: function(b) {
            if (b.status == "success") {
                window.location.href = window.location.href
            } else {
                alert(b.mess);
                wait_modal_hide()
            }
        }
    })
}

function ajax_json(url, data) {
    wait_modal_show();
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url,
        data: data,
        dataType: "json",
        success: function(b) {
            if (b.status != "success") {
                alert(b.mess);
                wait_modal_hide()
            } else {
                if (typeof b.redirect !== "undefined") {
                    window.location.href = b.redirect
                } else {
                    window.location.href = window.location.href
                }
            }
        }
    })
}

function vnsubdivisionsLoad() {
    var url = $('#vnsubdivisions').attr('data-location'),
        subdivParent = $('#vnsubdivisions').attr('data-subdiv-parent');
    wait_modal_show();
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url,
        data: {
            'vnsubdivisionsLoad': 1,
            'subdivParent': subdivParent
        },
        dataType: "html",
        success: function(b) {
            $('#vnsubdivisions .content').html(b);
            $('#vnsubdivisions').attr('data-loaded', 'true');
            wait_modal_hide()
        }
    })
}

function callcodesLoad() {
    var url = $('#callingcodes').attr('data-location');
    wait_modal_show();
    $.ajax({
        type: 'POST',
        cache: !1,
        url: url,
        data: 'callingcodesLoad=1',
        dataType: "html",
        success: function(b) {
            $('#callingcodes .content').html(b);
            $('#callingcodes').attr('data-loaded', 'true');
            wait_modal_hide()
        }
    })
}

$(function() {
    $('[data-toggle=getfollowers]').on('click', function(e) {
        e.preventDefault();
        var conf = confirm($(this).data('mess'));
        if (conf == true) {
            window.location.href = $(this).data('url')
        }
    });

    $('[data-toggle=follower_getprofile]').on('click', function(e) {
        e.preventDefault();
        var that = $(this).parents('.follower'),
            user_id = that.data('user-id'),
            url = $(this).parents('.followers').data('form-action');
        follower_getprofile(url, user_id)
    });

    $('[data-toggle=follower_getprofile2]').on('click', function(e) {
        e.preventDefault();
        var user_id = $(this).data('user-id'),
            url = $(this).data('url');
        follower_getprofile(url, user_id)
    });

    $('[data-toggle=oa_info_update]').on('click', function(e) {
        e.preventDefault();
        $('#oa-info-update').submit()
    });

    $('[data-toggle=oa_clear]').on('click', function(e) {
        e.preventDefault();
        $('#oa-clear').submit()
    });

    $('[data-toggle=access_token_create]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).attr('href'), "NVNB", 500, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
    });

    $('body').on('change', '[data-toggle=change_city]', function(e) {
        e.preventDefault();
        var city_id = $(this).val(),
            form = $(this).parents('form'),
            district_box = $('[name=district_id]', form),
            district_default = district_box.data('default'),
            url = form.data('action');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: {
                'get_districts': 1,
                'city_id': city_id
            },
            dataType: "html",
            success: function(b) {
                district_box.html(b);
                district_box.find('option[value=' + district_default + ']').prop('selected', 'selected')
            }
        })
    });

    $('[data-toggle=add_follower_tag], [data-toggle=access_token_copy], [data-toggle=add_tag], [data-toggle=request_form_submit], [data-toggle=list_form_submit], [data-toggle=access_token_copy], [data-toggle=webhookIPs]').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        ajax_json(url, data)
    });

    $('[data-toggle=remove_ftag]').on('click', function(e) {
        e.preventDefault();
        var tag_alias = $(this).data('tag-alias'),
            user_id = $(this).data('user-id'),
            url = $(this).attr('href');
        ajax_json(url, {
            'remove_ftag': 1,
            'user_id': user_id,
            'tag_alias': tag_alias
        })
    });

    $('[data-toggle=delete_tag]').on('click', function(e) {
        e.preventDefault();
        var tag_alias = $(this).data('tag-alias'),
            url = $(this).attr('href'),
            r = confirm($(this).parents('.tags').data('delete-confirm'));
        if (r == true) {
            ajax_json(url, {
                'delete_tag': 1,
                'tag_alias': tag_alias
            })
        }
    });

    $('[data-toggle=filter_by_tag]').on('change', function(e) {
        e.preventDefault();
        var tag = $(this).val(),
            url = $(this).data('url');
        if (tag != '') {
            url += '&tag=' + tag
        }
        window.location.href = url
    });

    $('[data-toggle=filter_by_type]').on('change', function(e) {
        e.preventDefault();
        var type = $(this).val(),
            url = $(this).data('url');
        if (type != '') {
            url += '&type=' + type
        }
        window.location.href = url
    });

    $('[data-toggle=filepreview]').on('click', function(e) {
        e.preventDefault();
        var type = $(this).data('type'),
            file = $(this).data('file'),
            width = parseInt($(this).data('width')),
            height = parseInt($(this).data('height')),
            url = $(this).data('url');
        if (type == 'image' || type == 'gif') {
            if (file.length && width && height) {
                $('#viewfile .filepreview').addClass('hidden').text('');
                $('#viewfile .imgpreview > img').attr('src', file).attr('width', width).attr('height', height);
                $('#viewfile .imgpreview').removeClass('hidden');
                $('#viewfile').modal('show')
            }
        } else if (url.length) {
            window.location.href = url
        }
    });

    $('[data-toggle=file_action_change]').on('change', function(e) {
        e.preventDefault();
        var val = $(this).val(),
            url = $(this).data('url'),
            id = $(this).data('id'),
            fr = $(this).parents('.zalo_file'),
            description = $('[name=description]', fr).val(),
            idfield = fr.data('idfield'),
            textfield = fr.data('textfield'),
            clfield = fr.data('clfield');
        if (val == 'delete') {
            if (confirm($(this).data('confirm')) == true) {
                ajax_json(url, 'file_delete=1&id=' + id)
            } else {
                $(this).val('')
            }
        } else if (val == 'renewal') {
            ajax_json(url, 'renewal=1&id=' + id)
        } else if (val == 'selfile' || val == 'selfiledesc') {
            $("#" + idfield, opener.document).val(id);
            if (val == 'selfiledesc') {
                $("#" + textfield, opener.document).val(description)
            }
            if (clfield != '') {
                $("#" + clfield, opener.document).trigger('click')
            }
            window.close()
        }
    });

    $('[data-toggle=select_file]').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id'),
            fr = $(this).parents('.zalo_file'),
            description = $('[name=description]', fr).val(),
            idfield = fr.data('idfield'),
            textfield = fr.data('textfield'),
            clfield = fr.data('clfield');
        $("#" + idfield, opener.document).val(id);
        if (textfield != '') {
            $("#" + textfield, opener.document).val(description)
        }
        if (clfield != '') {
            $("#" + clfield, opener.document).trigger('click')
        }
        window.close()
    });

    $('[data-toggle=zalo_upload]').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            type = $('[name=type]', this).val(),
            file = $('[name=file]', this).val(),
            filename = file.replace(/^.*(\\|\/|\:)/, ''),
            description = $('[name=description]', this).val(),
            store_on_server = $(this).data('store-on-server'),
            zalo_url = $('input[name=type]', this).length ? $('input[name=type]', this).data('url') : $('select[name=type] option:selected', this).data('url'),
            data = new FormData();
        if (!file.length) {
            alert($('[name=file]', this).data('mess'));
            return !1
        }
        data.append('file', $('input[type=file]', this)[0].files[0]);
        wait_modal_show();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'get_accesstoken=1',
            dataType: "html",
            success: function(accesstoken) {
                $.ajax({
                    type: 'POST',
                    url: zalo_url,
                    headers: {
                        'access_token': accesstoken
                    },
                    enctype: 'multipart/form-data',
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).done(function(a) {
                    if (a.error) {
                        alert(a.message);
                        wait_modal_hide()
                    } else {
                        if (store_on_server) {
                            data.append('type', type);
                            data.append('filename', filename);
                            data.append('description', description);
                            data.append('zalo_id', (a.data.attachment_id ? a.data.attachment_id : a.data.token));
                            $.ajax({
                                type: 'POST',
                                cache: !1,
                                url: url,
                                enctype: 'multipart/form-data',
                                data: data,
                                processData: false,
                                contentType: false,
                                dataType: "json",
                                success: function(b) {
                                    if (b.status != "success") {
                                        alert(b.mess);
                                        wait_modal_hide()
                                    } else {
                                        window.location.href = window.location.href
                                    }
                                }
                            })
                        } else {
                            $.ajax({
                                type: 'POST',
                                cache: !1,
                                url: url,
                                data: 'type=' + type + '&filename=' + filename + '&description=' + description + '&zalo_id=' + (a.data.attachment_id ? a.data.attachment_id : a.data.token),
                                dataType: "json",
                                success: function(b) {
                                    if (b.status != "success") {
                                        alert(b.mess);
                                        wait_modal_hide()
                                    } else {
                                        window.location.href = window.location.href
                                    }
                                }
                            })
                        }
                    }
                })
            }
        })
    });

    $('[data-toggle=zalo_video]').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            file = $('[name=file]', this).val(),
            filename = file.replace(/^.*(\\|\/|\:)/, ''),
            description = $('[name=description]', this).val(),
            view = $('[name=view]', this).val(),
            thumb = $('[name=thumb]', this).val(),
            zalo_url = $(this).data('url'),
            data = new FormData();
        if (!file.length) {
            alert($('[name=file]', this).data('mess'));
            return !1
        }
        if (!description.length) {
            alert($('[name=description]', this).data('mess'));
            return !1
        }

        wait_modal_show();
        data.append('file', $('input[type=file]', this)[0].files[0]);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'get_accesstoken=1',
            dataType: "html",
            success: function(accesstoken) {
                $.ajax({
                    type: 'POST',
                    url: zalo_url,
                    headers: {
                        'access_token': accesstoken
                    },
                    enctype: 'multipart/form-data',
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).done(function(a) {
                    if (a.error) {
                        alert(a.message);
                        wait_modal_hide()
                    } else {
                        $.ajax({
                            type: 'POST',
                            cache: !1,
                            url: url,
                            data: 'add=1&filename=' + filename + '&description=' + description + '&view=' + view + '&thumb=' + thumb + '&token=' + a.data.token,
                            dataType: "json",
                            success: function(b) {
                                if (b.status == "error") {
                                    alert(b.mess);
                                    wait_modal_hide()
                                } else {
                                    window.location.href = window.location.href
                                }
                            }
                        })
                    }
                })
            }
        })
    });

    $('[data-toggle=upload_type_change]').on('change', function(e) {
        e.preventDefault();
        var accept = $(this).find('option:selected').data('accept'),
            maxsize = $(this).find('option:selected').data('maxsize'),
            form = $(this).parents('form');
        $('[name=file]', form).attr('accept', accept);
        $('#upload_maxsize').text(maxsize)
    })

    $('[data-toggle=conversation_refresh]').on('click', function(e) {
        e.preventDefault();
        if (playObj !== null) {
            playerStop();
        }
        $(".message-box").scrollTop(0);
        $('#loading, #conversation-content').toggleClass('hidden');
        var url = $(this).data('url'),
            user_id = $(this).data('user-id');

        conv_refresh(url, user_id, false)
    });

    $('#fi_actions [data-toggle="collapse"], #settings [data-toggle="collapse"]').on('click', function() {
        locationReplace($($(this).attr('href')).attr("data-location"))
    });

    $('#fi_actions .panel-collapse, #settings .panel-collapse').on('show.bs.collapse', function() {
        $(this).parents('.panel').removeClass('panel-default').addClass('panel-primary')
    }).on('hide.bs.collapse', function() {
        $(this).parents('.panel').removeClass('panel-primary').addClass('panel-default')
    });

    $('#vnsubdivisions').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') == 'false') {
            vnsubdivisionsLoad()
        }
    });

    $('#callingcodes').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') == 'false') {
            callcodesLoad()
        }
    });

    $('#last_conversation').on('show.bs.collapse', function() {
        var url = $(this).data('url'),
            user_id = $(this).data('user-id');
        if ($(this).attr('data-loaded') == 'false') {
            $(this).attr('data-loaded', 'true');
            update_message_box(url, user_id, 0);
        }
        refresh_interval = setInterval(function() {
            update_message_box(url, user_id, 1)
        }, 30000)
    }).on('shown.bs.collapse', function() {
        setTimeout(() => {
            $(".message-box").scrollTop($(".message-box")[0].scrollHeight)
        }, 200);
    }).on('hide.bs.collapse', function() {
        clearInterval(refresh_interval)
    });

    $('#getquota').on('show.bs.collapse', function() {
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: {
                'get_proactive_messages_quota': 1
            },
            dataType: "json",
            success: function(b) {
                if (b.status == "error") {
                    alert(b.mess);
                } else {
                    $('#proactive_messages_type').text(b.type);
                    $('#proactive_messages_total').text(b.total);
                    $('#proactive_messages_remain').text(b.remain)
                }
            }
        })
    });

    $('body').on('keypress', '.keypress_submit', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $(this).parents('form').submit()
        }
    });

    $('[data-toggle=file_desc_change]').on('change', function(e) {
        e.preventDefault();
        $(this).parents('form').submit()
    });

    $('[data-toggle=file_desc]').on('submit', function(e) {
        e.preventDefault();
        var new_desc = trim(strip_tags($('[name=description]', this).val())),
            old_desc = $('[name=description]', this).data('default'),
            that = this;
        if (new_desc.length >= 3) {
            if (new_desc != old_desc) {
                $.ajax({
                    type: 'POST',
                    cache: !1,
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(b) {
                        if (b.status == 'success') {
                            $('[name=description]', that).data('default', b.mess).val(b.mess)
                        }
                    }
                })
            }
        } else {
            $('[name=description]', this).val(old_desc)
        }
    });

    $('[data-toggle=auto_update_tag]').on('change', function(e) {
        e.preventDefault();
        var new_name = trim(strip_tags($(this).val())),
            old_name = $(this).data('default'),
            url = $(this).data('url'),
            alias = $(this).data('alias'),
            that = this;
        if (new_name.length >= 3) {
            if (new_name != old_name) {
                $.ajax({
                    type: 'POST',
                    cache: !1,
                    url: url,
                    data: 'alias=' + alias + '&new_name=' + new_name,
                    dataType: "json",
                    success: function(b) {
                        if (b.status == 'success') {
                            $(that).data('default', b.mess).val(b.mess)
                        }
                    }
                })
            }
        } else {
            $(this).val(old_name)
        }
    })

    $('[data-toggle=change_attachment_type]').on('change', function(e) {
        e.preventDefault();
        var type = $(this).val();
        if (type == 'plaintext') {
            $('[data-toggle=add_attachment]').prop('disabled', true);
            $('#attachment').val('').prop('readonly', true);
            $('#chat_text').prop('readonly', false).focus()
        } else if (type == 'site' || type == 'zalo') {
            $('[data-toggle=add_attachment]').prop('disabled', false);
            $('#attachment').val('').prop('readonly', true);
            $('#chat_text').prop('readonly', false);
            $('[data-toggle=add_attachment]').trigger('click')
        } else if (type == 'file' || type == 'request') {
            $('[data-toggle=add_attachment]').prop('disabled', false);
            $('#attachment').val('').prop('readonly', true);
            $('#chat_text').val('').prop('readonly', true);
            $('[data-toggle=add_attachment]').trigger('click')
        } else if (type == 'internet') {
            $('[data-toggle=add_attachment]').prop('disabled', true);
            $('#attachment').val('').prop('readonly', false);
            $('#chat_text').prop('readonly', false);
            $('#attachment').focus()
        } else if (type == 'pltext') {
            $('[data-toggle=add_attachment]').prop('disabled', true);
            $('#attachment').val('').prop('readonly', true);
            $('#chat_text').prop('readonly', false);
            var url = $('option:selected', this).data('url');
            $(this).val('plaintext');
            nv_open_browse(url, "Attachment", 500, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        }
    });

    $('[data-toggle=add_attachment]').on('click', function(e) {
        e.preventDefault();
        var type = $('[data-toggle=change_attachment_type]').val();
        if (type == 'site') {
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=attachment&alt=&path=" + encodeURIComponent($(this).data('upload-dir')) + "&type=image&currentpath=" + encodeURIComponent($(this).data('upload-dir')), "Attachment", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        } else if (type == 'zalo' || type == 'file' || type == 'request') {
            var url = $('[data-toggle=change_attachment_type] option:selected').data('url');
            nv_open_browse(url, "Attachment", 500, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        }
    });

    $('[data-toggle=del_attachment]').on('click', function(e) {
        e.preventDefault();
        $('#attachment').val('')
    });

    $('#chat-box').on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize();
        $('input,button,select,textarea', that).prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(b) {
                $('input,button,select,textarea', that).prop('disabled', false);
                if (b.status == "error") {
                    alert(b.mess);
                } else if (b.status == 'success') {
                    $('#attachment, #chat_text').val('');
                    $('#chat_text').prop('readonly', false);
                    $('[name=attachment_type]').val('plaintext');
                    $('#message_id').val('');
                    $('#reply-mess').addClass('hidden');
                    $('#conversation-content').html(b.mess);
                    $(".message-box").scrollTop($(".message-box")[0].scrollHeight)
                }
            }
        })
    })

    $('[data-toggle=conversation_gotop]').on('click', function(e) {
        e.preventDefault();
        $(".message-box").animate({
            scrollTop: 0
        }, 'slow')
    });
    $('[data-toggle=conversation_gobottom]').on('click', function(e) {
        e.preventDefault();
        $(".message-box").animate({
            scrollTop: $(".message-box")[0].scrollHeight
        }, 'slow')
    });

    /*$('#auto_refresh').on('change', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            user_id = $(this).data('user-id');
        if ($(this).prop('checked')) {
            refresh_interval = setInterval(function() {
                $('#loading, #conversation-content').toggleClass('hidden');
                conv_refresh(url, user_id, 5, 1, true)
            }, 60000);
        } else {
            clearInterval(refresh_interval);
        }
    })*/

    $('body').on('click', '[data-toggle=viewimg]', function(e) {
        e.preventDefault();
        var img = $(this).data('img');
        $('#viewimg .viewimg img').attr('src', img);
        $('#viewimg').modal('show')
    });

    $('body').on('click', '[data-toggle=other_link]', function(e) {
        e.preventDefault();
        nv_open_browse($(this).attr('href'), "otherLink", 900, 600, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
    });

    $('body').on('click', '[data-toggle=voice_play]', function(e) {
        e.preventDefault();
        var fl = $(this).data('file');
        if (fl) {
            playerToggle($(this), fl)
        }
    });

    $('body').on('click', '[data-toggle=request_image], [data-toggle=list_image], [data-toggle=video_thumb], [data-toggle=cover_photo_url], [data-toggle=body_photo_url], [data-toggle=body_thumb], [data-toggle=video_avatar]', function(e) {
        e.preventDefault();
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + $(this).data('area') + "&alt=&path=" + encodeURIComponent($(this).data('upload-dir')) + "&type=image&currentpath=" + encodeURIComponent($(this).data('upload-dir')), "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    $('[data-toggle=request_delete]').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form'),
            url = form.attr('action'),
            id = $('[name=id]', form).val(),
            conf = confirm($(this).data('confirm'));
        if (conf == true) {
            ajax_json(url, 'action=delete&id=' + id)
        }
    });

    $('[data-toggle=request_select]').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form'),
            id = $('[name=id]', form).val(),
            idfield = form.data('idfield'),
            clfield = form.data('clfield');
        $("#" + idfield, opener.document).val(id);
        if (clfield != '') {
            $("#" + clfield, opener.document).trigger('click')
        }
        window.close()
    });

    $('[data-toggle=action_change]').on('change', function(e) {
        e.preventDefault();
        var el = $(this).parents('.element'),
            val = $(this).val();
        if (val == 'open_url') {
            $('[name^=url]', el).parents('.action').removeClass('hidden');
            $('[name^=content], [name^=keyword], [name^=phone_code], [name^=phone_number]', el).parents('.action').addClass('hidden')
        } else if (val == 'query_show' || val == 'query_hide') {
            $('[name^=content]', el).parents('.action').removeClass('hidden');
            $('[name^=url], [name^=keyword], [name^=phone_code], [name^=phone_number]', el).parents('.action').addClass('hidden')
        } else if (val == 'query_keyword') {
            $('[name^=keyword]', el).parents('.action').removeClass('hidden');
            $('[name^=url], [name^=content], [name^=phone_code], [name^=phone_number]', el).parents('.action').addClass('hidden')
        } else if (val == 'open_sms') {
            $('[name^=content], [name^=phone_code], [name^=phone_number]', el).parents('.action').removeClass('hidden');
            $('[name^=url], [name^=keyword]', el).parents('.action').addClass('hidden')
        } else if (val == 'open_phone') {
            $('[name^=phone_code], [name^=phone_number]', el).parents('.action').removeClass('hidden');
            $('[name^=url], [name^=content], [name^=keyword]', el).parents('.action').addClass('hidden')
        } else {
            $('[name^=url], [name^=content], [name^=keyword], [name^=phone_code], [name^=phone_number]', el).parents('.action').addClass('hidden')
        }
    });

    $('[data-toggle=subtitle_box]').on('click', function(e) {
        e.preventDefault();
        var el = $(this).parents('.element'),
            subtitle_obj = $('[name^=subtitle]', el).parents('.form-group'),
            issubtitle_obj = $('[name^=issubtitle]', el),
            icon_obj = $('.fa', this);
        if (subtitle_obj.is('.hidden')) {
            issubtitle_obj.val('1');
            subtitle_obj.removeClass('hidden');
            icon_obj.removeClass('fa-caret-down').addClass('fa-caret-up')
        } else {
            issubtitle_obj.val('0');
            subtitle_obj.addClass('hidden');
            icon_obj.removeClass('fa-caret-up').addClass('fa-caret-down')
        }
    });

    $('body').on('click', '[data-toggle=action_open_modal]', function(e) {
        e.preventDefault();
        $('#actionModal .action-title').text($(this).data('title'));
        $('#actionModal .action-content').text($(this).data('content'));
        $('#actionModal').modal('show')
    });

    $('[data-toggle=list_del]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href'),
            id = $(this).data('id'),
            conf = confirm($(this).data('confirm'));
        if (conf == true) {
            ajax_json(url, 'delete=1&id=' + id)
        }
    });

    $('[data-toggle=list_select]').on('click', function(e) {
        e.preventDefault();
        var el = $(this).parents('.list'),
            id = $(this).data('id'),
            tid = $(this).data('tid'),
            idfield = el.data('idfield'),
            parameter = el.data('parameter'),
            clfield = el.data('clfield');
        id = br2nl(id, true);
        if (idfield && idfield != '') {
            $("#" + idfield, opener.document).val(id);
        }
        if (parameter && parameter != '') {
            $("#" + parameter, opener.document).val(tid);
        }
        if (clfield && clfield != '') {
            $("#" + clfield, opener.document).trigger('click')
        }
        window.close()
    });

    $('body').on('click', '[data-toggle=mess_reply]', function(e) {
        e.preventDefault();
        var message_id = $(this).data('message-id');
        $('#message_id').val(message_id);
        $('#message_id_text').text(message_id);
        $('#reply-mess').removeClass('hidden');
        $("#chat_text").focus()
    });

    $('[data-toggle=goto_reply_mess]').on('click', function(e) {
        e.preventDefault();
        var id = $(this).text();
        if (id != '') {
            $(".message-box").animate({
                scrollTop: $(".message-box").scrollTop() + ($("#mess-" + id).offset().top - $(".message-box").offset().top)
            }, 400)
        }
    });

    $('[data-toggle=reply_remove]').on('click', function(e) {
        e.preventDefault();
        $('#message_id').val('');
        $('#reply-mess').addClass('hidden');
    });

    $('[data-toggle=video_check]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        ajax_json(url, 'check=1&id=' + id)
    });

    $('[data-toggle=video_edit_btn]').on('click', function(e) {
        e.preventDefault();
        var form = $('#video_edit form');
        $('[name=id]', form).val($(this).data('id'));
        $('[name=view]', form).val($(this).data('view'));
        $('[name=thumb]', form).val($(this).data('thumb'));
        $('[name=description]', form).val($(this).data('description'));
        $('#video_edit').modal('show')
    });

    $('[data-toggle=video_edit]').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            description = $('[name=description]', this).val(),
            data = $(this).serialize();
        if (description.length < 3) {
            alert($('[name=description]', this).data('mess'));
            return !1
        }
        ajax_json(url, data)
    });

    $('[data-toggle=add_article_change_type], [data-toggle=change_template_type]').on('change', function(e) {
        e.preventDefault();
        var url = $('option:selected', this).data('url');
        window.location.href = url
    });

    $('[data-toggle=cover_type_change]').on('change', function(e) {
        e.preventDefault();
        var val = $(this).val(),
            form = $(this).parents('form');
        if (val == 'photo') {
            $('[name=cover_photo_url]', form).parents('.field').removeClass('hidden');
            $('[name=cover_video_id], [name=cover_view]', form).parents('.field').addClass('hidden')
        } else {
            $('[name=cover_video_id], [name=cover_view]', form).parents('.field').removeClass('hidden');
            $('[name=cover_photo_url]', form).parents('.field').addClass('hidden')
        }
    });

    $('[data-toggle=cover_video_get], [data-toggle=article_video_get]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).data('url'), "Attachment", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    $('body').on('click', '[data-toggle=body_video_get]', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        url += '&idfield=' + $(this).data('idfield');
        url += '&thumbfield=' + $(this).data('thumbfield');
        nv_open_browse(url, "Attachment", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    $('[data-toggle=select_zalo_video]').on('click', function(e) {
        e.preventDefault();
        var fr = $(this).parents('.zalo_file'),
            video_id = $(this).data('video-id'),
            view = $(this).data('view'),
            thumb = $(this).data('thumb'),
            idfield = fr.data('idfield'),
            viewfield = fr.data('viewfield'),
            thumbfield = fr.data('thumbfield');
        if (idfield != '') {
            $("#" + idfield, opener.document).val(video_id)
        }
        if (viewfield != '') {
            $("#" + viewfield, opener.document).val(view)
        }
        if (thumbfield != '') {
            $("#" + thumbfield, opener.document).val(thumb)
        }
        window.close()
    });

    $('body').on('change', '[data-toggle=body_type_change]', function(e) {
        e.preventDefault();
        var val = $(this).val(),
            bd = $(this).parents('.body');
        if (val == 'text') {
            $('[name^=body_content]', bd).parents('.field').removeClass('hidden');
            $('[name^=body_photo_url], [name^=body_caption], [name^=body_video_content], [name^=body_thumb], [name^=body_product_id]', bd).parents('.field').addClass('hidden');
        } else if (val == 'image') {
            $('[name^=body_photo_url], [name^=body_caption]', bd).parents('.field').removeClass('hidden');
            $('[name^=body_content], [name^=body_video_content], [name^=body_thumb], [name^=body_product_id]', bd).parents('.field').addClass('hidden');
        } else if (val == 'video') {
            $('[name^=body_video_content], [name^=body_thumb]', bd).parents('.field').removeClass('hidden');
            $('[name^=body_content], [name^=body_photo_url], [name^=body_caption], [name^=body_product_id]', bd).parents('.field').addClass('hidden');
        } else if (val == 'product') {
            $('[name^=body_product_id]', bd).parents('.field').removeClass('hidden');
            $('[name^=body_content], [name^=body_photo_url], [name^=body_caption], [name^=body_video_content], [name^=body_thumb]', bd).parents('.field').addClass('hidden');
        } else {
            $('[name^=body_content], [name^=body_photo_url], [name^=body_caption], [name^=body_video_content], [name^=body_thumb]', bd).parents('.field').addClass('hidden');
        }
    });

    $('[data-toggle=add_body]').on('click', function(e) {
        e.preventDefault();
        var body_list = $(this).parents('.body_list'),
            content = $('.body:last', body_list).html(),
            count = new Date().getTime();
        body_list.append('<tbody class="body" id="body-' + count + '">' + content + '</tbody>');
        var tbody_last = $('.body:last', body_list);
        $('[name^=body_id]', tbody_last).val('body-' + count);
        $('[id^=body_photo_url]', tbody_last).attr('id', 'body_photo_url' + count);
        $('[data-toggle=body_photo_url]', tbody_last).attr('data-area', 'body_photo_url' + count);
        $('[id^=body_video_content]', tbody_last).attr('id', 'body_video_content' + count);
        $('[data-toggle=body_video_get]', tbody_last).attr('data-idfield', 'body_video_content' + count).attr('data-thumbfield', 'body_thumb' + count);
        $('[id^=body_thumb]', tbody_last).attr('id', 'body_thumb' + count);
        $('[data-toggle=body_thumb]', tbody_last).attr('data-area', 'body_thumb' + count);
        $('[name^=body_content], [name^=body_photo_url], [name^=body_caption], [name^=body_video_content], [name^=body_thumb], [name^=body_product_id]', tbody_last).val('');
        $('[name^=body_video_type]', tbody_last).val('url').trigger('change');
        $('[name^=body_type]', tbody_last).val('text').trigger('change')
    });

    $('body').on('click', '[data-toggle=delete_body]', function(e) {
        e.preventDefault();
        var body_list = $(this).parents('.body_list'),
            count = $('.body', body_list).length;
        if (count > 1) {
            $(this).parents('.body').remove()
        }
    });

    $('body').on('change', '[data-toggle=body_video_type_change]', function(e) {
        e.preventDefault();
        var val = $(this).val(),
            field = $(this).parents('.field');
        if (val == 'url') {
            $('[data-toggle=body_video_get]', field).prop('disabled', true)
        } else {
            $('[data-toggle=body_video_get]', field).prop('disabled', false)
        }
    });

    $('[data-toggle=article_form_submit]').on('submit', function(e) {
        e.preventDefault();
        var error = false,
            that = this;
        $('.required:visible', this).each(function(e) {
            var val = trim(strip_tags($(this).val())),
                pattern = $(this).data('pattern'),
                mess = $(this).data('mess'),
                a = pattern.match(/^\/(.*?)\/([gim]*)$/);
            if (!(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(val)) {
                error = true;
                alert(mess);
                $(this).focus();
                return !1
            }
        });
        if (error) {
            return !1
        }

        wait_modal_show();

        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(b) {
                if (b.status == "error") {
                    alert(b.mess);
                    wait_modal_hide();
                    if (b.input != '') {
                        if (b.body_id != '') {
                            $('#' + b.body_id + ' [name^=' + b.input + ']').focus()
                        } else {
                            $('[name^=' + b.input + ']', that).focus()
                        }
                    }
                } else if (b.status == "success") {
                    window.location.href = b.redirect
                }
            }
        })
    });

    $('[data-toggle=get_zalo_id]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).parents('.articles-list').data('url'),
            id = $(this).data('id');
        ajax_json(url, 'get_zalo_id=1&id=' + id)
    });

    $('[data-toggle=article_action_change]').on('change', function(e) {
        e.preventDefault();
        var val = $(this).val(),
            id = $(this).data('id'),
            url = $(this).parents('.articles-list').data('url');
        if (val == 'edit') {
            window.location.href = url + '&action=edit&id=' + id
        } else if (val == 'sync') {
            ajax_json(url, 'sync=1&id=' + id)
        } else if (val == 'delete') {
            var conf = confirm($(this).parents('.articles-list').data('del-confirm'));
            if (conf == true) {
                ajax_json(url, 'delete=1&id=' + id)
            } else {
                $(this).val('')
            }
        }
    });

    $('[data-toggle=view_article]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).data('url'), "VIEW_ARTICLE", 500, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
    });

    $('[data-toggle=get_article_list]').on('click', function(e) {
        e.preventDefault();
        var sel = $('[data-toggle=filter_by_type]'),
            art_type = sel.val(),
            url = sel.data('url'),
            mess = $(this).data('mess');
        if (art_type == '') {
            alert(mess);
            sel.focus()
        } else {
            window.location.href = url + '&getlist=1&type=' + art_type
        }
    });

    $('[data-toggle=get_related_article]').on('click', function(e) {
        e.preventDefault();
        var idfield = $(this).parents('.articles-list').data('idfield'),
            zalo_id = $(this).data('zalo-id'),
            title = $(this).data('title');
        $("#" + idfield, opener.document).append($('<li class="list-group-item related_article"><button type="button" class="close" data-toggle="related_article_remove"><span aria-hidden="true">&times;</span></button><input type="hidden" name="related_article[]" value="' + zalo_id + '">' + title + '</li>'));
        window.close()
    });

    $('[data-toggle=add_related_article]').on('click', function(e) {
        e.preventDefault();
        var ids = [$(this).data('zalo-id')],
            cont = $('[name^=related_article]', $(this).parents('form')).length;
        if (cont < 6) {
            $(this).parents('form').find('[name^=related_article]').each(function() {
                ids.push($(this).val())
            });
            ids.join(',');

            nv_open_browse($(this).data('url') + '&currentid=' + ids, "NVNB", 900, 600, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
        }
    });

    $('body').on('click', '[data-toggle=related_article_remove]', function(e) {
        e.preventDefault();
        $(this).parents('.related_article').remove()
    });

    $('.chat-close').on('click', function(e) {
        e.preventDefault();
        $('#last_conversation').collapse('hide')
    });

    $('body').on('change', '[data-toggle=subdiv_parent_change]', function(e) {
        var val = $(this).val(),
            loc = $('option:selected', this).data('url');
        $('#vnsubdivisions').attr('data-subdiv-parent', val).attr('data-location', loc);
        locationReplace(loc);
        vnsubdivisionsLoad()
    });

    $('body').on('change', '[data-toggle=subdiv_remove_readonly]', function(e) {
        e.preventDefault();
        if ($(this).prop('checked') == true) {
            var conf = confirm($(this).parents('.vnsubdivisions').data('mess'));
            if (conf == true) {
                $('[name^=subdiv_mainname]', $(this).parents('.unit')).prop('readonly', false).focus()
            } else {
                $('[name^=subdiv_mainname]', $(this).parents('.unit')).prop('readonly', true);
                $(this).prop('checked', false)
            }
        } else {
            $('[name^=subdiv_mainname]', $(this).parents('.unit')).prop('readonly', true)
        }
    });

    $('body').on('click', '[data-toggle=add_other_name]', function(e) {
        e.preventDefault();
        var l = $('.name', $(this).parents('.other_name')).length,
            code = $(this).data('code');
        if (l < 10) {
            $('.other_name', $(this).parents('.other-names')).append('<div class="input-group name"><input type="text" name="subdiv_othername[' + code + '][]" value="" class="form-control" maxlength="100"><span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="subdiv_name_remove"><span>&times;</span></button><button class="btn btn-default" type="button" data-toggle="add_other_name" data-code="' + code + '"><span>+</span></button></span></div>');
            $('.other_name', $(this).parents('.other-names')).find('.name:last-child input').focus()
        }
    });

    $('body').on('click', '[data-toggle=subdiv_name_remove]', function(e) {
        e.preventDefault();
        var l = $('.name', $(this).parents('.other_name')).length;
        if (l > 1) {
            $(this).parents('.name').remove()
        } else {
            $('[name^=subdiv_othername]', $(this).parents('.name')).val('')
        }
    });

    $('body').on('submit', '[data-toggle=vnsubdivisionsSubmit]', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        wait_modal_show();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(b) {
                if (b.status != "success") {
                    alert(b.mess);
                    wait_modal_hide()
                } else {
                    $('#vnsubdivisions .content').html(b.content);
                    $('#vnsubdivisions').attr('data-loaded', 'true');
                    wait_modal_hide()
                }
            }
        })
    });

    $('body').on('click', '[data-toggle=callcode_add]', function(e) {
        e.preventDefault();
        var l = $('.callcode', $(this).parents('.callcodes')).length,
            country_code = $(this).parents('.callcodes').data('country');
        if (l < 5) {
            $(this).parents('.callcodes').append('<div class="input-group callcode"><input type="text" name="callcode[' + country_code + '][]" value="" class="form-control" maxlength="6"><span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="callcode_remove"><span>&times;</span></button><button class="btn btn-default" type="button" data-toggle="callcode_add"><span>+</span></button></span></div>');
            $(this).parents('.callcodes').find('.callcode:last-child input').focus()
        }
    });

    $('body').on('click', '[data-toggle=callcode_remove]', function(e) {
        e.preventDefault();
        var l = $('.callcode', $(this).parents('.callcodes')).length;
        if (l > 1) {
            $(this).parents('.callcode').remove()
        } else {
            $('[name^=callcode]', $(this).parents('.callcode')).val('')
        }
    });

    $('body').on('submit', '[data-toggle=callingcodesSubmit]', function(e) {
        e.preventDefault();
        var error = false,
            error_mess = $(this).data('mess');
        $('.callcodes', this).each(function() {
            var count = 0;
            $('[name^=callcode]', this).each(function() {
                var val = $(this).val().replace(/[^0-9]+/, '');
                if (val.length) {
                    count++;
                }
            })
            if (!count) {
                error = true;
                alert(error_mess);
                $('[name^=callcode]:first-child', this).focus()
            }
        });
        if (error) {
            return !1
        }

        var url = $(this).attr('action'),
            data = $(this).serialize();
        wait_modal_show();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(b) {
                if (b.status != "success") {
                    alert(b.mess);
                    wait_modal_hide()
                } else {
                    $('#callingcodes .content').html(b.content);
                    $('#callingcodes').attr('data-loaded', 'true');
                    wait_modal_hide()
                }
            }
        })
    });

    $('textarea.auto-resize').off('keyup').on('keyup', function(e) {
        resizeTextArea($(this))
    });

    $('[data-toggle=event_action_change]').on('change', function(e) {
        e.preventDefault();
        var event = $(this).parents('.event'),
            def_action = $(this).data('default'),
            def_parameter = $('[name^=parameter]', event).data('default'),
            action = $(this).val(),
            optnum = $('option', this).length;
        if (optnum > 1) {
            if (action != def_action) {
                $('[name^=parameter]', event).val('')
            } else {
                $('[name^=parameter]', event).val(def_parameter)
            }
            if (action != '') {
                $('[data-toggle=parameter_change]', event).trigger('click')
            }
        }
    });

    $('[data-toggle=parameter_change]').on('click', function(e) {
        e.preventDefault();
        var event = $(this).parents('.event'),
            optnum = $('[name^=action] option', event).length,
            url = $('[name^=action] option:selected', event).data('url');
        if (optnum > 1) {
            if (url) {
                nv_open_browse(url, "ChangeParameter", 500, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
            } else {
                alert($(this).parents('form').data('action-error-mess'));
                $('[name^=action]', event).trigger('focus')
            }
        }
    });

    $('[data-toggle=parameter_view]').on('click', function(e) {
        e.preventDefault();
        var event = $(this).parents('.event'),
            parameter = $('[name^=parameter]', event).val(),
            view_url = $('[name^=action] option:selected', event).data('view');
        if (view_url && parameter) {
            view_url += parameter;
            $.ajax({
                type: 'GET',
                cache: !1,
                url: view_url,
                dataType: "json",
                success: function(b) {
                    if (b.status != "success") {
                        alert(b.mess)
                    } else {
                        $('#preview .content').html(b.content);
                        $('#preview').modal('show')
                    }
                }
            })
        }
    });

    $('[data-toggle=event_action_submit]').on('submit', function(e) {
        e.preventDefault();
        var error = false,
            that = this;
        $('.has-error', this).removeClass('has-error');
        $('[name^=parameter]', this).each(function() {
            var parameter = $(this).val(),
                action = $('[name^=action]', $(this).parents('.event')).val();
            if ((parameter != '' && action == '') || (parameter == '' && action != '')) {
                $(this).parent().addClass('has-error');
                alert($(that).data('parameter-error-mess'));
                error = true;
                $(this).focus();
                return !1
            }
        });

        if (error) {
            return !1
        };

        ajax_json($(this).attr('action'), $(this).serialize())
    });

    $('[data-toggle=alias_get]').on('change', function(e) {
        e.preventDefault();
        var str = $(this).val(),
            def = $(this).attr('data-default'),
            url = $(this).parents('form').attr('action'),
            that = this;
        str = trim(strip_tags(str));
        $(this).val(str);
        if (str.length > 2 && str != def) {
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: 'change_alias=' + str,
                dataType: "html",
                success: function(b) {
                    $(that).val(b)
                }
            })
        }
    });

    $('[data-toggle=text_get_alias]').on('change', function(e) {
        e.preventDefault();
        var str = $(this).val(),
            def = $(this).attr('data-default'),
            event = $(this).parents('.event'),
            url = $(this).parents('form').attr('action'),
            keyword = $('[name^=keyword]', event).val();
        str = trim(strip_tags(str));
        $(this).val(str);
        if (keyword == '') {
            if (str.length > 2) {
                if (str != def) {
                    $.ajax({
                        type: 'POST',
                        cache: !1,
                        url: url,
                        data: 'change_alias=' + str,
                        dataType: "html",
                        success: function(b) {
                            $('[name^=keyword]', event).val(b)
                        }
                    })
                } else {
                    $('[name^keyword]', event).val($('[name^keyword]', event).attr('data-default'))
                }
            }
        }
    });

    $('[data-toggle=command_keyword_submit]').on('submit', function(e) {
        e.preventDefault();
        var error = false,
            that = this;
        $('.has-error', this).removeClass('has-error');
        $('[name^=keyword]', this).each(function() {
            var event = $(this).parents('.event'),
                keyword = $(this).val(),
                action = $('[name^=action]', event).val(),
                parameter = $('[name^=parameter]', event).val();
            if (!((keyword == '' && action == '' && parameter == '') || (keyword != '' && action != '' && parameter != ''))) {
                error = true;
                if (keyword == '') {
                    $(this).parent().addClass('has-error');
                    alert($(that).data('keyword-error-mess'));
                    $(this).focus()
                } else if (action == '') {
                    $('[name^=action]', event).parent().addClass('has-error');
                    alert($(that).data('action-error-mess'));
                    $('[name^=action]', event).focus()
                } else if (parameter == '') {
                    $('[name^=parameter]', event).parent().addClass('has-error');
                    alert($(that).data('parameter-error-mess'));
                    $('[name^=parameter]', event).focus()
                }

                return !1
            }
        });

        if (error) {
            return !1
        };

        ajax_json($(this).attr('action'), $(this).serialize())
    });

    $('[data-toggle=keyword_select]').on('click', function(e) {
        e.preventDefault();
        var val = $(this).data('value'),
            idfield = $(this).parents('.event').data('idfield');
        $("#" + idfield, opener.document).val(val);
        window.close()
    });

    $('[data-toggle=list_keywords]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).data('url') + $(this).data('idfield'), "NVNB", 700, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
    });

    $('[data-toggle=readonly_remove]').on('change', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            $('input[type=text]', $(this).parents('.readonly-item')).prop('readonly', false).focus()
        } else {
            $('input[type=text]', $(this).parents('.readonly-item')).prop('readonly', true)
        }
    });

    $('[data-toggle=event_remove]').on('click', function(e) {
        e.preventDefault();
        if (confirm($(this).data('confirm-mess')) === true) {
            $(this).parents('.event').remove()
        }
    });

    $('[data-toggle=zalowebhookIPs_check]').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $(this).parents('form').attr('action'),
            data: 'func=check_zaloip&checkss=' + $('[name=checkss]', $(this).parents('form')).val(),
            dataType: "html",
            success: function(b) {
                $('#zalowebhookIP_autocheck').modal({
                    backdrop: false,
                    keyboard: false
                  }).modal('show')
            }
        })
    });

    $('[data-toggle=zalowebhook_ip_update]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).parents('form').attr('action'),
            data = 'func=zalowebhook_ip_update&checkss=' + $('[name=checkss]', $(this).parents('form')).val();
        ajax_json(url, data)
    })

})
