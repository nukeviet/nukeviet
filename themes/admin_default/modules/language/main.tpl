<!-- BEGIN: activelang -->
<br />
<br />
<br />
<p class="text-center">
	{LANG.nv_setting_save}
</p>
<meta http-equiv="Refresh" content="1;URL={URL}" />
<!-- END: activelang -->
<!-- BEGIN: contents_setup -->
<br />
<br />
<div class="text-center">
	<strong>{LANG.nv_data_setup_ok}</strong>
</div>
<meta http-equiv="Refresh" content="5;URL={URL}" />
<!-- END: contents_setup -->
<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.nv_lang_key}</th>
				<th>{LANG.nv_lang_name}</th>
				<th>{LANG.nv_lang_slsite}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.keylang}</td>
				<td>{ROW.name}</td>
				<td align="center">
				<!-- BEGIN: allow_sitelangs_note -->
				{LANG.site_lang}
				<!-- END: allow_sitelangs_note -->
				<!-- BEGIN: allow_sitelangs -->
				<select onchange="top.location.href=this.options[this.selectedIndex].value;return;" class="form-control w200">
					<option {ALLOW_SITELANGS.selected_yes} value="{ALLOW_SITELANGS.url_yes}"> {GLANG.yes}</option>
					<option {ALLOW_SITELANGS.selected_no} value="{ALLOW_SITELANGS.url_no}"> {GLANG.no}</option>
				</select>
				<!-- END: allow_sitelangs -->
				</td>
				<td>
				<!-- BEGIN: setup_delete -->
				<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a onclick="return confirm(nv_is_del_confirm[0])" href="{DELETE}" title="{LANG.nv_setup_delete}">{LANG.nv_setup_delete}</a>
				<!-- END: setup_delete -->
				<!-- BEGIN: setup_note -->
				{LANG.nv_setup}
				<!-- END: setup_note -->
				<!-- BEGIN: setup_new -->
				<em class="fa fa-sun-o fa-lg">&nbsp;</em> <a href="{INSTALL}" title="{LANG.nv_setup_new}">{LANG.nv_setup_new}</a>
				<!-- END: setup_new -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<div class="alert alert-warning">{LANG.nv_data_note}</div>
<!-- END: main -->