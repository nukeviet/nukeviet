<!-- BEGIN: main -->
<style type="text/css">
	#rpc
	{
	}
	#rpc .borderRed
	{
		border: 1px solid #FF0000 !important;
	}
	#rpc .end
	{
		margin-left: 162px;
		margin-top: 20px;
		text-align: left;
	}
	#rpc .form
	{
		-moz-border-radius: .5em;
		-moz-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		-webkit-border-radius: .5em;
		-webkit-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		background: #DADADA;
		border: 1px solid #ccc;
		border-radius: .5em;
		box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		margin-bottom: 20px;
		padding: 20px;
		position: relative;
	}
	#rpc .form .submit
	{
		-moz-border-radius: .5em;
		-moz-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		-webkit-border-radius: .5em;
		-webkit-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		background: #fff url({IMGPATH}/bg.jpg) repeat-x 50% 50%;
		border: 1px solid #ccc;
		border-radius: .5em;
		box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		cursor: pointer;
		font-weight: bold;
		outline: none;
		padding: 8px;
		vertical-align: middle;
		width: 100px;
	}
	#rpc .form .txt
	{
		-moz-border-radius: .5em;
		-webkit-border-radius: .5em;
		border: 1px solid #ccc;
		border-radius: .5em;
		outline: none;
		padding: 8px;
		vertical-align: middle;
		width: 300px;
	}
	#rpc .form label
	{
		display: inline-block;
		margin-right: 10px;
		text-align: right;
		vertical-align: middle;
		width: 150px;
	}
	#rpc .form label span
	{
		color: #F00;
	}
	#rpc .head
	{
		background: #DFDFDF;
		border-bottom: 1px solid #ccc;
		font-weight: bold;
		position: relative;
	}
	#rpc .list
	{
		-moz-border-radius: .5em;
		-moz-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		-webkit-border-radius: .5em;
		-webkit-box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		background: #fff;
		border: 1px solid #ccc;
		border-radius: .5em;
		box-shadow: 2px 2px 4px rgba(0,0,0,.4);
		position: relative;
	}
	#rpc .list .col1
	{
		border-right: 1px solid #ccc;
		float: left;
		padding: 10px;
		width: 120px;
	}
	#rpc .list .col1 span
	{
		display: inline-block;
		vertical-align: middle;
	}
	#rpc .list .col2
	{
		border-right: 1px solid #ccc;
		float: left;
		padding: 10px;
		width: 50px;
	}
	#rpc .list .col3, #rpc .list .col3a
	{
		cursor: pointer;
		float: left;
		padding: 10px;
	}
	#rpc .list .error
	{
		background: transparent url({IMGPATH}/error.png) no-repeat center center;
	}
	#rpc .list .load
	{
		background: transparent url({IMGPATH}/load.gif) no-repeat center center;
	}
	#rpc .list .ok
	{
		background: transparent url({IMGPATH}/ok.png) no-repeat center center;
	}
	#rpc .list img
	{
		height: 16px;
		margin-right: 10px;
		text-align: center;
		vertical-align: middle;
		width: 16px;
	}
	#rpc .row
	{
		margin: 0 auto 10px auto;
		position: relative;
		width: 500px;
	}
	#rpc .service
	{
		border-bottom: 1px solid #ccc;
		position: relative;
	}
	#rpc h3
	{
		font: bold 13px Arial;
		margin-bottom: 20px;
	}
</style>
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
			<div class="clear"></div>
		</div>
		<!-- BEGIN: service -->
		<div class="service">
			<div class="col1">
				<!-- BEGIN: icon --><img src="{IMGPATH}/{SERVICE.icon}" alt="" /><!-- END: icon -->
				<!-- BEGIN: noticon --><img src="{IMGPATH}/link.png" alt="" /><!-- END: noticon -->
				<span>{SERVICE.title}</span>
			</div>
			<div class="col2 ld" id="res{SERVICE.id}">
				&nbsp;
			</div>
			<div class="col3" id="mes{SERVICE.id}" title=""></div>
			<div class="clear"></div>
		</div>
		<!-- END: service -->
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
	function sload(c) {
		$.ajax({
			type : "POST",
			url : "{LOAD_DATA}",
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
				if(b == "OK") {
					$("#rpc .ld").removeClass("load");
					if(confirm('{LANG.rpc_finish}')) {
						window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name
					}
				} else {b == "WAIT" ? sload(c) : ( b = b.split("|"), alert(b[1]), $("#rpc .ld").removeClass("load"));
				}
				return !1
			}
		});
		return !1
	}


	$("#rpc .col3").click(function() {
		var a = $(this).attr("title"); a != "" && alert(a);
		return !1
	});

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