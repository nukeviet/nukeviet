<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.thumb_note}</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr class="text-center">
					<th>{LANG.thumb_dir}</th>
					<th>{LANG.thumb_type}</th>
					<th>{LANG.thumb_width_height}</th>
					<th>{LANG.thumb_quality}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4" class="text-center"><input type="submit" value="{LANG.pubdate}" name="submit" class="btn btn-primary"/></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr class="text-center">
					<td  class="text-left"><strong>{DATA.dirname}</strong></td>
					<td>
					<select name="thumb_type[{DATA.did}]" class="form-control">
						<!-- BEGIN: thumb_type -->
						<option value="{TYPE.id}" {TYPE.selected}>{TYPE.name}</option>
						<!-- END: thumb_type -->
					</select></td>
					<td><input class="form-control w50 pull-left" type="text" value="{DATA.thumb_width}" name="thumb_width[{DATA.did}]" maxlength="3"/><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input class="form-control pull-left w50" type="text" value="{DATA.thumb_height}" name="thumb_height[{DATA.did}]" maxlength="3"/></td>
					<td><input class="form-control w50" type="text" value="{DATA.thumb_quality}" name="thumb_quality[{DATA.did}]" maxlength="2"/></td>
				</tr>
				<!-- END: loop -->
				<tr class="text-center">
					<td class="text-left">
					<select name="other_dir" class="form-control">
						<option value=""> ---- </option>
						<!-- BEGIN: other_dir -->
						<option value="{OTHER_DIR.did}">{OTHER_DIR.dirname}</option>
						<!-- END: other_dir -->
					</select></td>
					<td>
					<select name="other_type" class="form-control">
						<!-- BEGIN: other_type -->
						<option value="{TYPE.id}">{TYPE.name}</option>
						<!-- END: other_type -->
					</select></td>
					<td><input class="form-control w50 pull-left" type="text" value="100" name="other_thumb_width" maxlength="3"/><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input class="form-control w50 pull-left" type="text" value="120" name="other_thumb_height" maxlength="3"/></td>
					<td><input class="form-control w50" type="text" value="90" name="other_thumb_quality" maxlength="2"/></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
	});
</script>
<!-- END: main -->