<!-- BEGIN: add -->
<div class="alert alert-info">{INFO}</div>
<form method="post" action="{ACTION}" id="addadmin">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w250">
			</colgroup>
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input name="save" id="save" type="hidden" value="1" /><input name="go_add" type="submit" value="{SUBMIT}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{LANG.add_user}: <sup class="required">(*)</sup></td>
					<td><input class="form-control pull-left" name="userid" id="userid" type="text" value="{USERID}" maxlength="20" style="width: 180px" />&nbsp;<input type="button" value="{LANG.add_select}" onclick="open_browse_us()" class="btn btn-default" /></td>
				</tr>
				<tr>
					<td>{POSITION0}:<sup class="required">(*)</sup>
					</td>
					<td>
						<div class="row">
							<div class="col-sm-8 col-md-6">
								<input class="form-control" name="position" id="position" type="text" value="{POSITION1}" maxlength="250" />
							</div>
							<div class="col-sm-16 col-md-18">
								<span>&lArr;</span> {POSITION2}
							</div>
						</div>
					</td>
				</tr>
                <tr>
                    <td>{LANG.themeadmin}</td>
                    <td>
                    <select name="admin_theme" class="form-control w300" >
                        <option value="">{LANG.theme_default}</option>
                        <!-- BEGIN: admin_theme -->
                        <option value="{THEME_NAME}"{THEME_SELECTED}>{THEME_NAME}</option>
                        <!-- END: admin_theme -->
                    </select></td>
                </tr>                
				<!-- BEGIN: editor -->
				<tr>
					<td>{EDITOR0}:</td>
					<td><select name="editor" id="editor" class="form-control w200">
							<option value="">{EDITOR3}</option>
							<!-- BEGIN: loop -->
							<option value="{EDITOR}"{SELECTED}>{EDITOR}</option>
							<!-- END: loop -->
					</select></td>
				</tr>
				<!-- END: editor -->
				<!-- BEGIN: allow_files_type -->
				<tr>
					<td>{ALLOW_FILES_TYPE0}:</td>
					<td>
						<div class="row">
							<!-- BEGIN: loop --> 
							<div class="col-sm-8 col-md-6">
								<input name="allow_files_type[]" type="checkbox" value="{TP}" {CHECKED} /> {TP}
							</div>
							 <!-- END: loop -->
						</div>
					</td>
				</tr>
				<!-- END: allow_files_type -->
				<tr>
					<td>{ALLOW_MODIFY_FILES0}:</td>
					<td><input name="allow_modify_files" type="checkbox" value="1" {MODIFY_CHECKED} /></td>
				</tr>
				<tr>
					<td>{ALLOW_CREATE_SUBDIRECTORIES0}:</td>
					<td><input name="allow_create_subdirectories" type="checkbox" value="1" {CREATE_CHECKED} /></td>
				</tr>
				<tr>
					<td>{ALLOW_MODIFY_SUBDIRECTORIES}:</td>
					<td><input name="allow_modify_subdirectories" type="checkbox" value="1" {ALLOW_MODIFY_SUBDIRECTORIES_CHECKED} /></td>
				</tr>
				<tr>
					<td>{LEV0}: <sup class="required">(*)</sup></td>
					<td>
						<!-- BEGIN: show_lev_2 --> <label> <input name="lev" type="radio" value="2" onclick="nv_show_hidden('modslist',0);" {LEV2_CHECKED} /> &nbsp; {LEV2}&nbsp;&nbsp;&nbsp;
					</label> <!-- END: show_lev_2 --> <label> <input name="lev" type="radio" value="3" onclick="nv_show_hidden('modslist',1);" {LEV3_CHECKED} /> &nbsp; {LEV3}
					</label> <br />
						<div id="modslist" style="margin-top: 10px;{STYLE_MODS}">
							{MODS0}: <em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp; <em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>
							<div class="clear"></div>
							<div class="row">
								<!-- BEGIN: lev_loop -->
								<div class="col-sm-8 col-md-6">
									<input name="modules[]" type="checkbox" value="{MOD_VALUE}" {LEV_CHECKED} /> &nbsp; {CUSTOM_TITLE}
								</div>
								<!-- END: lev_loop -->
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	function open_browse_us() {
		nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=userid&return=username&filtersql={FILTERSQL}', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
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
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			<em class="fa fa-file-text-o">&nbsp;</em>{TITLE}:
		</caption>
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
</div>
<br />
<div>
	<a class="btn btn-primary" href="{EDIT_HREF}">{EDIT}</a> <a class="btn btn-primary" href="{HOME_HREF}">{HOME}</a>
</div>
<!-- END: add_result -->