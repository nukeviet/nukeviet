<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tfoot>
			<tr>
				<td colspan="2" class="text-center"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary w100" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.sitename}</strong></td>
				<td><input type="text" name="site_name" value="{VALUE.sitename}" class="form-control" style="width: 450px"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.description}</strong></td>
				<td><input type="text" name="site_description" value="{VALUE.description}" class="form-control" style="width: 450px"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.site_keywords}</strong></td>
				<td><input type="text" name="site_keywords" class="form-control" value="{VALUE.site_keywords}" style="width: 450px"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.site_logo}</strong></td>
				<td><input type="text" class="w300 form-control pull-left" name="site_logo" id="site_logo" value="{VALUE.site_logo}" style="margin-right: 10px" /><button name="selectimg" class="btn btn-default"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.browse_image}</button></td>
			</tr>
			<tr>
				<td><strong>{LANG.theme}</strong></td>
				<td>
				<select name="site_theme" class="form-control w200">
					<!-- BEGIN: site_theme -->
					<option value="{SITE_THEME}"{SELECTED}>{SITE_THEME} </option>
					<!-- END: site_theme -->
				</select></td>
			</tr>
			<!-- BEGIN: mobile_theme -->
			<tr>
				<td><strong>{LANG.mobile_theme}</strong></td>
				<td>
				<select name="mobile_theme" class="form-control w200">
					<option value="">{LANG.theme}</option>
					<!-- BEGIN: loop -->
					<option value="{SITE_THEME}"{SELECTED}>{SITE_THEME} </option>
					<!-- END: loop -->
				</select></td>
			</tr>
			<!-- END: mobile_theme -->
			<tr>
				<td><strong>{LANG.default_module}</strong></td>
				<td>
				<select name="site_home_module" class="form-control w200">
					<!-- BEGIN: module -->
					<option value="{MODULE.title}"{SELECTED}>{MODULE.custom_title} </option>
					<!-- END: module -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.allow_switch_mobi_des}</strong></td>
				<td><input type="checkbox" name="switch_mobi_des" value="1"{VALUE.switch_mobi_des}/></td>
			</tr>
			<tr>
				<td><strong>{LANG.disable_content}</strong></td>
				<td> {DISABLE_SITE_CONTENT} </td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("button[name=selectimg]").click(function() {
			var area = "site_logo";
			var path = "";
			var currentpath = "images";
			var type = "image";
			nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
			return false;
		});
	});
	//]]>
</script>
<!-- END: main -->