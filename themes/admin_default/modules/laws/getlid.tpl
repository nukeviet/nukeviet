<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.pagetitle1}</title>

		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
		<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">
			//<![CDATA[
			var nv_base_siteurl = "{NV_BASE_SITEURL}";
			//]]>
		</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
	</head>
	<body>
		<div id="getuidcontent">
			<form class="form-inline" id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="area" value="{AREA}" />
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr class="fixbg">
						<td colspan="4" class="text-center">
							<strong>{LANG.enter_key}</strong>
						</td>
					</tr>
					<tr>
						<td>
							{LANG.title}
						</td>
						<td>
							<input class="form-control fixwidthinput" type="text" name="title" value="" maxlength="100" />
						</td>
						<td>
							{LANG.code}
						</td>
						<td>
							<input class="form-control fixwidthinput" type="text" name="code" value="" maxlength="100" />
						</td>
					</tr>
					<tr>
						<td>
							{LANG.publtime}
						</td>
						<td>
							{LANG.from}<input class="form-control" style="width:100px" type="text" name="pfrom" id="pfrom" value="" maxlength="100" />
							{LANG.to}<input class="form-control" style="width:100px" type="text" name="pto" id="pto" value="" maxlength="100" />
						</td>
						<td>
							{LANG.exptime}
						</td>
						<td>
							{LANG.from}<input class="form-control" style="width:100px" type="text" name="efrom" id="efrom" value="" maxlength="100" />
							{LANG.to}<input class="form-control" style="width:100px" type="text" name="eto" id="eto" value="" maxlength="100" />
						</td>
					</tr>
					<tr>
						<td colspan="4" class="text-center">
							<input class="btn btn-primary" type="submit" name="submit" value="{LANG.search}"/>
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
$("#pfrom,#pto,#efrom,#eto").datepicker({
	showOn: "button",
	dateFormat: "dd.mm.yy",
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	buttonImage: nv_base_siteurl+"assets/images/calendar.gif",
	//buttonImageOnly: true,
	buttonText: '{LANG.select}',
	showButtonPanel: true,
	showOn: 'focus'
});

$("#formgetuid").submit(function() {
  var a = $(this).attr("action");
  b = $(this).serialize();
  a = a + "&" + b + "&submit=1";
  $("#formgetuid input, #formgetuid select").attr("disabled", "disabled");
  $.ajax({type:"GET", url:a, success:function(c) {
    $("#resultdata").html(c);
    $("#formgetuid input, #formgetuid select").removeAttr("disabled");
  }});
  return!1;
});
$(".reset").click(function() {
  $("[type=text]").val('');
  $("select[name=gender]").val('');
  $("#resultdata").html('');
  return!1;
});
//]]>
</script>
	</body>
</html>
<!--  END: main  -->
<!-- BEGIN: resultdata -->
            <!-- BEGIN: data -->
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td>
							<a class="{ODER_ID.class}" href="{ODER_ID.url}" title="">ID</a>
						</td>
						<td>
							<a class="{ODER_TITLE.class}" href="{ODER_TITLE.url}" title="">{LANG.title}</a>
						</td>
						<td>
							<a class="{ODER_CODE.class}" href="{ODER_CODE.url}" title="">{LANG.code}</a>
						</td>
						<td>
							<a class="{ODER_ADDTIME.class}" href="{ODER_ADDTIME.url}" title="">{LANG.addtime}</a>
						</td>
						<td class="text-center">
							{LANG.select}
						</td>
					</tr>
				</thead>
				<tbody>
				<!-- BEGIN: row -->
					<tr>
						<td style="width:30px;">
							<strong>{ROW.id}</strong>
						</td>
						<td style="width:100px;">
							<strong>{ROW.title}</strong>
						</td>
						<td style="width:100px;">
							{ROW.code}
						</td>
						<td style="width:100px;white-space:nowrap;">
							{ROW.addtime}
						</td>
						<td style="width:50px;text-align:center">
							<a title="" onclick="nv_close_pop('{ROW.id}');" href="javascript:void(0);">{LANG.select}</a>
						</td>
					</tr>
				<!-- END: row -->
				<tbody>
				<!-- BEGIN: generate_page -->
				<tbody>
					<tr>
						<td colspan="5" style="text-align:center">
							<div class="fr generatePage">
							{GENERATE_PAGE}
							</div>
						</td>
					</tr>
				<!-- END: generate_page -->
				<tbody>
			</table>
<script type="text/javascript">
//<![CDATA[
function nv_close_pop ( id ) {
  $( "#{AREA}", opener.document ).val( id );
  window.close();
}
$("thead a,.generatePage a").click(function() {
  var a = $(this).attr("href");
  $("#resultdata").load(a);
  return!1;
});
//]]>
</script>
			<!-- END: data -->
			<!-- BEGIN: nodata -->
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td class="text-center">
							{LANG.noresult}
						</td>
					</tr>
				</tbody>
			</table>
			<!-- END: nodata -->
<!-- END: resultdata -->