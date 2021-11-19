/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (typeof ud_url_return == 'undefined') {
    var ud_url_return = '';
}

$(document).ready(function() {
    // Delete update package
    $('.delete_update_backage').click(function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            $('#infodetectedupg').append('<div id="dpackagew"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></div>');
            $.get($(this).attr('href'), function(e) {
                $('#dpackagew').remove()
                if (e == 'OK') {
                    window.location = ud_url_return;
                } else {
                    alert(e);
                }
            });
        }
        return !1;
    });

    $('.update_dump').click(function() {
        $('#infodetectedupg').append('<div id="dpackagew"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></div>');
        $.get($(this).attr('href'), function(e) {
            $('#dpackagew').remove();
            $('#infodetectedupg').append('<br />' + e);
        });
        return !1;
    });

    // Finish update
    $('.delete_update_backage_end').click(function() {
        if (completeUpdate == 0) {
            if (confirm(nv_is_del_confirm[0])) {
                $('#infodetectedupg').append('<div id="dpackagew"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></div>');
                $.get($(this).attr('href'), function(e) {
                    $('#dpackagew').remove()
                    if (e == 'OK') {
                        completeUpdate = 1;
                        $('#endupdate').append(
                            '<div class="infook">' +
                            update_package_deleted + '<br />' +
                            '<a href="' + URL_GOHOME + '" title="' + gohome + '">' + gohome + '</a> - ' +
                            '<a href="' + URL_GOADMIN + '" title="' + update_goadmin + '">' + update_goadmin + '</a>' +
                            '</div>'
                        );
                    } else {
                        alert(e);
                        $('#endupdate').append(
                            '<div class="infoerror">' +
                            update_package_not_deleted + '<br />' +
                            '<a href="' + URL_GOHOME + '" title="' + gohome + '">' + gohome + '</a> - ' +
                            '<a href="' + URL_GOADMIN + '" title="' + update_goadmin + '">' + update_goadmin + '</a>' +
                            '</div>'
                        );
                    }
                });
            }
        }
        return !1;
    });
});

// Control update task
var NVU = {};
NVU.IsStart = 0;
NVU.IsAlert = 0;
NVU.NextStepUrl = '';
NVU.NavigateConfirm = '';
NVU.NextFuncs = '';
NVU.NextFuncsName = '';
NVU.NextUrl = '';
NVU.update_taskiload = '';
NVU.Start = function() {
    $('#nv-message').hide();
    NVU.IsStart = 1;
    NVU.ShowLoad(NVU.NextFuncsName);
    $('#' + NVU.NextFuncs).removeClass('ierror').removeClass('iok').removeClass('iwarn').addClass('iload').attr('title', NVU.update_taskiload);
    setTimeout("NVU.load()", 1000);
}
NVU.load = function() {
    var url;
    if (NVU.NextUrl == '') {
        url = nv_base_siteurl + 'install/update.php?step=2&substep=3&load=' + NVU.NextFuncs;
    } else {
        url = NVU.NextUrl;
    }

    // Dieu khien
    $.get(url, function(r) {
        var check = r.split('|');
        NVU.HideLoad();

        if (check[0] == undefined || check[1] == undefined || check[2] == undefined || check[3] == undefined || check[4] == undefined || check[5] == undefined || check[6] == undefined || check[7] == undefined) {
            check[6] = '1';
        }

        if (check[0] == '0') {
            NVU.IsAlert = 1;
            if (check[6] == '1') {
                $('#' + NVU.NextFuncs).removeClass('iload').removeClass('iok').removeClass('iwarn').addClass('ierror').attr('title', update_taskierror);
            } else {
                $('#' + NVU.NextFuncs).removeClass('iload').removeClass('iok').removeClass('ierror').addClass('iwarn').attr('title', update_taskiwarn);
            }
        } else {
            $('#' + NVU.NextFuncs).removeClass('iload').removeClass('iwarn').removeClass('ierror').addClass('iok').attr('title', update_taskiok);
        }

        if (check[6] == '1') {
            NVU.SetStop();
        } else if (check[7] == '1') {
            NVU.SetComplete();
        } else {
            NVU.NextFuncs = check[1];
            NVU.NextFuncsName = check[2];
            NVU.NextUrl = '';
            var loadmessage = '';
            if (check[3] != 'NO' && check[3] != '') NVU.NextUrl = check[3];
            if (check[5] != 'NO' && check[5] != '') {
                loadmessage = NVU.NextFuncsName + ' - ' + check[5];
            } else {
                loadmessage = NVU.NextFuncsName;
            }
            NVU.ShowLoad(loadmessage);
            $('#' + NVU.NextFuncs).removeClass('ierror').removeClass('iok').removeClass('iwarn').addClass('iload').attr('title', NVU.update_taskiload);
            setTimeout("NVU.load()", 1000);
        }
    });
}
NVU.SetStop = function() {
    NVU.IsStart = 0;
    $('#nv-message').show().html('<div class="infoerror">' + update_task_do1_error + ' <strong>&quot;' + NVU.NextFuncsName + '&quot;</strong> ' + update_task_do2_error + '</div>');
}
NVU.SetComplete = function() {
    NVU.IsStart = 0;
    var DivClass = 'infook';
    if (NVU.IsAlert == 1) {
        DivClass = 'infoalert';
    }
    $('#nv-message').show().html('<div class="' + DivClass + '">' + ((DivClass == 'infook') ? update_task_all_complete : update_task_all_complete_alert) + '</div>');
    $('#control_t').append('<li><span class="next_step"><a href="' + NVU.NextStepUrl + '">' + next_step + '</a></span></li>');
}
NVU.ShowLoad = function(m) {
    $('#nv-loading').html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt=""/><br />' + update_task_load + ' <strong>' + m + '</strong><br />' + update_task_load_message + '.');
    $('#nv-loading').show();
}
NVU.HideLoad = function() {
    $('#nv-loading').html('');
    $('#nv-loading').hide();
}
NVU.ConfirmExit = function(event) {
    if (NVU.IsStart == 0) {
        event.cancelBubble = true;
    } else {
        return NVU.NavigateConfirm;
    }
}

// Control FTP detected
var NVMF = {};
NVMF.IsStart = 0;
NVMF.ftp_nosupport = document.getElementById('ftp_nosupport');
NVMF.check_ftp = document.getElementById('check_ftp');
NVMF.NavigateConfirm = 'update_nav_confirm';
NVMF.OkMessage = '';
NVMF.Start = function() {
    NVMF.IsStart = 1;
    $('#nv-message').html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Loading..."/><br />' + update_load_waiting);
    if (NVMF.ftp_nosupport) {
        $('#ftp_nosupport').slideUp(400);
    }
    if (NVMF.check_ftp) {
        $('#check_ftp').slideUp(400);
    }
    $('#nv-toolmove').slideUp(200, function() {
        $('#nv-message').slideDown(200, function() {
            $.get(nv_base_siteurl + 'install/update.php?step=2&substep=4&move', function(r) {
                NVMF.IsStart = 0;
                if (r == 'OK') {
                    $('.workitem').removeClass('ierror').removeClass('iload').removeClass('iwarn').addClass('iok');
                    $('#nv-message').html('<div class="infook">' + NVMF.OkMessage + '</div>');
                    $('#control_t').append('<li><span class="next_step"><a href="' + NextStepUrl + '">' + next_step + '</a></span></li>');
                } else {
                    if (NVMF.ftp_nosupport) {
                        $('#ftp_nosupport').slideDown(600);
                    }
                    if (NVMF.check_ftp) {
                        $('#check_ftp').slideDown(600);
                    }
                    $('#nv-message').slideUp(200, function() {
                        $('#nv-toolmove').removeClass('infook').addClass('infoerror').html(r + '<br /><strong><a href="javascript:NVMF.Start();" title="' + update_move_redo + '">' + update_move_redo + '</a></strong><br />' + update_move_redo_message + '<br />' + update_move_redo_manual);
                        $('#nv-toolmove').slideDown(200);
                    });
                }
            });
        });
    });
}
NVMF.ConfirmExit = function(event) {
    if (NVMF.IsStart == 0) {
        event.cancelBubble = true;
    } else {
        return NVMF.NavigateConfirm;
    }
}
