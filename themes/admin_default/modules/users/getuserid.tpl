<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
	function nv_search_other() {
		$("#search_other").show();
		$("#onclick_other").hide();
	}	
</script>
<div id="getuidcontent">
	<form class="form-inline" id="formgetuid" method="get" action="{FORM_ACTION}">
		<input type="hidden" name="area" value="{AREA}" /> <input type="hidden" name="return" value="{RETURN}" />
		<div class="alert alert-info text-center">{LANG.enter_key}</div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tfoot>
					<tr>
						<td colspan="4" class="text-center"><input type="reset" class="btn btn-info" value="{LANG.reset}" /> <input class="btn btn-primary" type="submit" name="submit" value="{LANG.search}" />
						<div class="pull-right" id="onclick_other"> <a title="" onclick="nv_search_other()" href="javascript:void(0);" class="btn btn-primary btn-xs">{LANG.field_options_text}</a></div></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.username}</td>
						<td><input class="form-control fixwidthinput" type="text" name="username" value="" maxlength="100" /></td>

						<td>{LANG.fullname}</td>
						<td><input class="form-control fixwidthinput" type="text" name="full_name" value="" maxlength="100" /></td>
					</tr>
					<tr>
						<td>{LANG.email}</td>
						<td><input class="form-control fixwidthinput" type="text" name="email" value="" maxlength="100" /></td>
						<td>{LANG.gender}</td>
						<td><select name="gender" class="form-control fixwidthinput">
								<!-- BEGIN: gender -->
								<option value="{GENDER.key}">{GENDER.title}</option>
								<!-- END: gender -->
						</select></td>
					</tr>
				</tbody>
				<tbody id="search_other" style="display:none;">
					<tr>
						<td>{LANG.regdate}</td>
						<td>{LANG.from} <input class="form-control txt" type="text" value="" name="regdatefrom" id="regdatefrom" style="width: 90px" maxlength="100" /> {LANG.to} <input class="form-control txt" type="text" value="" name="regdateto" id="regdateto" style="width: 90px" maxlength="100" /></td>
						<td>{LANG.sig}</td>
						<td><input class="form-control fixwidthinput" type="text" name="sig" value="" maxlength="100" /></td>
					</tr>
					<tr>
						<td>{LANG.last_login}</td>
						<td>{LANG.from} <input class="form-control txt" type="text" value="" name="last_loginfrom" id="last_loginfrom" style="width: 90px" maxlength="100" /> {LANG.to} <input class="form-control txt" type="text" value="" name="last_loginto" id="last_loginto" style="width: 90px" maxlength="100" /></td>
						<td>{LANG.last_idlogin}</td>
						<td><input class="form-control fixwidthinput" type="text" name="last_ip" value="" maxlength="100" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<div id="resultdata">&nbsp;</div>
<!--  END: main  -->
<!-- BEGIN: resultdata -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50">
			<col class="w100">
			<col>
			<col class="w150" />
			<col class="w50" />
		</colgroup>
		<thead>
			<tr>
				<th><a class="{ODER_ID.class}" href="{ODER_ID.url}" title="">ID</a></th>
				<th><a class="{ODER_USERNAME.class}" href="{ODER_USERNAME.url}" title="">{LANG.username}</a></th>
				<th><a class="{ODER_EMAIL.class}" href="{ODER_EMAIL.url}" title="">{LANG.email}</a></th>
				<th><a class="{ODER_REGDATE.class}" href="{ODER_REGDATE.url}" title="">{LANG.regdate}</a></th>
				<td class="text-center">{LANG.select}
				</th>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: center">
					<div class="fr generatePage">{GENERATE_PAGE}</div>
				</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><strong>{ROW.userid}</strong></td>
				<td>{ROW.username}</td>
				<td>{ROW.email}</td>
				<td>{ROW.regdate}</td>
				<td class="text-center"><a title="" onclick="nv_close_pop('{ROW.return}');" href="javascript:void(0);">{LANG.select}</a></td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	function nv_close_pop(userid) {
		$("#{AREA}", opener.document).val(userid);
		window.close();
	}	
	//]]>
</script>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center">{LANG.noresult}</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: nodata -->
<!-- END: resultdata -->