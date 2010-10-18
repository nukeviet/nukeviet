<!-- BEGIN: main -->
<!-- BEGIN: detail -->
<div class="box-border content-box{COMMENT.bg}">
    <div class="ava">
        <a href="#"><img src="{COMMENT.photo}" alt="Avata" class="s-border" /></a>
    </div>
    <div class="comment-content">
        <strong>{COMMENT.post_name}</strong>
        <!-- BEGIN: emailcomm --> - <a title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}"><span class="email">{COMMENT.post_email}</span></a>
        <!-- END: emailcomm --> - <span class="small">{LANG.pubtime}: {COMMENT.post_time}</span>
        <br/>
        {COMMENT.content}
    </div>
    <div class="clear">
    </div>
</div>
<!-- END: detail -->
<div class="page">
    {PAGE}
</div>
<!-- END: main -->