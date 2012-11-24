<!-- BEGIN: main -->
<!-- BEGIN: if_not_empty -->
<!-- BEGIN: detail -->
        <div class="box-border content-box clearfix">
            <div class="comment-content">
                <strong>{ROW.subject}</strong>
                - <span class="small">{ROW.post_time}</span>
                <br/>
                {ROW.comment}
                    <!-- BEGIN: is_admin -->
                        (<a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; 
                        <a href="{ROW.del_link}" onclick="nv_comment_del(this,{ROW.id});return false;">{GLANG.delete}</a>)
                    <!-- END: is_admin -->
            </div>
        </div>
<!-- END: detail -->
<!-- BEGIN: generate_page -->
<div class="page-nav m-bottom aright">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: if_not_empty -->
<!-- END: main -->