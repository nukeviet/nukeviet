<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div id="edit">&nbsp;</div>
<div class="alert alert-danger"><span id="message">ERROR! CONFIG FILE: {ERROR}</span></div>
<!-- END: error -->
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<tbody>
		<tr>
			<!-- BEGIN: loop -->
			<!-- BEGIN: active -->
			<td style="padding-left:50px;width:50%;background-color:#FFDBB7">
			<!-- END: active -->
			<!-- BEGIN: deactive -->
			<td style="padding-left:50px;width:50%">
			<!-- END: deactive -->
			<p>
				<strong>{ROW.name}</strong> {LANG.theme_created_by} <a href="{ROW.website}" title="{LANG.theme_created_website}" style="color:#3B5998" onclick="this.target='_blank'"><strong>{ROW.author}</strong></a>
			</p>
			<p><img alt="{ROW.name}" src="{NV_BASE_SITEURL}themes/{ROW.value}/{ROW.thumbnail}" style="max-width:300px;max-height:200px"/>
			</p>
			<p style="font-size:13px;margin-top:10px;font-weight:bold">
				<!-- BEGIN: link_active -->
				<a href="javascript:void(0);" class="activate" title="{ROW.value}" style="color:#3B5998">{LANG.theme_created_activate}</a>
				<!-- END: link_active -->
				<!-- BEGIN: dash -->
				-
				<!-- END: dash -->
				<!-- BEGIN: link_delete -->
				<a href="javascript:void(0);" class="delete" title="{ROW.value}" style="color:#3B5998">{LANG.theme_created_delete}</a>
			</p>
			<!-- END: link_delete -->
			<p style="font-size:13px">
				{ROW.description}
			</p>
			<p style="font-size:13px;margin-top:10px">
				{LANG.theme_created_folder} <span style="background-color:#E5F4FD">/themes/{ROW.value}/</span>
			</p>
			<p style="font-size:13px;margin-top:20px">
				{LANG.theme_created_position} {POSITION}
			</p>
			<!-- BEGIN: endtr -->
			</td>
		</tr>
		<tr>
			<!-- END: endtr -->
			<!-- BEGIN: endtd -->
			</td>
			<!-- END: endtd -->
			<!-- END: loop -->
		</tr>
	</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("a.activate").click(function() {
			var theme = $(this).attr("title");
			$.ajax({
				type : "POST",
				url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=activatetheme",
				data : "theme=" + theme,
				success : function(data) {
					if (data != "OK_" + theme) {
						alert(data);
					}
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}";
				}
			});
		});
		$("a.delete").click(function() {
			var theme = $(this).attr("title");
			if (confirm("{LANG.theme_created_delete_theme}" + theme + " ?")) {
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=deletetheme",
					data : "theme=" + theme,
					success : function(data) {
						alert(data);
						window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}";
					}
				});
			}
		});
	});
	//]]>
</script>
<!-- END: main -->