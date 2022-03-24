<!-- BEGIN: step -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#check_database").validate();
    });
</script>
<form id="check_database" action="{ACTIONFORM}" method="post">
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
        <td><select name="dbtype" data-url="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5">
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
            <!-- BEGIN: db_detete -->
            <input type="checkbox" name="db_detete" id="db_detete" value="1" class="checkbox"/><label for="db_detete">{LANG.db_detete}</label> &nbsp; &nbsp;
            <!-- END: db_detete -->
            <input class="button" type="submit" value="{LANG.refesh}" />
        </td>
    </tr>
</table>
<!-- BEGIN: errordata --><span class="highlight_red">{DATADASE.error}</span>
<!-- END: errordata --></form>
<ul class="control_t fr">
    <li><span class="back_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=4&t={NV_CURRENTTIME}">{LANG.previous}</a></span>
    </li>
    <!-- BEGIN: nextstep -->
    <li><span class="next_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=6&t={NV_CURRENTTIME}">{LANG.next_step}</a></span>
    </li>
    <!-- END: nextstep -->
</ul>
<script type="text/javascript">
//<![CDATA[
document.getElementById('check_database').setAttribute("autocomplete", "off");
//]]>
</script>
<!-- END: step -->