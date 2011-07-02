<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
        <tbody>
            <tr>
                <td>
                    {LANG.faq_category_cat_name}
                </td>
                <td>
                    <input class="txt" value="{DATA.title}" name="title" id="title" style="width:300px" maxlength="100" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.faq_description}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.description}" name="description" style="width:300px" maxlength="255" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.faq_category_cat_parent}
                </td>
                <td>
                    <select name="parentid">
                        <!-- BEGIN: parentid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: parentid -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.faq_who_view}
                </td>
                <td>
                    <select name="who_view">
                        <!-- BEGIN: who_view -->
                        <option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
                        <!-- END: who_view -->
                    </select>
                    <!-- BEGIN: group_view_empty -->
                    <br />
                    {LANG.groups_upload}<br />
                        <!-- BEGIN: groups_view -->
                        <input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}<br />
                        <!-- END: groups_view -->
                    <!-- END: group_view_empty -->
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="{LANG.faq_cat_save}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: main -->
