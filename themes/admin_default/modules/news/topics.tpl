<!-- BEGIN: main -->
<div id="module_show_list">
	{TOPIC_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="topicid" value="{DATA.topicid}" />
	<input name="savecat" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_topic}</caption>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.name}: </strong><sup class="required">(∗)</sup></td>
					<td><input class="form-control w500" name="title" id="idtitle" type="text" value="{DATA.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.alias}: </strong></td>
					<td>
						<input class="form-control w500 pull-left" name="alias" id="idalias" type="text" value="{DATA.alias}" maxlength="255" />
						&nbsp; <span class="text-middle"><em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('topics', {DATA.topicid});">&nbsp;</em></span>
					</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.content_homeimg}:</strong></td>
					<td><input class="form-control pull-left w500" style="margin-right: 5px" type="text" name="homeimg" id="homeimg" value="{DATA.image}" /> <input id="select-img-topic" type="button" value="Browse server" name="selectimg" class="btn btn-info" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.keywords}: </strong></td>
					<td><input class="form-control w500" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right">
					<br />
					<strong>{LANG.description}</strong></td>
					<td><textarea class="form-control w500" name="description" cols="100" rows="5">{DATA.description}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
</form>
<script type="text/javascript">
var CFG = [];
CFG.upload_dir = '{UPLOADS_DIR}';
<!-- BEGIN: getalias -->
$("#idtitle").change(function() {
	get_alias('topics', '{DATA.topicid}');
});
<!-- END: getalias -->
</script>
<!-- END: main -->