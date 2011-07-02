<!-- BEGIN: main -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
        <table class="tab1">
            <tbody>
                <tr>
                    <td>{LANG.config_is_showdes}</td>
                    <td>
                        <input name="is_showdes"{DATA.is_showdes} value="1" type="checkbox"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align:center;padding-top:15px">
            <input type="submit" name="submit" value="{LANG.save}" />
        </div>
    </form>
</div><!-- END: main -->
