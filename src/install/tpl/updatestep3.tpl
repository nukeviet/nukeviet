<!-- BEGIN: module_info -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
    <tr>
        <th scope="col" abbr="{LANG.update_mod_list}" class="nobg">{LANG.update_mod_list}</th>
        <th scope="col">{LANG.update_mod_version}</th>
        <th scope="col">{LANG.update_mod_author}</th>
        <th scope="col">{LANG.update_mod_note}</th>
    </tr>
    <!-- BEGIN: loop -->
    <tr>
        <th scope="col" class="spec text_normal">{ROW.name}</th>
        <td><span class="highlight_green">{ROW.version} ({ROW.date})</span></td>
        <td><span class="highlight_green">{ROW.author}</span></td>
        <td><span class="highlight_green">{ROW.note}</span></td>
    </tr>
    <!-- END: loop -->
</table>
<!-- END: module_info -->
<!-- BEGIN: version_info -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
    <tr>
        <th scope="col" abbr="{LANG.update_step_title_1}" class="nobg" style="width:380px">{LANG.update_step_title_1}</th>
        <th scope="col">{LANG.update_value}</th>
    </tr>
    <tr>
        <th scope="col" class="spec text_normal">{LANG.update_current_version}</th>
        <td><span class="highlight_green">{DATA.current_version}</span></td>
    </tr>
    <tr>
        <th scope="col" class="specalt text_normal">{LANG.update_lastest_version}</th>
        <td><span class="highlight_green">{DATA.newVersion}</span></td>
    </tr>
</table>
<!-- BEGIN: checkversion -->
<div class="infoerror">
    {LANG.update_check_version}
</div>
<!-- END: checkversion -->
<!-- END: version_info -->
<!-- BEGIN: commodule -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
    <tr>
        <th scope="col" abbr="{LANG.update_step_title_1}" class="nobg" style="width:380px">{LANG.update_step_title_1}</th>
        <th scope="col">{LANG.update_value}</th>
    </tr>
    <tr>
        <th scope="col" class="spec text_normal">{LANG.update_current_version}</th>
        <td><span class="highlight_green">{CONFIG.to_version}</span></td>
    </tr>
    <tr>
        <th scope="col" class="specalt text_normal">{LANG.update_lastest_version}</th>
        <td><span class="highlight_green">{LASTEST_VERSION}</span></td>
    </tr>
</table>
<!-- BEGIN: notcertified -->
<div class="infoerror">
    {LANG.updatemod_notcertified}
</div>
<!-- END: notcertified -->
<!-- BEGIN: checkversion -->
<div class="infoerror">
    {LANG.update_check_version}
</div>
<!-- END: checkversion -->
<!-- END: commodule -->
<!-- BEGIN: main -->
<div class="infook">
    {LANG.update_info_complete}
</div>
<!-- BEGIN: typemodule -->
<script type="text/javascript">
$(window).on('load', function() {
    $('#versioninfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=module');
});
</script>
<div id="versioninfo">
    <div class="infoalert">    
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..."/><br />
        {LANG.update_waiting}
    </div>
</div>
<!-- END: typemodule -->
<!-- BEGIN: typefull -->
<script type="text/javascript">
function LoadModInfo(){
    $('#modinfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=mod');
}
$(window).on('load', function() {
    $('#versioninfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=ver', function(){
        $('#versioninfo').append(
            '<div id="modinfo">' +
                '<div class="infoalert">' +
                    '<img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..."/><br />' +
                    '{LANG.update_waiting_continue}' +
                '</div>' +
            '</div>'
        );
        setTimeout( "LoadModInfo()", 1000 );
    });
});
</script>
<div id="versioninfo">
    <div class="infoalert">    
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..."/><br />
        {LANG.update_waiting}
    </div>
</div>
<!-- END: typefull -->
<div id="endupdate">
    <div class="infoalert" id="infodetectedupg">
        {LANG.update_info_end}<br />
        <strong><a class="delete_update_backage_end" href="{URL_DELETE}" title="{LANG.update_package_delete}">{LANG.update_package_delete}</a></strong>
        <script type="text/javascript">
        var completeUpdate = 0;
        var URL_GOHOME = '{URL_GOHOME}';
        var URL_GOADMIN = '{URL_GOADMIN}';
        var update_package_deleted = '{LANG.update_package_deleted}';
        var gohome = '{LANG.gohome}';
        var update_goadmin = '{LANG.update_goadmin}';
        </script>
    </div>
</div>
<!-- END: main -->