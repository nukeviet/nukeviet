<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col class="w250" />
				<col span="2" />
				<col class="w100" />
				<col class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th>{LANG.name}</th>
					<th>{LANG.alias}</th>
					<th>{LANG.keywords}</th>
					<th class="text-center">{LANG.numlinks}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.tid}" name="idcheck[]" /></td>
					<td>{ROW.title}</td>
					<td><a href="{ROW.link}">{ROW.alias}</a></td>
					<td>
						{ROW.keywords}
						<!-- BEGIN: incomplete -->
					 	<em class="text-danger fa fa-lg fa-warning tags-tip" data-toggle="tooltip" data-placement="top" title="{LANG.tags_no_description}">&nbsp;</em>
						<!-- END: incomplete -->
					</td>
					<td class="text-center">{ROW.numnews}</td>
					<td class="text-center">
						<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
						<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_tags({ROW.tid})">{GLANG.delete}</a>
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><input class="btn btn-danger" name="submit_dell" type="button" onclick="nv_del_check_tags(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{GLANG.delete}" /></td>
					<td colspan="3"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
$(function(){
	$('.tags-tip').tooltip();
});
</script>
<!-- END: main -->