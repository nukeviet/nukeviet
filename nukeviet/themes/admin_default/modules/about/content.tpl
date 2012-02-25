<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width:98%" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:150px"><strong>{LANG.aabout2}</strong></td>
                <td><input class="txt-half" type="text" value="{TITLE}" name="title" id="idtitle" maxlength="255" /></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.alias}</strong></td>
                <td>
					<input class="txt-half" type="text" value="{ALIAS}" name="alias" id="idalias" maxlength="255" />
					<img src="{NV_BASE_SITEURL}images/refresh.png" width="16" class="refresh" onclick="get_alias('{ID}');" alt="Get alias..." height="16" />
				</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
					<p><strong>{LANG.aabout11}</strong></p>
					{BODYTEXT}
				</td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.keywords}</strong></td>
                <td><input class="txt-full" type="text" value="{KEYWORDS}" name="keywords" /></td>
            </tr>
        </tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
    </table>
</form>
<!-- BEGIN: get_alias --><script type="text/javascript">
$(document).ready(function(){$('#idtitle').change(function(){get_alias('{ID}');});});
</script>
<!-- END: get_alias -->
<!-- END: main -->
