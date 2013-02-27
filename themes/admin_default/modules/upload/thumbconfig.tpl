<!-- BEGIN: main -->
<div class="quote" style="width:98%">
	<blockquote><span>{LANG.thumb_note}</span></blockquote>
</div>
<div class="clear"></div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<form action="" method="post" id="frm">
	<table class="tab1" style="auto">
		<col width="230"/>
		<thead>
			<tr align="center">
				<td>{LANG.thumb_dir}</td>
				<td>{LANG.thumb_type}</td>
				<td>{LANG.thumb_width_height}</td>
				<td>{LANG.thumb_quality}</td>
			</tr>
		</thead>
		<!-- BEGIN: loop -->
		<tbody {DATA.class}>
			<tr align="center">
				<td  align="left"><b>{DATA.dirname}</b></td>
				<td>
				<select name="thumb_type[{DATA.did}]">
					<!-- BEGIN: thumb_type -->
					<option value="{TYPE.id}" {TYPE.selected}>{TYPE.name}</option>
					<!-- END: thumb_type -->
				</select></td>
				<td><input class="digits" style="width:40px; text-align: right" type="text" value="{DATA.thumb_width}" name="thumb_width[{DATA.did}]" maxlength="3"/> x <input class="digits" style="width:40px; text-align: right" type="text" value="{DATA.thumb_height}" name="thumb_height[{DATA.did}]" maxlength="3"/></td>
				<td><input class="digits" style="width:40px; text-align: right" type="text" value="{DATA.thumb_quality}" name="thumb_quality[{DATA.did}]" maxlength="2"/></td>
			</tr>
		</tbody>
		<!-- END: loop -->
		<tbody {DATA.class}>
			<tr align="center">
				<td align="left">
				<select name="other_dir">
					<option value=""> ---- </option>
					<!-- BEGIN: other_dir -->
					<option value="{OTHER_DIR.did}">{OTHER_DIR.dirname}</option>
					<!-- END: other_dir -->
				</select></td>
				<td>
				<select name="other_type">
					<!-- BEGIN: other_type -->
					<option value="{TYPE.id}">{TYPE.name}</option>
					<!-- END: other_type -->
				</select></td>
				<td><input class="digits" style="width:40px; text-align: right" type="text" value="100" name="other_thumb_width" maxlength="3"/> x <input class="digits" style="width:40px; text-align: right" type="text" value="120" name="other_thumb_height" maxlength="3"/></td>
				<td><input class="digits" style="width:40px; text-align: right" type="text" value="90" name="other_thumb_quality" maxlength="2"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="center"><input type="submit" value="{LANG.pubdate}" name="submit"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
	}); 
</script>
<!-- END: main -->