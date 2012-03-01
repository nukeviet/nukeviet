<!-- BEGIN: main -->
<!-- BEGIN: edit -->
<form action="{FORM_ACTION}" method="post">
<input name="save" type="hidden" value="1" />
<table class="tab1">
	<tbody>
		<tr>
			<td>{DATA}</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td class="center"><input name="submit1" type="submit" value="{LANG.save}" /></td>
		</tr>
	</tfoot>
</table>
</form>
<!-- END: edit -->
<!-- BEGIN: data -->
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><a href="{URL_EDIT}" title="{GLANG.edit}" class="button1"><span><span>{GLANG.edit}</span></span></a></td>
		</tr>
	</tbody>
</table>
{DATA}
<!-- END: data -->
<!-- END: main -->
