<!-- BEGIN: main -->
<ul class="comment-list">
	<!-- BEGIN: detail -->
	<li class="media" id="cid_{COMMENT.cid}">
		<a class="pull-left" href="#">
			<img class="media-object bg-gainsboro" src="{COMMENT.photo}" alt="{COMMENT.post_name}" width="40"/>
		</a>
		<div class="media-body">
			<div class="margin-bottom">{COMMENT.content}</div>
			<div class="comment-info clearfix">
				<em class="pull-left fa fa-user">&nbsp;</em> <strong class="cm_item">{COMMENT.post_name} </strong>
				<!-- BEGIN: emailcomm -->
			 	<em class="fa fa-envelope-o">&nbsp;</em> <a class="cm_item" title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}">{COMMENT.post_email}</a>
				<!-- END: emailcomm -->
				<em class="fa fa-clock-o">&nbsp;</em> <span class="small">{LANG.pubtime} {COMMENT.post_time}</span>
				<ul class="comment-tool clearfix">
					<!-- BEGIN: delete --><li><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_delete({COMMENT.cid}, '{COMMENT.check_like}')">{LANG.delete}</a></li><!-- END: delete -->
					<li><em class="fa fa-reply">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_feedback({COMMENT.cid}, '{COMMENT.post_name}')">{LANG.feedback}</a></li>
					<li><em class="fa fa-thumbs-o-up">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_like({COMMENT.cid}, '{COMMENT.check_like}', '1')">{LANG.like}</a> <span id="like{COMMENT.cid}">{COMMENT.likes}</span></li>
					<li><em class="fa fa-thumbs-o-down">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_like({COMMENT.cid}, '{COMMENT.check_like}', '-1')">{LANG.dislike}</a> <span id="dislike{COMMENT.cid}">{COMMENT.dislikes}</span></li>
                    <!-- BEGIN: attach --><li><a href="{COMMENT.attach}" rel="nofollow"><i class="fa fa-fw fa-download"></i>{LANG.attachdownload}</a></li><!-- END: attach -->
				</ul>
			</div>
            <!-- BEGIN: children -->
            {CHILDREN}
            <!-- END: children -->
		</div>
	</li>
	<!-- END: detail -->
</ul>
<div class="text-center">
	{PAGE}
</div>
<!-- END: main -->

<!-- BEGIN: children -->
<ul class="comment-list">
	<!-- BEGIN: detail -->
	<li class="media" id="cid_{COMMENT.cid}">
		<div class="media-body">
			<div class="margin-bottom">{COMMENT.content}</div>
			<div class="comment-info clearfix">
				<em class="pull-left fa fa-user">&nbsp;</em> <strong class="cm_item">{COMMENT.post_name} </strong>
				<!-- BEGIN: emailcomm -->
			 	<em class="fa fa-envelope-o">&nbsp;</em> <a class="cm_item" title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}">{COMMENT.post_email}</a>
				<!-- END: emailcomm -->
				<em class="fa fa-clock-o">&nbsp;</em> <span class="small">{LANG.pubtime} {COMMENT.post_time}</span>
				<ul class="comment-tool">
					<!-- BEGIN: delete --><li><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_delete({COMMENT.cid}, '{COMMENT.check_like}')">{LANG.delete}</a></li><!-- END: delete -->
					<li><em class="fa fa-reply">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_feedback({COMMENT.cid}, '{COMMENT.post_name}')">{LANG.feedback}</a></li>
					<li><em class="fa fa-thumbs-o-up">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_like({COMMENT.cid}, '{COMMENT.check_like}', '1')">{LANG.like}</a> <span id="like{COMMENT.cid}">{COMMENT.likes}</span></li>
					<li><em class="fa fa-thumbs-o-down">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_commment_like({COMMENT.cid}, '{COMMENT.check_like}', '-1')">{LANG.dislike}</a> <span id="dislike{COMMENT.cid}">{COMMENT.dislikes}</span></li>
                    <!-- BEGIN: attach --><li><a href="{COMMENT.attach}" rel="nofollow"><i class="fa fa-fw fa-download"></i>{LANG.attachdownload}</a></li><!-- END: attach -->
				</ul>
			</div>
		</div>
        <!-- BEGIN: children -->
        {CHILDREN}
        <!-- END: children -->
	</li>
	<!-- END: detail -->
</ul>
<div class="text-center">
	{PAGE}
</div>
<!-- END: children -->