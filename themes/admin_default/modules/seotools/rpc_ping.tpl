<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/admin_default/css/seotools_rpc.css" type="text/css" />
<div id="rpc">
	<h3>{LANG.rpc_ftitle}</h3>
	<div class="list">
		<div class="head">
			<div class="col1">
				{LANG.rpc_linkname}
			</div>
			<div class="col2">
				{LANG.rpc_reruslt}
			</div>
			<div class="col3a">
				{LANG.rpc_message}
			</div>
			<div class="clearfix"></div>
		</div>
		<!-- BEGIN: service -->
		<div class="service">
			<div class="col1">
				<!-- BEGIN: icon -->
				<img src="{NV_BASE_SITEURL}themes/admin_default/images/seotools/{SERVICE.icon}" alt="" />
				<!-- END: icon -->
				<!-- BEGIN: noticon -->
				<img src="{NV_BASE_SITEURL}themes/admin_default/images/seotools/link.png" alt="" />
				<!-- END: noticon -->
				<span>{SERVICE.title}</span>
			</div>
			<div class="col2 ld" id="res{SERVICE.id}">
				&nbsp;
			</div>
			<div class="col3" id="mes{SERVICE.id}" title=""></div>
			<div class="clearfix"></div>
		</div>
		<!-- END: service -->
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
function sload(c) {
	$.ajax({
		type : "POST",
		url : '{LOAD_DATA}',
		dataType : "xml",
		data : "total=" + c + "&rand=" + nv_randomPassword(8),
		success : function(b) {
			jQuery(b).find("service").each(function() {
				var a = jQuery(this).find("id").text(), b = jQuery(this).find("flerrorCode").text(), c = jQuery(this).find("message").text();
				$("#res" + a).removeClass("load");
				$("#mes" + a).removeClass("load");
				b == "0" ? $("#res" + a).addClass("ok") : $("#res" + a).addClass("error");
				$("#mes" + a).text(c);
			});
			var c = jQuery(b).find("break").text(), b = jQuery(b).find("finish").text();
			if (b == "OK") {
				$("#rpc .ld").removeClass("load");
				if (confirm('{LANG.rpc_finish}')) {
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name
				}
			} else {
				b == "WAIT" ? sload(c) : ( b = b.split("|"), alert(b[1]), $("#rpc .ld").removeClass("load"));
			}
			return !1
		}
	});
	return !1
}
$("#rpc .borderRed").removeClass("borderRed");
$("#rpc .ok").removeClass("ok");
$("#rpc .error").removeClass("error");
$("#rpc .col3").html("");
$("#rpc .col3").attr("title", "");
$("#rpc .ld").addClass("load");
sload(0);
//]]>
</script>
<!-- END: main -->