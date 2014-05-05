<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<div class="quote">
	<blockquote class="{CLASSERROR}"><span>{LANG.vmodule_blockquote}</span></blockquote>
</div>

<form id="vform" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input name="checkss" type="hidden" value="{CHECKSS}" />
	<table class="tab1">
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input name="submit1" type="submit" value="{GLANG.submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="w250 right"><strong>{LANG.vmodule_name}: </strong></td>
				<td><input class="w250 required" style="margin-right: 10px;" name="title" type="text" value="{TITLE}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.vmodule_file}: </strong></td>
				<td>
				<select name="module_file" class="required" style="margin-right: 10px;">
					<option value="">{LANG.vmodule_select}</option>
					<!-- BEGIN: modfile -->
					<option value="{MODFILE.key}"{MODFILE.selected}>{MODFILE.key}</option>
					<!-- END: modfile -->
				</select></td>
			</tr>
			<tr>
				<td class="right top">
				<br />
				<strong>{LANG.vmodule_note}:</strong></td>
				<td><textarea style="width: 450px" name="note" cols="80" rows="5">{NOTE}</textarea></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('#vform').validate({
			rules : {
				title : {
					minlength : 3
				}
			}
		});
	});
	//]]>
</script>
<!-- END: main -->