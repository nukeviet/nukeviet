<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1" style="width:400px">
		<thead>
		<tr>
		<td>{LANG.comment_edit_title}</td>
		</tr>
		</thead>
		<tr>
			<td>
				<textarea name="content" style="width:600px;height:100px">{ROW.content}</textarea>
			</td>
		</tr>
		<tbody class="second">
			<tr>
				<td>
					<label><input type="checkbox" name="active" value="1" {ROW.status}/> {LANG.comment_edit_active}</label>
				</td>
			</tr>
		</tbody>
		<tr>
			<td>
				<label>
				<input type="checkbox" name="delete" value="1"/> {LANG.comment_edit_delete}
				</label>&nbsp;&nbsp;
				<input type="hidden" value="{CID}" name="cid"/>
				<input type="submit" name="submit" value="{LANG.comment_delete_accept}"/>
			</td>
		</tr>
	</table>
</form>
<!-- END: main -->