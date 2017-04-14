<!-- BEGIN: main -->
<div class="row" style="margin-bottom: 5px">
	<div class="col-xs-24">
		<div class="btn-group pull-left">
			<a href="{TEM_ADD}" class="btn btn-info">{LANG.template_add}</a>
		</div>

		<div class="btn-group pull-right">
			<button type="button" class="btn btn-primary btn-xs" id="field">
				{LANG.field_id}
			</button>
			<button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
				<span class="sr-only"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{FIELD_ADD}">{LANG.captionform_add}</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#field').click(function(){
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields';
	});
</script>

<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<colgroup>
		<col class="w50" />
		<col />
		<col span="2" class="w150" />
	</colgroup>
	<thead>
		<tr>
			<th class="text-center">&nbsp;</th>
			<th>{LANG.template_name}</th>
			<th class="text-center">{LANG.status}</th>
			<th class="text-center"><strong>{LANG.function}</strong></th>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: row -->
		<tr>
			<td class="text-center"><input type="checkbox" class="ck" value="{id}" /></td>
			<td><a href="{FIELD_TAB}">{title}</a></td>
			<td class="text-center"><input type="checkbox" id="change_active_{id}" onclick="nv_change_active({id})" {active} /></td>
			<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{link_edit}" title="">{LANG.edit}</a>&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="{link_del}" class="delete" title="">{LANG.del}</a></td>
		</tr>
		<!-- END: row -->
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.prounit_select}</a> - <i class="fa fa-square-o">&nbsp;</i><a href="#" id="uncheckall">{LANG.prounit_unselect}</a> - <i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.prounit_del_select}</a></td>
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
						var r_split = data.split('_');
						if (r_split[0] != 'OK') {
							alert(r_split[1]);
						} else {
							window.location = '{URL_DEL_BACK}';
						}
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
						var r_split = data.split('_');
						if (r_split[0] != 'OK') {
							alert(r_split[1]);
						} else {
							window.location = '{URL_DEL_BACK}';
						}
					}
				});
			}
		});
	});

	function nv_change_active(id) {
		var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=template&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

			});
		} else {
			$('#change_active_' + id).prop('checked', new_status ? false : true);
		}
	}
</script>
<!-- END: data -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{error}
</div>
<!-- END: error -->
<form action="" method="post" id="add">
	<input name="savecat" type="hidden" value="1" />
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{caption}
		</caption>
		<tr>
			<th class="text-right w200">{LANG.template_name}: </strong><span class="red">*</span></th>
			<td class="w500"><input class="form-control w500" name="title" type="text" value="{DATA.title}" maxlength="150" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
			<td><input class="btn btn-primary" name="submit" type="submit" value="{LANG.template_save}" /></td>
		<tr>
	</table>
</form>
<!-- END: main -->