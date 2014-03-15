<!-- BEGIN: add -->
<div class="quote">
	<blockquote><span>{INFO}</span></blockquote>
</div>
<form method="post" action="{ACTION}" id="addadmin">
	<table class="tab1">
		<colgroup>
			<col class="w200">
			<col class="w20">
			<col class="w400">
			<col>
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="4" class="center"><input name="save" id="save" type="hidden" value="1" /><input name="go_add" type="submit" value="{SUBMIT}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>{LANG.add_user}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input class="w250" name="userid" id="userid" type="text" value="{USERID}" maxlength="20" />&nbsp;<input type="button" value="{LANG.add_select}" onclick="open_browse_us()" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td> {POSITION0}: </td>
				<td><sup class="required"> &lowast; </sup></td>
				<td><input class="w250" name="position" id="position" type="text" value="{POSITION1}" maxlength="250" /></td>
				<td><span class="row">&lArr;</span> {POSITION2} </td>
			</tr>
			<!-- BEGIN: editor -->
			<tr>
				<td> {EDITOR0}: </td>
				<td>&nbsp;</td>
				<td>
				<select name="editor" id="editor">
					<option value="">{EDITOR3}</option>
					<!-- BEGIN: loop -->
					<option value="{EDITOR}" {SELECTED}>{EDITOR} </option>
					<!-- END: loop -->
				</select></td>
				<td>&nbsp;</td>
			</tr>
			<!-- END: editor -->
			<!-- BEGIN: allow_files_type -->
			<tr>
				<td> {ALLOW_FILES_TYPE0}: </td>
				<td>&nbsp;</td>
				<td>
				<!-- BEGIN: loop -->
				<input name="allow_files_type[]" type="checkbox" value="{TP}" {CHECKED} /> {TP}
				<br/>
				<!-- END: loop -->
				</td>
				<td>&nbsp;</td>
			</tr>
			<!-- END: allow_files_type -->
			<tr>
				<td> {ALLOW_MODIFY_FILES0}: </td>
				<td>&nbsp;</td>
				<td><input name="allow_modify_files" type="checkbox" value="1" {MODIFY_CHECKED} /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td> {ALLOW_CREATE_SUBDIRECTORIES0}: </td>
				<td>&nbsp;</td>
				<td><input name="allow_create_subdirectories" type="checkbox" value="1" {CREATE_CHECKED} /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td> {ALLOW_MODIFY_SUBDIRECTORIES}: </td>
				<td>&nbsp;</td>
				<td><input name="allow_modify_subdirectories" type="checkbox" value="1" {ALLOW_MODIFY_SUBDIRECTORIES_CHECKED} /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td> {LEV0}: </td>
				<td>&nbsp;</td>
				<td colspan="2">
				<!-- BEGIN: show_lev_2 -->
				<label> <input name="lev" type="radio" value="2" onclick="nv_show_hidden('modslist',0);"{LEV2_CHECKED} /> &nbsp;
					{LEV2}&nbsp;&nbsp;&nbsp; </label>
				<!-- END: show_lev_2 -->
				<label> <input name="lev" type="radio" value="3" onclick="nv_show_hidden('modslist',1);"{LEV3_CHECKED} /> &nbsp;
					{LEV3} </label>
				<br/>
				<div id="modslist" style="margin-top:10px;{STYLE_MODS}">
					{MODS0}:
					<br/>
					<!-- BEGIN: lev_loop -->
					<p>
						<input name="modules[]" type="checkbox" value="{MOD_VALUE}" {LEV_CHECKED} />
						&nbsp;
						{CUSTOM_TITLE}

					</p>
					<!-- END: lev_loop -->
				</div></td>
			</tr>
		</tbody>

	</table>
</form>
<script type="text/javascript">
	//<![CDATA[
	function open_browse_us() {
		nv_open_browse_file('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=userid&filtersql={FILTERSQL}', 'NVImg', '850', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
	}

	$(document).ready(function() {
		$("form#addadmin").submit(function() {
			a = $(this).serialize();
			var b = $(this).attr("action");
			$("[type=submit]").attr("disabled", "disabled");
			$.ajax({
				type : "POST",
				url : b,
				data : a,
				success : function(c) {
					if (c == "OK") {
						window.location = '{RESULT_URL}';
					} else {
						alert(c);
					}
					$("[type=submit]").removeAttr("disabled")
				}
			});
			return !1
		});
	});
	//]]>
</script>
<!-- END: add -->
<!-- BEGIN: add_result -->
<table class="tab1">
	<caption>{TITLE}:</caption>
	<col span="2" class="top" style="width: 50%" />
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>{VALUE0}</td>
			<td>{VALUE1}</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<br/>
<div>
	<a class="button button-h" href="{EDIT_HREF}">{EDIT}</a>
	<a class="button button-h" href="{HOME_HREF}">{HOME}</a>
</div>
<!-- END: add_result -->