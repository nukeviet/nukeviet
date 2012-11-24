<!-- BEGIN: main -->
<!-- BEGIN: complete -->
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{LANG.setup_updated_layout}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: complete -->
<form method="post" action="" name="setuplayout">
	<table class="tab2">
		<tr>
			<!-- BEGIN: loop -->
			<td style="vertical-align:top">
				<strong>{MOD_NAME_TITLE}</strong>
				<div class="hr"></div>
				<!-- BEGIN: func -->
				<span style="display:inline-block;width:150px">{FUNC_ARR_VAL.1}</span>
				<select name="func[{FUNC_ARR_VAL.0}]" class="function">
					<!-- BEGIN: option -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.key}</option>
					<!-- END: option -->
				</select>
				<br/>
				<!-- END: func -->
				<!-- BEGIN: endtr --></td></tr>&nbsp;<tr><!-- END: endtr -->
				<!-- BEGIN: endtd --></td><!-- END: endtd -->
			<!-- END: loop -->
			<!-- BEGIN: fixend --><td>&nbsp;</td><!-- END: fixend -->
		</tr>
		<tr>
			<td colspan="3" class="center">
				<input name="save" type="submit" value="{LANG.setup_save_layout}"/>
			</td>
		</tr>
	</table>
</form>
<!-- END: main -->