<!-- BEGIN: main -->
<div style="margin-top:5px;margin-bottom:35px;">
    <a class="button1" href="{COMMENT_STATUS0_HREF}"><span><span>{LANG.comment_st0}</span></span></a>
    <a class="button1" href="{COMMENT_STATUS1_HREF}"><span><span>{LANG.comment_st1}</span></span></a>
    <a class="button1" href="{COMMENT_STATUS2_HREF}"><span><span>{LANG.comment_st2}</span></span></a>
</div>
<div class="clear"></div>
<!-- BEGIN: row -->
<table class="tab1" style="margin-bottom:5px">
    <thead>
        <tr>
            <td colspan="2">
                <div style="width:35%;position:absolute;right:10px">
                    <div style="position:absolute;right:0px">
                        <a class="button2" href="{ROW.edit_href}"><span><span>{GLANG.edit}</span></span></a>
                        <a class="button2" href="javascript:void(0);" onclick="nv_comment_del({ROW.id});"><span><span>{GLANG.delete}</span></span></a>
                    </div>
                    <span style="font-weight:normal">{GLANG.status}:</span> 
                    <select name="status" id="status{ROW.id}" onchange="nv_chang_comment_status({ROW.id});">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.key}"{STATUS.selected}>{STATUS.value}</option>
                        <!-- END: status -->
                    </select>
                </div>
                <strong>{ROW.subject}</strong>
            </td>
        </tr>
    </thead>
    
    <tbody{CLASS}>
        <tr>
            <td style="width:25%;background-color:#DBDBDB;vertical-align:top">
                {LANG.comment_post_name}: <strong>{ROW.post_name}</strong><br />
                {LANG.comment_post_email}: {ROW.post_email}<br />
                {LANG.comment_post_ip}: {ROW.post_ip}
            </td>
            <td style="vertical-align:top">
                <div style="padding-bottom:15px">
                <div style="position:absolute;right:10px">
                {ROW.post_time}
                </div>
                {LANG.comment_of_file2}: <strong>{ROW.file}</strong> ({ROW.comments_of_file})
                </div>
                {ROW.comment}
                <!-- BEGIN: admin_reply -->
                <div style="padding-top:15px">
                <em><strong>{LANG.comment_admin_reply} {ROW.admin_id}</strong>: {ROW.admin_reply}</em>
                </div>
                <!-- END: admin_reply -->
            </td>
        </tr>
    </tbody>
</table>
<!-- END: row -->
<!-- BEGIN: generate_page -->
<div style="margin-top:8px;">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
