<!-- BEGIN: main -->
<div id="users">
	<div class="page-header">
		<h3>{LANG.openid_administrator}</h3>
	</div>
	<ul class="list-tab top-option clearfix">
		<li>
			<a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
		</li>
		<li>
			<a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
		</li>
		<li>
			<a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
		</li>
		<li class="ui-tabs-selected">
			<a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
		</li>
		<!-- BEGIN: regroups -->
		<li>
			<a href="{URL_HREF}regroups">{LANG.in_group}</a>
		</li>
		<!-- END: regroups -->
		<!-- BEGIN: logout -->
		<li>
			<a href="{URL_HREF}logout">{LANG.logout_title}</a>
		</li>
		<!-- END: logout -->
	</ul>
	<div class="box-border-shadow">
		<div class="acenter">
			<img alt="{LANG.openid_administrator}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
		</div>
		<!-- BEGIN: openid_empty -->
		<form id="openidForm" action="{FORM_ACTION}" method="post">
			<!-- BEGIN: openid_list -->
			<dl class="clearfix{OPENID_CLASS}">
				<dt class="fl">
					<input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}" style="padding-right:5px"{OPENID_LIST.disabled} />
				</dt>
				<dd class="fl">
					<a href="javascript:void(0);" title="{OPENID_LIST.openid}">{OPENID_LIST.server}</a>
				</dd>
				<dd class="fr">
					{OPENID_LIST.email}
				</dd>
			</dl>
			<!-- END: openid_list -->
			<input id="submit" type="submit" class="button" value="{LANG.openid_del}" />
		</form>
		<!-- END: openid_empty -->
		<div class="m-bottom acenter">
			<p>
				{DATA.info}
			</p>
			<!-- BEGIN: server -->
			<a title="{OPENID.title}" href="{OPENID.href}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
			<!-- END: server -->
		</div>
	</div>

</div>
<!-- END: main -->