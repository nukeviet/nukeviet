<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="10px" class="text-center"></th>
				<th><strong>{LANG.order_code}</strong></th>
				<th><strong>{LANG.order_time}</strong></th>
				<th><strong>{LANG.order_email}</strong></th>
				<th align="right"><strong>{LANG.order_total}</strong></th>
				<th><strong>{LANG.order_payment}</strong></th>
				<th width="100px" class="text-center"><strong>{LANG.function}</strong></th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
		<tr {bgview}>
			<td><input type="checkbox" class="ck" value="{order_id}" {DIS} /></td>
			<td>{DATA.order_code}</td>
			<td>{DATA.order_time}</td>
			<td><a href="{DATA.link_user}" style="text-decoration:underline" target="_blank">{DATA.order_email}</a></td>
			<td align="right">{DATA.order_total} {DATA.unit_total}</td>
			<td>{DATA.status_payment}</td>
			<td class="text-center"><span class="edit_icon"><a href="{link_view}" title="">{LANG.view}</a></span>
			<!-- BEGIN: delete -->
			&nbsp;-&nbsp;<span class="delete_icon"><a href="{link_del}" class="delete" title="">{LANG.del}</a></span>
			<!-- END: delete -->
			</td>
		</tr>
		<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.prounit_select}</a> - <i class="fa fa-square-o">&nbsp;</i> <a href="#" id="uncheckall">{LANG.prounit_unselect}</a> - <i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.prounit_del_select}</a></td>
				<td colspan="5">{PAGES}</td>
			</tr>
		</tfoot>
	</table>
</div>
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
<!-- END: main -->