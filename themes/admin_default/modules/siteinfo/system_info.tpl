<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<!-- BEGIN: textcap -->
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
		<!-- END: textcap -->
		<!-- BEGIN: urlcap -->
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION} <a href="{URL}" id="checkchmod" title="{LANG.checkchmod}">({LANG.checkchmod})</a><span id="wait"></span></caption>
		<!-- END: urlcap -->
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td>{KEY}</td>
				<td>{VALUE}</td>
			</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->
<!-- BEGIN: js -->
<script type="text/javascript">
	//<![CDATA[
	$("#checkchmod").click(function(event) {
		event.preventDefault();
		var url = $(this).attr("href");
		$("#checkchmod").hide();
		$("#wait").html('<img class="refresh" src="{NV_BASE_SITEURL}images/load_bar.gif" alt=""/>');
		$.ajax({
			type : "POST",
			url : url,
			data : "",
			success : function(data) {
				$("#wait").html("");
				alert(data);
				$("#checkchmod").show();
			}
		});
	})
	//]]>
</script>
<!-- END: js -->