<!-- BEGIN: main -->
<div id="users">
	<div class="page-header m-bottom">
		<h3>{LANG.user_info}</h3>
	</div>
	<div class="table box-border-shadow">
		<div class="content-box h-info">
			<div class="left fl">
				<img src="{SRC_IMG}" alt="" class="s-border" />
				<br />
				<span class="small">{LANG.img_size_title}</span>
			</div>
			<div class="fl">
				{LANG.account2}: <strong>{USER.username}</strong>
				<!-- BEGIN: viewemail -->
				({USER.email})
				<!-- END: viewemail -->
				<br />
				{LANG.last_login}: {USER.last_login}
				<br />
			</div>
			<div class="clear"></div>
		</div>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.name}:
			</dt>
			<dd class="fl">
				{USER.full_name}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.birthday}:
			</dt>
			<dd class="fl">
				{USER.birthday}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.gender}:
			</dt>
			<dd class="fl">
				{USER.gender}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.regdate}:
			</dt>
			<dd class="fl">
				{USER.regdate}
			</dd>
		</dl>
		<!-- BEGIN: field -->
		<!-- BEGIN: loop -->
		<dl class="clearfix">
			<dt class="fl">{FIELD.title}</dt>
			<dd class="fl">{FIELD.value}</dd>
		</dl>
		<!-- END: loop -->
		<!-- END: field -->
	</div>
</div>
<!-- END: main -->