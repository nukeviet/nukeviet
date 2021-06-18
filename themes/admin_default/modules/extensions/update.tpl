<!-- BEGIN: main -->
<div id="getUpd" class="hide"></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#getUpd').html(nv_loading).removeClass('hide').load("index.php?{NV_NAME_VARIABLE}=extensions&{NV_OP_VARIABLE}=update&eid={EID}&fid={FID}&checksess={CHECKSESS}&num=" + nv_randomPassword(10));
});
//]]>
</script>
<!-- END: main -->

<!-- BEGIN: warning -->
<div class="alert alert-warning" id="prevent-link">{MESSAGE}</div>
<script type="text/javascript">
$(function(){
	$('#prevent-link a').click(function(e){
		e.preventDefault();
		$('#getUpd').html(nv_loading).load($(this).attr('href'));
	});
});
</script>
<!-- END: warning -->

<!-- BEGIN: ok -->
<div class="alert alert-success" id="prevent-link">{MESSAGE}</div>
<script type="text/javascript">
$(function(){
	$('#prevent-link a').click(function(e){
		e.preventDefault();
		$('#getUpd').html(nv_loading).load($(this).attr('href'));
	});
});
</script>
<!-- END: ok -->

<!-- BEGIN: error -->
<div class="alert alert-danger text-center">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: complete -->
<!-- BEGIN: no_extract -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.get_update_cantunzip}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: no_extract -->
<!-- BEGIN: error_create_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.get_update_warning_permission_folder}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_create_folder -->
<!-- BEGIN: error_move_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.get_update_error_movefile}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_move_folder -->
<!-- BEGIN: ok -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-success">{LANG.get_update_okunzip}</strong></td>
			</tr>
			<tr>
				<td class="text-center"><a href="{URL_GO}" title="{LANG.get_update_okunzip_link}">{LANG.get_update_okunzip_link}</a></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
//<![CDATA[
setTimeout("redirect_page()", 5000);
function redirect_page() {
	window.location = "{URL_GO}";
}
//]]>
</script>
<!-- END: ok -->
<!-- END: complete -->
<!-- BEGIN: check -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.extUpdCheck}</caption>
		<col class="w250"/>
		<tbody>
			<tr>
				<td><strong>{LANG.extname}</strong></td>
				<td>{DATA.title}</td>
			</tr>
			<tr>
				<td><strong>{LANG.extUpdCheckStatus}</strong></td>
				<td><em class="text-{DATA.class} fa {DATA.icon}"></em> {DATA.message}</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- BEGIN: message -->
					<div class="alert alert-{DATA.class} ext-alert-inline">{MESSAGE}</div>
					<!-- END: message -->
					<!-- BEGIN: ready -->
					<div id="upd-getfile" class="text-center"></div>
					<script type="text/javascript">
					$(function(){
						$('#upd-getfile').html('{LANG.extUpdCheckSuccessNote}<br />' + nv_loading).load('{LINK}');
					});
					</script>
					<!-- END: ready -->
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: check -->