<!-- BEGIN: main -->
<input name="report_check_ok" id="report_check_ok" type="hidden" value="{LANG.report_check_ok}" />
<input name="report_check_error" id="report_check_error" type="hidden" value="{LANG.report_check_error}" />
<input name="report_check_error2" id="report_check_error2" type="hidden" value="{LANG.report_check_error2}" />
<table class="tab1">
    <caption>{TABLE_CAPTION}</caption>
    <thead>
        <tr>
            <td>
                {LANG.file_title}
            </td>
            <td>
                {LANG.category_cat_parent}
            </td>
            <td style="width:100px;white-space:nowrap;text-align:center">
                {LANG.report_post_time}
            </td>
            <td style="width:150px;white-space:nowrap;text-align:center">
                {LANG.file_feature}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: row -->
    <tbody{CLASS}>
        <tr>
            <td>
                <strong>{ROW.title}</strong>
            </td>
            <td>
                <a href="{ROW.catlink}">{ROW.cattitle}</a>
            </td>
            <td style="width:100px;white-space:nowrap;text-align:center">
                {ROW.post_time}
            </td>
            <td style="width:150px;white-space:nowrap;text-align:center">
                <span class="search_icon"><a href="javascript:void(0);" onclick="nv_report_check({ROW.id});">{LANG.file_checkUrl}</a></span>
                &nbsp;&nbsp;<span class="edit_icon"><a href="javascript:void(0);" onclick="nv_report_edit({ROW.id});">{GLANG.edit}</a></span>
                &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_report_del({ROW.id});">{GLANG.delete}</a></span>
            </td>
        </tr>
    </tbody>
    <!-- END: row -->
</table>
<div style="margin-top:8px;">
    <a class="button1" href="javascript:void(0);" onclick="nv_report_alldel();"><span><span>{LANG.download_alldel}</span></span></a>
</div>
<!-- END: main -->
