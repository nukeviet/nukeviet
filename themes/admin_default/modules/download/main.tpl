<!-- BEGIN: main -->
<table class="tab1">
    <thead>
        <tr>
            <td>
                {LANG.file_title}
            </td>
            <td>
                {LANG.category_cat_parent}
            </td>
            <td style="width:100px;white-space:nowrap;text-align:center">
                {LANG.file_update}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {LANG.file_view_hits}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {LANG.file_download_hits}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {LANG.file_comment_hits}
            </td>
            <td style="width:60px;white-space:nowrap;text-align:center">
                {LANG.file_active}
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
                {ROW.uploadtime}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {ROW.view_hits}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {ROW.download_hits}
            </td>
            <td style="width:40px;white-space:nowrap;">
                {ROW.comment_hits}
            </td>
            <td style="width:60px;white-space:nowrap;text-align:center">
                <input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_file_status({ROW.id})" />
            </td>
            <td style="width:150px;white-space:nowrap;text-align:center">
                <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_file_del({ROW.id});">{GLANG.delete}</a></span>
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
<div style="margin-top:8px">
    <a class="button1" href="{ADD_NEW_FILE}"><span><span>{LANG.file_addfile}</span></span></a>
</div>
<!-- END: main -->
