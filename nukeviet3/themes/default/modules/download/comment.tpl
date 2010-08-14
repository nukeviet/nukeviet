<!-- BEGIN: main -->
<div>
    <!-- BEGIN: if_not_empty -->
    <div class="box silver div_comm">
        <h3 class="header"><strong>&bull;</strong>{LANG.file_comment_title}</h3>
        <div class="div_comments">
        	<!-- BEGIN: detail -->
        	<div class="div_detail">
                <div class="title">
                    {ROW.subject}
                </div>
                <div class="post_name">
                    <dl class="clearfix">
                        <dd class="fl">
                            {ROW.post_name}
                        </dd>
                        <dt class="fr">
                            {ROW.post_time}
                        </dt>
                    </dl>    
                </div>
                <div class="comm_content">
                    {ROW.comment}
                    <!-- BEGIN: is_admin -->
                        (<a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; 
                        <a href="{ROW.del_link}" onclick="nv_comment_del(this,{ROW.id});return false;">{GLANG.delete}</a>)
                    <!-- END: is_admin -->
                </div>
                
                
        	</div>
        	<!-- END: detail -->
        </div>
        <div class="list_footer">
            <!-- BEGIN: generate_page -->
            {GENERATE_PAGE}
            <!-- END: generate_page -->
        </div>
    </div>
    <!-- END: if_not_empty -->
</div>
<!-- END: main -->