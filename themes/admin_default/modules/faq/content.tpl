<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error">
		<p>
			<span>{ERROR}</span>
		</p></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td> {LANG.faq_title_faq} </td>
				<td><input class="w300" type="text" value="{DATA.title}" name="title" id="title" /></td>
			</tr>
			<tr>
				<td> {LANG.faq_catid_faq} </td>
				<td>
				<select name="catid">
					<!-- BEGIN: catid -->
					<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
					<!-- END: catid -->
				</select></td>
			</tr>
			<tr>
				<td style="vertical-align:top"> {LANG.faq_question_faq} </td>
				<td><textarea name="question" id="question" style="width:300px;height:150px">{DATA.question}</textarea></td>
			</tr>
		</tbody>
	</table>

	<div style="textarea-align:center;padding-top:15px">
		{LANG.faq_answer_faq}
		<br />
		{DATA.answer}
	</div>

	<div style="text-align:center;padding-top:15px">
		<input type="submit" name="submit" value="{LANG.faq_save}" />
	</div>
</form>
<!-- END: main -->