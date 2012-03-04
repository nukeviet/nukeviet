<!-- BEGIN: activelang -->
<br /><br /><br /><p class="center">{LANG.nv_setting_save}</p>
<meta http-equiv="Refresh" content="1;URL={URL}" />
<!-- END: activelang -->
<!-- BEGIN: contents_setup -->
<br /><br /><center><br /><b>{LANG.nv_data_setup_ok}</b></center>
<meta http-equiv="Refresh" content="5;URL={URL}" />
<!-- END: contents_setup -->
<!-- BEGIN: main -->
<table summary="" class="tab1">
	<thead>
		<tr>
			<td>{LANG.nv_lang_key}</td>
			<td>{LANG.nv_lang_name}</td>
			<td style="width: 120px">{LANG.nv_lang_slsite}</td>
			<td></td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.keylang}</td>
			<td>{ROW.name}</td>
			<td class="center">
				<!-- BEGIN: allow_sitelangs_note -->{LANG.site_lang}<!-- END: allow_sitelangs_note -->
				<!-- BEGIN: allow_sitelangs -->
				<select onchange="top.location.href=this.options[this.selectedIndex].value;return;">
					<option{ALLOW_SITELANGS.selected_yes} value="{ALLOW_SITELANGS.url_yes}">{GLANG.yes}</option>
					<option{ALLOW_SITELANGS.selected_no} value="{ALLOW_SITELANGS.url_no}">{GLANG.no}</option>
				</select>
				<!-- END: allow_sitelangs -->
			</td>
			<td>
				<!-- BEGIN: setup_delete -->
				<span class="delete_icon"><a onclick="return confirm(nv_is_del_confirm[0])" href="{DELETE}" title="{LANG.nv_setup_delete}">{LANG.nv_setup_delete}</a></span>
				<!-- END: setup_delete -->
				<!-- BEGIN: setup_note -->{LANG.nv_setup}<!-- END: setup_note -->
				<!-- BEGIN: setup_new -->
				<span class="default_icon"><a href="{INSTALL}" title="{LANG.nv_setup_new}">{LANG.nv_setup_new}</a></span>
				<!-- END: setup_new -->
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<div class="quote" style="width:98%">
	<blockquote><span>{LANG.nv_data_note}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: main -->
