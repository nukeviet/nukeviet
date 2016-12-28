<!-- BEGIN: main -->
<div id="module_show_list">
	{SOURCES_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="sourceid" value="{sourceid}" />
	<input name="savecat" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_sources}</caption>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.name}: </strong><sup class="required">(∗)</sup></td>
					<td><input class="form-control w500" name="title" type="text" value="{title}" maxlength="250" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.link}: </strong></td>
					<td><input class="form-control w500" name="link" type="url" value="{link}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.source_logo}: </strong></td>
					<td>
                        <input class="form-control w500 pull-left" style="margin-right: 5px" type="text" name="logo" id="logo" value="{logo}"/>
                        <input id="select-img-source" type="button" value="{GLANG.browse_image}" name="selectimg" class="btn btn-info" />
    					<!-- BEGIN: logo -->
    					<br />
    					<img src="{logo}"/>
    					<!-- END: logo -->
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
</form>
<script type="text/javascript">
//<![CDATA[
var CFG = [];
CFG.upload_path = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}/source';
//]]>
</script>
<!-- END: main -->