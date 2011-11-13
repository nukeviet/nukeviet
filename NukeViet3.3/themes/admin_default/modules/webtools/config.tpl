<!-- BEGIN: main -->
<form action="" method="post">
    <table class="tab1" summary="">
    	<col style="width:50%" />
    	<col style="width:50%" />
        <tfoot>
            <tr>
                <td align="center" colspan="2">
                    <input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.autocheckupdate}</strong>
                </td>
                <td>
                    <input type="checkbox" value="1" name="autocheckupdate" {AUTOCHECKUPDATE} />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
        <tr>
            <td>
                <strong>{LANG.updatetime}</strong>
            </td>
            <td>
                <select name="autoupdatetime">
                    <!-- BEGIN: updatetime -->
						<option value="{VALUE}" {SELECTED}>{TEXT} </option>
                    <!-- END: updatetime -->
                </select> ({LANG.hour})
            </td>
        </tr>
		</tbody>
    </table>
</form>
<!-- END: main -->
