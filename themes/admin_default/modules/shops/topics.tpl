<!-- BEGIN: main -->
<script type="text/javascript">
	var url_back = '{url_back}';
	var url_change_weight = '{url_change}';
</script>
<table class="table table-striped table-bordered table-hover" style="margin-bottom:0">
	<tbody>
		<tr>
			<td width="120px"><strong>{LANG.content_cat}</strong></td>
			<td>
			<select class="form-control" name="catid" style="width:300px" onChange="changecatid (this)">
				<!-- BEGIN: rowscat -->
				<option value="{catid_i}" {select} >{xtitle_i} {title_i}</option>
				<!-- END: rowscat -->
			</select></td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-hover" style="margin-bottom:0">
	<thead>
		<tr>
			<td width="30px" class="text-center">{LANG.weight}</td>
			<td><strong>{LANG.topic_title}</strong></td>
			<td><strong>{LANG.topic_sub}</strong></td>
			<td width="120px" class="text-center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<!-- BEGIN: listrow -->
	<tr {bg}>
		<td class="text-center"> {ROW.slect_weight} </td>
		<td>{ROW.title}</td>
		<td>{ROW.keywords}</td>
		<td class="text-center"><span class="edit_icon"><a href="{ROW.link_edit}" title="">{LANG.edit}</a></span>&nbsp; <span class="delete_icon"><a href="{ROW.link_del}" class="delete" title="">{LANG.del}</a></span></td>
	</tr>
	<!-- END: listrow -->
	<tfoot>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
	</tfoot>
</table>
<form class="form-inline" action="" method="post"><input name="save" type="hidden" value="1" />
	<table summary="{DATA.caption}" class="table table-striped table-bordered table-hover">
		<caption>{DATA.caption}</caption>
		<tfoot>
			<tr>
				<td colspan="2"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.prounit_save}" /></td>
			</tr>
		</tfoot>
		<tr>
			<td align="right" width="180px"><strong>{LANG.topic_title}: </strong></td>
			<td><input class="form-control" style="width:50%" name="title" type="text" value="{DATA.title}" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="right"><strong>{LANG.alias}: </strong></td>
			<td><input class="form-control" style="width:50%" name="alias" type="text" value="{DATA.alias}" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="right"><strong>{LANG.topic_sub}: </strong>
			<br />
			{LANG.topic_sub_note} </td>
			<td><textarea style="width:50%; height:50px" name="keywords">{DATA.keywords}</textarea></td>
		</tr>
	</table>
	<br>
</form>
<script type="text/javascript">
	function changecatid(ob) {
		catid = $(ob).val();
		window.location = '{LINK_CHANGE}' + catid;
	}

	$('a.delete').click(function(event) {
		event.preventDefault();
		if (confirm("{LANG.topic_delete_confirm}")) {
			var href = $(this).attr('href');
			$.ajax({
				type : 'POST',
				url : href,
				data : '',
				success : function(data) {
					window.location = url_back;
				}
			});
		}
	});
</script>
<!-- END: main -->