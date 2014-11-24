<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<td width="20px" class="text-center">&nbsp;</td>
			<td><strong>{LANG.prounit_name_unit}</strong></td>
			<td><strong>{LANG.prounit_name_note}</strong></td>
			<td width="120px" class="text-center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: row -->
		<tr>
			<td><input type="checkbox" class="ck" value="{id}" /></td>
			<td>{title}</td>
			<td>{note}</td>
			<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{link_edit}" title="">{LANG.edit}</a>&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="{link_del}" class="delete" title="">{LANG.del}</a></td>
		</tr>
	<!-- END: row -->
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.prounit_select}</a> - <i class="fa fa-square-o">&nbsp;</i> <a href="#" id="uncheckall">{LANG.prounit_unselect}</a> - <i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.prounit_del_select}</a></td>
		</tr>
	</tfoot>
</table>
<script type='text/javascript'>
	$(function() {
		$('#checkall').click(function() {
			$('input:checkbox').each(function() {
				$(this).attr('checked', 'checked');
			});
		});
		$('#uncheckall').click(function() {
			$('input:checkbox').each(function() {
				$(this).removeAttr('checked');
			});
		});
		$('#delall').click(function() {
			if (confirm("{LANG.prounit_del_confirm}")) {
				var listall = [];
				$('input.ck:checked').each(function() {
					listall.push($(this).val());
				});
				if (listall.length < 1) {
					alert("{LANG.prounit_del_no_items}");
					return false;
				}
				$.ajax({
					type : 'POST',
					url : '{URL_DEL}',
					data : 'listall=' + listall,
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a.delete').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.prounit_del_confirm}")) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
</script>
<!-- END: data -->
<form class="form-inline" action="" method="post">
	<input name="savecat" type="hidden" value="1" />
	<table class="table table-striped table-bordered table-hover">
		<caption>{caption}</caption>
		<tr>
			<td align="right" width="150px"><strong>{LANG.prounit_name_unit}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="title" type="text" value="{DATA.title}" maxlength="255" /></td>
		</tr>
		<tr>
			<td valign="top" align="right"><strong>{LANG.prounit_name_note}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="note" type="text" value="{DATA.note}" maxlength="255" /></td>
		</tr>
	</table>
	<br>
	<div class="text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.prounit_save}" />
	</div>
</form>
<!-- END: main -->