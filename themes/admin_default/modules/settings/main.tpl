<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w300"/>
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="2" class="text-center"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary w100" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: site_domain -->
			<tr>
				<th>{LANG.site_domain}</th>
				<td>
					<select name="site_domain" class="form-control w300">
						<option value=""> -- </option>
						<!-- BEGIN: loop -->
						<option value="{SITE_DOMAIN}"{SELECTED}>{SITE_DOMAIN} </option>
						<!-- END: loop -->
					</select>
				</td>
			</tr>
			<!-- END: site_domain -->
			<tr>
				<th>{LANG.sitename}</th>
				<td><input type="text" name="site_name" value="{VALUE.sitename}" class="form-control" style="width: 450px"/></td>
			</tr>
			<tr>
				<th>{LANG.description}</th>
				<td><input type="text" name="site_description" value="{VALUE.description}" class="form-control" style="width: 450px"/></td>
			</tr>
			<tr>
				<th>{LANG.site_keywords}</th>
				<td><input type="text" name="site_keywords" class="form-control" value="{VALUE.site_keywords}" style="width: 450px"/></td>
			</tr>
			<tr>
				<th>{LANG.site_logo}</th>
				<td><input type="text" class="w300 form-control pull-left" name="site_logo" id="site_logo" value="{VALUE.site_logo}" style="margin-right: 10px" /><button data-name="site_logo" name="logo_select" class="btn btn-default selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.browse_image}</button></td>
			</tr>
			<tr>
				<th>{LANG.site_banner}</th>
				<td><input type="text" class="w300 form-control pull-left" name="site_banner" id="site_banner" value="{VALUE.site_banner}" style="margin-right: 10px" /><button data-name="site_banner" name="banner_select" class="btn btn-default selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.browse_image}</button></td>
			</tr>
			<tr>
				<th>{LANG.site_favicon}</th>
				<td><input type="text" class="w300 form-control pull-left" name="site_favicon" id="site_favicon" value="{VALUE.site_favicon}" style="margin-right: 10px" /><button data-name="site_favicon" name="favicon_select" class="btn btn-default selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.browse_image}</button></td>
			</tr>
            <tr>
                <th>{LANG.default_module}</th>
                <td>
                <select name="site_home_module" id="site_home_module" class="form-control w300">
                    <!-- BEGIN: module -->
                    <option value="{MODULE.title}"{SELECTED}>{MODULE.custom_title} </option>
                    <!-- END: module -->
                </select></td>
            </tr>   
            <tr>
                <th>{LANG.allow_theme_type}</th>
                <td>
                    <!-- BEGIN: theme_type -->
                    <input class="form-control"  type="checkbox" name="theme_type[]" value="{THEME_TYPE}" {THEME_TYPE_CHECKED}/> {THEME_TYPE_TXT} &nbsp; &nbsp; &nbsp;
                    <!-- END: theme_type -->                    
                </td>
            </tr>                      
			<tr>
				<th>{LANG.theme}</th>
				<td>
				<select name="site_theme" class="form-control w300">
					<!-- BEGIN: site_theme -->
					<option value="{SITE_THEME}"{SELECTED}>{SITE_THEME} </option>
					<!-- END: site_theme -->
				</select></td>
			</tr>
			<!-- BEGIN: mobile_theme -->
			<tr>
				<th>{LANG.mobile_theme}</th>
				<td>
				<select name="mobile_theme" class="form-control w300">
					<option value="">{LANG.theme}</option>
					<!-- BEGIN: loop -->
					<option value="{SITE_THEME}"{SELECTED}>{SITE_THEME} </option>
					<!-- END: loop -->
				</select></td>
			</tr>
			<tr>
				<th>{LANG.allow_switch_mobi_des}</th>
				<td><input type="checkbox" name="switch_mobi_des" value="1"{VALUE.switch_mobi_des}/></td>
			</tr>
            <!-- END: mobile_theme -->
			<tr>
				<th>{LANG.disable_content}</th>
				<td> {DISABLE_SITE_CONTENT} </td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#site_home_module").select2();
	});
	//]]>
</script>
<!-- END: main -->