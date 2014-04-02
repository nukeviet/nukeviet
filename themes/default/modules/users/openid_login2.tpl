<!-- BEGIN: main -->
<h2>{LANG.openid_login}</h2>
<p class="text-center">
	<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
</p>
<p>
	{LANG.openid_note2}:
</p>
<ul class="nv-list-item m-bottom">
	<!-- BEGIN: login_note -->
	<li><em class="fa fa-angle-right">&nbsp;</em> <a href="{USER_LOGIN.link}">{USER_LOGIN.title}</a></li>
	<!-- END: login_note -->
</ul>
<div class="form-tooltip text-center well">
	<!-- BEGIN: server -->
	<a href="{OPENID.href}">&nbsp;<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>&nbsp;</a>
	<!-- END: server -->
</div>

<div id="users">

</div>
<!-- END: main -->