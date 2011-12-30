<!-- BEGIN: main -->
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
                {LANG.file_update}
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
            <td style="width:150px;white-space:nowrap;text-align:center">
                <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_filequeue_del({ROW.id});">{GLANG.delete}</a></span>
            </td>
        </tr>
    </tbody>
    <!-- END: row -->
</table>
<div style="margin-top:8px;">
    <a class="button1" href="javascript:void(0);" onclick="nv_filequeue_alldel();"><span><span>{LANG.download_alldel}</span></span></a>
</div>
<!-- END: main -->
