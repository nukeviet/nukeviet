<!-- BEGIN: main -->
<ul class="media-list comment-list list-none list-items">
    <!-- BEGIN: loop -->
    <li class="media">
        <div class="media-body">
            <div class="comment-info">
                <em class="fa fa-user">&nbsp;</em> <strong class="cm_item">{COMMENT.post_name} </strong>
                <!-- BEGIN: emailcomm -->
                 <em class="fa fa-envelope-o">&nbsp;</em> <a class="cm_item" title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}">{COMMENT.post_email}</a>
                <!-- END: emailcomm -->
                <em class="fa fa-clock-o">&nbsp;</em> <span class="small">{LANG.pubtime} {COMMENT.post_time}</span>
            </div>
            <p><a href="{COMMENT.url_comment}#idcomment">{COMMENT.content}</a></p>
        </div>
    </li>
    <!-- END: loop -->
</ul>
<div class="text-center">
    {PAGE}
</div>
<!-- END: main -->
