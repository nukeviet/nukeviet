<!-- BEGIN: main -->
<script type="text/javascript">
	function nv_check_form(OForm) {
		var f_method = document.getElementById( 'f_method' ).options[document.getElementById('f_method').selectedIndex].value;
		var f_value = document.getElementById('f_value').value;

		if (f_method != '' && f_value != '') {
			OForm.submit();
		}
		return false;
	}
</script>
<div id="users">
	<!-- BEGIN: is_forum -->
	<div class="quote">
		<blockquote class="error"><span>{LANG.modforum}</span></blockquote>
	</div>
	<!-- END: is_forum -->
	<div style="padding-top:10px;">
		<form action="{FORM_ACTION}" method="post" onsubmit="nv_check_form(this);return false;">
			<span><strong>{LANG.search_type}:</strong></span>
			<select name="method" id="f_method">
				<option value="">---</option>
				<!-- BEGIN: method -->
				<option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
				<!-- END: method -->
			</select>
			<input type="text" name="value" id="f_value" value="{SEARCH_VALUE}" />
			<input name='search' type="submit" value="{LANG.submit}" />
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
				<td><strong>{LANG.funcs}</strong></td>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="8"> {GENERATE_PAGE} </td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: xusers -->
			<tr>
				<td> {CONTENT_TD.userid} </td>
				<td> {CONTENT_TD.username} </td>
				<td> {CONTENT_TD.full_name} </td>
				<td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
				<td> {CONTENT_TD.regdate} </td>
				<td> &nbsp;&nbsp;<a class="edit_icon" href="{ACTIVATE_URL}">{LANG.awaiting_active}</a>&nbsp;&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_waiting_row_del({CONTENT_TD.userid});">{LANG.delete}</a></td>
			</tr>
			<!-- END: xusers -->
		</tbody>
	</table>
</div>
<!-- END: main -->