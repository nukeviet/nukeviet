<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.pagetitle1}</title>	
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">
		//<![CDATA[
		var nv_siteroot = "{NV_BASE_SITEURL}";
		//]]>
		</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>		
	</head>
	<body>
		<div id="getuidcontent">
			<form id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="area" value="{AREA}" />
			<table class="tab1">
				<tbody>
					<tr class="fixbg">
						<td colspan="4" class="center">
							<strong>{LANG.enter_key}</strong>
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.username}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="username" value="" maxlength="100" />
						</td>
						<td>
							{LANG.full_name}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="full_name" value="" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.email}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="email" value="" maxlength="100" />
						</td>
						<td>
							{LANG.gender}
						</td>
						<td>
							<select name="gender" class="fixwidthinput">
								<!-- BEGIN: gender -->
								<option value="{GENDER.key}">{GENDER.title}</option>
								<!-- END: gender -->
							</select>
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.web}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="website" value="" maxlength="255" />
						</td>
						<td>
							{LANG.address}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="address" value="" maxlength="255" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.phone}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="phone" value="" maxlength="100" />
						</td>
						<td>
							{LANG.mobile}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="mobile" value="" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.regdate}
						</td>
						<td>
							{LANG.from}
							<input class="txt" type="text" value="" name="regdatefrom" id="regdatefrom" style="width:90px" maxlength="100" />
							{LANG.to}
							<input class="txt" type="text" value="" name="regdateto" id="regdateto" style="width:90px" maxlength="100" />
						</td>
						<td>
							{LANG.yim}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="yim" value="" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.last_login}
						</td>
						<td>
							{LANG.from}
							<input class="txt" type="text" value="" name="last_loginfrom" id="last_loginfrom" style="width:90px" maxlength="100" />
							{LANG.to}
							<input class="txt" type="text" value="" name="last_loginto" id="last_loginto" style="width:90px" maxlength="100" />
						</td>
						<td>
							{LANG.last_idlogin}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="last_ip" value="" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td colspan="4" class="center">
							<input type="submit" name="submit" value="{LANG.search}"/>
							<input type="button" class="reset" value="{LANG.reset}"/>
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="resultdata"></div>
		<script type="text/javascript">
		//<![CDATA[
		$(document).ready(function(){
			$("#last_loginfrom,#last_loginto,#regdatefrom,#regdateto").datepicker({
				showOn: "button",
				dateFormat: "dd.mm.yy",
				changeMonth: true,
				changeYear: true,
				showOtherMonths: true,
				buttonText: '',
				showButtonPanel: true,
				showOn: 'focus'
			});
			$("#formgetuid").submit(function(){
				var a = $(this).attr("action");
				b = $(this).serialize();
				a = a + "&" + b + "&submit";
				$("#formgetuid input, #formgetuid select").attr("disabled", "disabled");
				$.ajax({type:"GET", url:a, success:function(c) {
					$("#resultdata").html(c);
					$("#formgetuid input, #formgetuid select").removeAttr("disabled")
				}});
				return!1
			});
			$(".reset").click(function(){
				$("[type=text]").val('');
				$("select[name=gender]").val('');
				$("#resultdata").html('');
				return!1
			});
		});
		//]]>
		</script>
	</body>
</html>
<!--  END: main  -->
<!-- BEGIN: resultdata -->
<!-- BEGIN: data -->
<table class="tab1">
	<thead>
		<tr>
			<td>
				<a class="{ODER_ID.class}" href="{ODER_ID.url}" title="">ID</a>
			</td>
			<td>
				<a class="{ODER_USERNAME.class}" href="{ODER_USERNAME.url}" title="">{LANG.username}</a>
			</td>
			<td>
				<a class="{ODER_EMAIL.class}" href="{ODER_EMAIL.url}" title="">{LANG.email}</a>
			</td>
			<td>
				<a class="{ODER_REGDATE.class}" href="{ODER_REGDATE.url}" title="">{LANG.regdate}</a>
			</td>
			<td class="center">
				{LANG.select}
			</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{CLASS}>
		<tr>
			<td style="width:30px;">
				<strong>{ROW.userid}</strong>
			</td>
			<td style="width:100px;">
				{ROW.username}
			</td>
			<td>
				{ROW.email}
			</td>
			<td style="width:100px;white-space:nowrap;">
				{ROW.regdate}
			</td>
			<td style="width:50px;text-align:center">
				<a title="" onclick="nv_close_pop('{ROW.userid}');" href="javascript:void(0);">{LANG.select}</a>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
	<!-- BEGIN: generate_page -->
	<tbody>
		<tr>
			<td colspan="5" style="text-align:center">
				<div class="fr generatePage">
					{GENERATE_PAGE}
				</div>
			</td>
		</tr>
	</tbody>
	<!-- END: generate_page -->
</table>
<script type="text/javascript">
//<![CDATA[
function nv_close_pop(userid){
	$("#{AREA}", opener.document).val(userid);
	window.close();
}
$("thead a,.generatePage a").click(function(){
	var a = $(this).attr("href");
	$("#resultdata").load(a);
	return!1
});
//]]>
</script>
<!-- END: data -->
<!-- BEGIN: nodata -->
<table class="tab1">
	<tbody>
		<tr>
			<td class="center">
				{LANG.noresult}
			</td>
		</tr>
	</tbody>
</table>
<!-- END: nodata -->
<!-- END: resultdata -->