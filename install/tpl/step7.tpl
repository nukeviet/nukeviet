<!-- BEGIN: step -->
<blockquote>{LANG.spdata_note}</blockquote>
<form method="post" action="{ACTIONFORM}">
    <table cellspacing="0" summary="{LANG.website_info}">
        <tr>
            <th scope="col" class="nobg" style="width: 200px;">&nbsp;</th>
            <th scope="col">{LANG.spdata_name}</th>
            <th scope="col">{LANG.note}</th>
        </tr>
        <!-- BEGIN: loop -->
        <tr>
            <th scope="row" class="spec center">
                <input type="radio" name="package" id="package{ROWKEY}" value="{ROW.title}"/>
            </th>
            <td>
                <strong><label for="package{ROWKEY}">{ROW.title}</label></strong>
            </td>
            <td>{MESSAGE}</td>
        </tr>
        <!-- END: loop -->
        <tr>
            <th class="spec center">
                <input type="submit" name="submit" class="button" value="{LANG.spdata_choose}"/>
            </th>
            <td colspan="2"></td>
        </tr>
    </table>
</form>
<ul class="control_t fr">
    <!-- BEGIN: nextstep -->
    <li>
        <span class="next_step"><a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=8">{LANG.next_step}</a></span>
    </li>
    <!-- END: nextstep -->
</ul>
<script type="text/javascript">
//<![CDATA[
document.getElementById('site_config').setAttribute("autocomplete", "off");
//]]>
</script>
<!-- END: step -->