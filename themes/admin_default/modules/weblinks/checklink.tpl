<!-- BEGIN: main -->
<!-- BEGIN: check -->
<table class="tab1">
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td>{GENERATE_PAGE}</td>
		</tr>
	</tfoot>
	<!-- END: generate_page -->
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<!-- BEGIN: ok -->
			{URL} - OK
			<!-- END: ok -->
			<!-- BEGIN: error -->
			<span style="text-decoration:line-through">{URL}</span> {LANG.weblink_check_error}
			<!-- END: error -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: check -->
<!-- BEGIN: form -->
<div class="quote">
	<blockquote><span>{LANG.weblink_check_notice}</span></blockquote>
</div>
<table class="tab1">
	<tbody>
		<tr>
			<td>
			<form name="confirm" action="{FORM_ACTION}" method="post">
				<input type="hidden" name="ok" value="1">
				<input type="submit" value="{LANG.weblink_check_confirm}" name="submit">
			</form></td>
		</tr>
	</tbody>
</table>
<!-- END: form -->
<!-- END: main -->