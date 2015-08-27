<!-- BEGIN: main -->
<div class="page">
    <ul class="nav nav-tabs m-bottom">
    	<li><a href="{URL_MODULE}">{LANG.user_info}</a></li>
    	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
    	<li class="active"><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li>
    	<!-- BEGIN: regroups --><li><a href="{URL_HREF}editinfo/group">{LANG.in_group}</a></li><!-- END: regroups -->
    	<li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li>
    </ul>
    <h2>{LANG.openid_administrator}</h2>
    <p class="text-center">
    	<img alt="{LANG.openid_administrator}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />

    </p>
    <!-- BEGIN: openid_empty -->
    <form id="openidForm" action="{FORM_ACTION}" method="post" role="form" class="m-bottom">
    	<table class="table table-bordered table-striped table-hover">
    		<tbody>
    			<!-- BEGIN: openid_list -->
    			<tr>
    				<th class="text-center"><input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}"{OPENID_LIST.disabled} /></th>
    				<td>{OPENID_LIST.openid}</td>
    				<td>{OPENID_LIST.email}</td>
    			</tr>
    			<!-- END: openid_list -->
    		</tbody>
    	</table>
    	<p>
    		<input id="submit" type="submit" class="btn btn-primary" value="{LANG.openid_del}" />
    	</p>
    </form>
    <!-- END: openid_empty -->
    <div class="text-center m-bottom">
    	<p>
    		{DATA.info}
    	</p>
    	<!-- BEGIN: server -->
    	<a href="{OPENID.href}"><img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
    	<!-- END: server -->
    </div>
</div>
<!-- END: main -->