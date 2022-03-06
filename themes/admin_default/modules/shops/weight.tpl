<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<colgroup>
		<col />
		<col class="w150" />
		<col />
		<col span="2" class="w200" />
		<col class="w150" />
	</colgroup>
	<thead>
		<tr>
			<th width="10px" class="text-center">&nbsp;</th>
			<th>{LANG.weight_sign}</th>
			<th>{LANG.currency}</th>
			<th>{LANG.weight_convention} <em class="fa fa-info-circle text-info" data-toggle="tooltip" title="" data-original-title="{LANG.weight_convention_note}">&nbsp;</em></th>
			<th>{LANG.round}</th>
			<th class="text-center">{LANG.function}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.prounit_select}</a>&nbsp;&nbsp;<i class="fa fa-square-o">&nbsp;</i><a href="#" id="uncheckall">{LANG.prounit_unselect}</a>&nbsp;&nbsp;<i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.prounit_del_select}</a></td>
		</tr>
	</tfoot>
	<tbody>
	<!-- BEGIN: row -->
		<tr>
			<td><input type="checkbox" class="ck" value="{ROW.id}" /></td>
			<td>{ROW.code}</td>
			<td>{ROW.title}</td>
			<td>1 {ROW.code} = {ROW.exchange} {WEIGHT_UNIT}</td>
			<td>{ROW.round}</td>
			<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{ROW.link_edit}" title="">{LANG.edit}</a>&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="{ROW.link_del}" class="delete" title="">{LANG.del}</a></td>
		</tr>
	<!-- END: row -->
	</tbody>
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

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form class="form-inline" action="" method="post"><input name="savecat" type="hidden" value="1" />
	<table class="table table-striped table-bordered table-hover">
		<caption>{DATA.caption}</caption>
		<tbody>
			<tr>
				<td align="right" width="150px"><strong>{LANG.weight_sign} <span class="red">*</span></strong></td>
				<td><input class="form-control" style="width: 500px" name="code" type="text" value="{DATA.code}" maxlength="255" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
			</tr>
			<tr>
				<td valign="top" align="right"><strong>{LANG.currency} <span class="red">*</span></strong></td>
				<td><input class="form-control" style="width: 500px" name="title" type="text" value="{DATA.title}" maxlength="255" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
			</tr>
			<tr>
				<td valign="top" align="right"><strong>{LANG.weight_convention} <span class="red">*</span></strong></td>
				<td>
					<input class="form-control" style="width: 500px" name="exchange" type="text" value="{DATA.exchange}" maxlength="255" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
					<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="" data-original-title="{LANG.weight_convention_note}">&nbsp;</em>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right"><strong>{LANG.round}: </strong></td>
				<td>
					<select class="form-control" name="round">
						<!-- BEGIN: round -->
						<option value="{ROUND.round1}"{ROUND.selected}>{ROUND.round2}</option>
						<!-- END: round -->
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	<div class="text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.prounit_save}" />
	</div>
</form>
<!-- END: main -->