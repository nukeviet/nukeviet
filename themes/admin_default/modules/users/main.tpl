<!-- BEGIN: main -->
<div id="users">
	<!-- BEGIN: is_forum -->
	<div class="quote">
		<blockquote class="error">
			<span>{LANG.modforum}</span>
		</blockquote>
	</div>
	<!-- END: is_forum -->
	<div style="padding-top:10px;">
		<form action="{FORM_ACTION}" method="get">
			<input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
			<span><strong>{LANG.search_type}:</strong></span>
			<select name="method" id="f_method">
				<option value="">---</option>
				<!-- BEGIN: method -->
				<option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
				<!-- END: method -->
			</select>
			<input type="text" name="value" id="f_value" value="{SEARCH_VALUE}" />
			<select name="usactive">
				<!-- BEGIN: usactive -->
				<option value="{USACTIVE.key}"{USACTIVE.selected}>{USACTIVE.value}</option>
				<!-- END: usactive -->
			</select>
			<input name="search" type="submit" value="{LANG.submit}" />
			<p>
				{LANG.search_note}
			</p>
		</form>
	</div>
	<table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: head_td -->
				<td><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></td>
				<!-- END: head_td -->
				<td class="center"><strong>{LANG.memberlist_active}</strong></td>
				<td><strong>{LANG.funcs}</strong></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
				<!-- BEGIN: exportfile -->
				<input type="button" value="{LANG.export}" name="data_export"/>
				<!-- END: exportfile -->
				<!-- BEGIN: generate_page -->
				{GENERATE_PAGE}
				<!-- END: generate_page -->
				</td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: xusers -->
			<tr>
				<td> {CONTENT_TD.userid} </td>
				<td>
				<!-- BEGIN: is_admin -->
				<img style="vertical-align:middle;" alt="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
				<!-- END: is_admin -->
				{CONTENT_TD.username} </td>
				<td> {CONTENT_TD.full_name} </td>
				<td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
				<td> {CONTENT_TD.regdate} </td>
				<td class="center"><input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}"{CONTENT_TD.checked}{CONTENT_TD.disabled} /></td>
				<td style="white-space: nowrap">
				<!-- BEGIN: edit -->
				&nbsp;&nbsp; <em class="icon-edit icon-large">&nbsp;</em> <a href="{EDIT_URL}">{LANG.memberlist_edit}</a>
				<!-- END: edit -->
				<!-- BEGIN: del -->
				&nbsp;&nbsp; <em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del({CONTENT_TD.userid});">{LANG.delete}</a>
				<!-- END: del -->
				</td>
			</tr>
			<!-- END: xusers -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	function nv_data_export(set_export) {
		$.ajax({
			type : "POST",
			url : "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export&nocache=" + new Date().getTime(),
			data : "step=1&set_export=" + set_export + "&method=" + $("select[name=method]").val() + "&value=" + $("input[name=value]").val() + "&usactive=" + $("select[name=usactive]").val(),
			success : function(response) {
				if (response == "OK_GETFILE") {
					nv_data_export(0);
				} else if (response == "OK_COMPLETE") {
					$("#users").hide();
					alert('{LANG.export_complete}');
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
				} else {
					$("#users").hide();
					alert(response);
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name;
				}
			}
		});
	}


	$("input[name=data_export]").click(function() {
		$("input[name=data_export]").attr("disabled", "disabled");
		$('#users').html('<center>{LANG.export_note}<br /><br /><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></center>');
		nv_data_export(1);
	});
</script>
<!-- END: main -->