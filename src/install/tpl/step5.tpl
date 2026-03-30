<!-- BEGIN: step -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#check_database").validate();
    });
</script>
<form id="check_database" action="{ACTIONFORM}" method="post">
<input type="text" value="" id="__fake_username" style="display:none"/>
<input type="password" value="" id="__fake_password" style="display:none"/>
<table id="database_config" cellspacing="0" summary="{LANG.database}">
    <caption>{LANG.properties} <span class="highlight_red">*</span>
    {LANG.is_required}</caption>
    <tr>
        <th scope="col" class="nobg w150">&nbsp;</th>
        <th scope="col">{LANG.database_config}</th>
        <th scope="col">{LANG.note}</th>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.database_type} <span
            class="highlight_red">*</span></th>
        <td><select name="dbtype" data-url="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5&amp;t={NV_CURRENTTIME}">
            <!-- BEGIN: dbtype -->
            <option value="{DBTYPE.value}" {DBTYPE.selected}>{DBTYPE.text}</option>
            <!-- END: dbtype -->
        </select> <img id="dbtype-check" class="hide" src="{BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif"></td>
        <td>{LANG.database_default} <strong>MySQL</strong></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.host_name} <span
            class="highlight_red">*</span></th>
        <td class="alt"><input type="text" value="{DATADASE.dbhost}" name="dbhost" class="required w120" /> Port:<input type="text" value="{DATADASE.dbport}" name="dbport" style="width: 40px;text-align: right"/></td>
        <td class="alt">{LANG.host_name_note} <strong>localhost</strong>.</td>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.db_username} <span
            class="highlight_red">*</span></th>
        <td><input type="text" value="{DATADASE.dbuname}" name="dbuname" class="required w200" /></td>
        <td>{LANG.db_username_note}.</td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.db_pass}</th>
        <td class="alt"><input type="password" autocomplete="off" value="{DATADASE.dbpass}" name="dbpass"  class="w200" /></td>
        <td class="alt">{LANG.db_pass_note}</td>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.db_name}<span class="highlight_red">*</span>
        </th>
        <td><input type="text" value="{DATADASE.dbname}" name="dbname" class="required w200" /></td>
        <td>{LANG.db_name_note}</td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.prefix} <span
            class="highlight_red">*</span></th>
        <td class="alt"><input type="text" value="{DATADASE.prefix}" name="prefix" class="required w200" /></td>
        <td class="alt">&nbsp;</td>
    </tr>
    <tr>
        <th class="spec"></th>
        <td class="spec" colspan="2">
            <div id="db_detete_wrap" style="display:none; margin-bottom:8px;">
                <span class="highlight_red" style="display:block; margin-bottom:6px;">{LANG.db_err_prefix}</span>
                <input type="checkbox" name="db_detete" id="db_detete" value="1" class="checkbox"/><label for="db_detete">{LANG.db_detete}</label> &nbsp; &nbsp;
            </div>
            <input class="button" type="submit" value="{LANG.refesh}" id="btn_install_db" />
        </td>
    </tr>
</table>
<!-- BEGIN: errordata --><span class="highlight_red">{DATADASE.error}</span>
<!-- END: errordata --></form>

<!-- Khu vực hiển thị tiến trình cài đặt (ẩn ban đầu) -->
<div id="nv_install_progress" style="display:none; margin-top:20px;">
    <table cellspacing="0" style="width:100%">
        <caption id="nv_progress_caption">{LANG.installing_db}</caption>
        <tr>
            <td>
                <div id="nv_progress_bar_wrap" style="border:1px solid #ccc; border-radius:4px; height:22px; background:#f5f5f5; overflow:hidden; margin-bottom:10px;">
                    <div id="nv_progress_bar" style="height:22px; width:0%; background:#4caf50; transition:width 0.3s; text-align:center; line-height:22px; color:#fff; font-size:12px;">0%</div>
                </div>
                <div id="nv_progress_log" style="max-height:180px; overflow-y:auto; border:1px solid #e5e5e5; padding:8px; background:#fff; font-size:12px; font-family:monospace;"></div>
            </td>
        </tr>
    </table>
</div>

<ul class="control_t fr">
    <li><span class="back_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=4&amp;t={NV_CURRENTTIME}">{LANG.previous}</a></span>
    </li>
    <!-- BEGIN: nextstep -->
    <li><span class="next_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=6&amp;t={NV_CURRENTTIME}">{LANG.next_step}</a></span>
    </li>
    <!-- END: nextstep -->
</ul>
<script type="text/javascript">
//<![CDATA[
document.getElementById('check_database').setAttribute("autocomplete", "off");

(function($){
    var ajaxUrl = '{ACTIONFORM}';
    var formData = null;

    function logMsg(msg, isError) {
        var $log = $('#nv_progress_log');
        var color = isError ? '#c00' : '#333';
        $log.append('<div style="color:' + color + '">' + msg + '</div>');
        $log.scrollTop($log[0].scrollHeight);
    }

    function setProgress(percent, caption) {
        $('#nv_progress_bar').css('width', percent + '%').text(percent + '%');
        if (caption) $('#nv_progress_caption').text(caption);
    }

    function doInstallModules(modules, idx, total, afterDone) {
        if (idx >= modules.length) {
            afterDone();
            return;
        }
        var mod = modules[idx];
        var percent = Math.round(30 + ((idx + 1) / total) * 60);
        setProgress(percent, '{LANG.installing_module}'.replace('%s', mod.title));
        logMsg('{LANG.installing_module}'.replace('%s', '<strong>' + mod.title + '</strong> (' + mod.module_file + ')'));

        setTimeout(function() {
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: formData + '&ajax_action=module&module_title=' + encodeURIComponent(mod.title) + '&module_file=' + encodeURIComponent(mod.module_file),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        doInstallModules(modules, idx + 1, total, afterDone);
                    } else {
                        logMsg('{LANG.install_error}: ' + (res.message || ''), true);
                    }
                },
                error: function(xhr) {
                    logMsg('{LANG.install_error}: HTTP ' + xhr.status, true);
                }
            });
        }, 800);
    }

    $('#check_database').on('submit', function(e) {
        e.preventDefault();

        if (!$(this).valid()) return;

        formData = $(this).serialize();

        // Ẩn form, hiện progress
        $('#check_database').hide();
        $('ul.control_t').hide();
        $('#db_detete_wrap').hide();
        $('#nv_install_progress').show();
        setProgress(5, '{LANG.installing_db}');
        logMsg('{LANG.installing_db}...');

        // Bước A: Tạo CSDL hệ thống
        var dbDetete = (document.getElementById('db_detete') && document.getElementById('db_detete').checked) ? '&db_detete=1' : '';
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData + '&ajax_action=system' + dbDetete,
            dataType: 'json',
            success: function(res) {
                if (res.has_table) {
                    // DB đã có bảng cũ, yêu cầu xác nhận xóa
                    setProgress(0, '');
                    $('#nv_install_progress').hide();
                    $('#db_detete_wrap').show();
                    $('#check_database').show();
                    $('ul.control_t').show();
                    return;
                }
                if (res.status !== 'success') {
                    logMsg('{LANG.install_error}: ' + (res.message || ''), true);
                    $('#check_database').show();
                    $('ul.control_t').show();
                    $('#nv_install_progress').hide();
                    return;
                }

                var modules = res.modules || [];
                var total = modules.length;
                setProgress(30, '{LANG.installing_modules}');
                logMsg('{LANG.installing_modules} (' + total + ')...');

                // Bước B: Cài từng module
                doInstallModules(modules, 0, total, function() {
                    setProgress(92, '{LANG.finalizing}');
                    logMsg('{LANG.finalizing}...');

                    // Bước C: Hoàn tất
                    $.ajax({
                        url: ajaxUrl,
                        type: 'POST',
                        data: formData + '&ajax_action=finish',
                        dataType: 'json',
                        success: function(res2) {
                            if (res2.status === 'success') {
                                setProgress(100, '{LANG.install_done}');
                                logMsg('{LANG.install_done}');
                                setTimeout(function(){
                                    window.location.href = res2.redirect;
                                }, 800);
                            } else {
                                logMsg('{LANG.install_error}: ' + (res2.message || ''), true);
                            }
                        },
                        error: function(xhr) {
                            logMsg('{LANG.install_error}: HTTP ' + xhr.status, true);
                        }
                    });
                });
            },
            error: function(xhr) {
                logMsg('{LANG.install_error}: HTTP ' + xhr.status, true);
                $('#check_database').show();
                $('ul.control_t').show();
                $('#nv_install_progress').hide();
            }
        });
    });
})(jQuery);
//]]>
</script>
<!-- END: step -->
