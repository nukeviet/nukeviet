<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div id="getuidcontent">
	<form class="form-inline" id="formgetuid" method="get" action="{FORM_ACTION}">
		<input type="hidden" name="area" value="{AREA}" />
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr class="fixbg">
						<td colspan="4" class="text-center"><strong>{LANG.enter_key}</strong></td>
					</tr>
					<tr>
						<td> {LANG.username} </td>
						<td><input class="form-control fixwidthinput" type="text" name="username" value="" maxlength="100" /></td>
						<td> {LANG.full_name} </td>
						<td><input class="form-control fixwidthinput" type="text" name="full_name" value="" maxlength="100" /></td>
					</tr>
					<tr>
						<td> {LANG.email} </td>
						<td><input class="form-control fixwidthinput" type="text" name="email" value="" maxlength="100" /></td>
						<td> {LANG.gender} </td>
						<td>
						<select name="gender" class="form-control fixwidthinput">
							<!-- BEGIN: gender -->
							<option value="{GENDER.key}">{GENDER.title}</option>
							<!-- END: gender -->
						</select></td>
					</tr>
					<tr>
						<td> {LANG.regdate} </td>
						<td> {LANG.from} <input class="form-control txt" type="text" value="" name="regdatefrom" id="regdatefrom" style="width:90px" maxlength="100" /> {LANG.to} <input class="form-control txt" type="text" value="" name="regdateto" id="regdateto" style="width:90px" maxlength="100" /></td>
						<td> {LANG.sig} </td>
						<td><input class="form-control fixwidthinput" type="text" name="sig" value="" maxlength="100" /></td>
					</tr>
					<tr>
						<td> {LANG.last_login} </td>
						<td> {LANG.from} <input class="form-control txt" type="text" value="" name="last_loginfrom" id="last_loginfrom" style="width:90px" maxlength="100" /> {LANG.to} <input class="form-control txt" type="text" value="" name="last_loginto" id="last_loginto" style="width:90px" maxlength="100" /></td>
						<td> {LANG.last_idlogin} </td>
						<td><input class="form-control fixwidthinput" type="text" name="last_ip" value="" maxlength="100" /></td>
					</tr>
					<tr>
						<td colspan="4" class="text-center">
							<input type="reset" class="btn btn-info" value="{LANG.reset}"/>
							<input class="btn btn-primary" type="submit" name="submit" value="{LANG.search}"/>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<div id="resultdata">&nbsp;</div>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#last_loginfrom,#last_loginto,#regdatefrom,#regdateto").datepicker({
			showOn : "both",
			dateFormat : "dd.mm.yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonText : '',
			showButtonPanel : true,
			showOn : 'focus'
		});
		$("#formgetuid").submit(function() {
			var a = $(this).attr("action");
			b = $(this).serialize();
			a = a + "&" + b + "&submit";
			$("#formgetuid input, #formgetuid select").attr("disabled", "disabled");
			$.ajax({
				type : "GET",
				url : a,
				success : function(c) {
					$("#resultdata").html(c);
					$("#formgetuid input, #formgetuid select").removeAttr("disabled")
				}
			});
			return !1
		});
	});
	//]]>
</script>
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
				<td class="text-center"> {LANG.select} </th>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="5" style="text-align:center">
				<div class="fr generatePage">
					{GENERATE_PAGE}
				</div></td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><strong>{ROW.userid}</strong></td>
				<td> {ROW.username} </td>
				<td> {ROW.email} </td>
				<td> {ROW.regdate} </td>
				<td class="text-center"><a title="" onclick="nv_close_pop('{ROW.userid}');" href="javascript:void(0);">{LANG.select}</a></td>
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

	$("thead a,.generatePage a").click(function() {
		var a = $(this).attr("href");
		$("#resultdata").load(a);
		return !1
	});
	//]]>
</script>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"> {LANG.noresult} </td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: nodata -->
<!-- END: resultdata -->