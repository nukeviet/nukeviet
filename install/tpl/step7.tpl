<!-- BEGIN: step -->
<blockquote>{LANG.spdata_note}</blockquote>
<table cellspacing="0" summary="{LANG.website_info}">
    <tr>
        <th scope="col" class="nobg" style="width: 200px;">&nbsp;</th>
        <th scope="col">{LANG.spdata_name}</th>
        <th scope="col">{LANG.note}</th>
    </tr>
    <!-- BEGIN: loop -->
    <tr>
        <th scope="row" class="spec">
            <a href="{ROW.link}" class="button fr">{LANG.spdata_choose}</a>
        </th>
        <td>
            <strong>{ROW.title}</strong>
        </td>
        <td>{MESSAGE}</td>
    </tr>
    <!-- END: loop -->
</table>
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