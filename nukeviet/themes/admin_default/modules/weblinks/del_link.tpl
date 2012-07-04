<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<!-- BEGIN: confirm -->
<div id="list_mods">
	<form name="del_link" action="" method="post">
		<table summary="" class="tab1">
			<input type="hidden" name="id" value="{ID}">
			<tr>
				<td width="50px" style="padding-left:60px">{LANG.weblink_del_link_confirm}</td>
			</tr>
			<tr>
				<td width="50px" style="padding-left:80px">
					<label><input name="confirm" type="radio" value="1" id="confirm_0">{LANG.weblink_yes}</label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input name="confirm" type="radio" value="0" id="confirm_1"/>{LANG.weblink_no}</label>
				</td>
			</tr>
			<tbody class="second">
				<tr>
					<td align="left"><input name="submit" style="width:80px;margin-left:80px" type="submit" value="{LANG.weblink_submit}" /></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<!-- END: confirm -->
<!-- END: main -->
