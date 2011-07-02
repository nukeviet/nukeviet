<!-- BEGIN: main -->
<table class="tab1">
    <caption>{TABLE_CAPTION}</caption>
    <thead>
        <tr>
            <!-- BEGIN: is_cat1 -->
            <td>
                {LANG.faq_pos}
            </td>
            <!-- END: is_cat1 -->
            <td>
                {LANG.faq_title_faq}
            </td>
            <td>
                {LANG.faq_catid_faq}
            </td>
            <td style="width:60px;white-space:nowrap;text-align:center">
                {LANG.faq_active}
            </td>
            <td style="width:150px;white-space:nowrap;text-align:center">
                {LANG.faq_feature}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: row -->
    <tbody{CLASS}>
        <tr>
            <!-- BEGIN: is_cat2 -->
            <td style="width:15px">
                <select name="weight" id="weight{ROW.id}" onchange="nv_chang_row_weight({ROW.id});">
                    <!-- BEGIN: weight -->
                    <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                    <!-- END: weight -->
                </select>
            </td>
            <!-- END: is_cat2 -->
            <td>
                {ROW.title}
            </td>
            <td>
                <a href="{ROW.catlink}">{ROW.cattitle}</a>
            </td>
            <td style="width:60px;white-space:nowrap;text-align:center">
                <input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_row_status({ROW.id})" />
            </td>
            <td style="width:150px;white-space:nowrap;text-align:center">
                <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_row_del({ROW.id});">{GLANG.delete}</a></span>
            </td>
        </tr>
    </tbody>
    <!-- END: row -->
    <!-- BEGIN: generate_page -->
    <tr class="footer">
        <td colspan="8">
            {GENERATE_PAGE}
        </td>
    </tr>
    <!-- END: generate_page -->
</table>
<div style="margin-top:8px;">
    <a class="button1" href="{ADD_NEW_FAQ}"><span><span>{LANG.faq_addfaq}</span></span></a>
</div>
<!-- END: main -->
