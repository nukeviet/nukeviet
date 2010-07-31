<!-- BEGIN: del -->
<div class="quote" style="width:780px;">
<blockquote{CLASS}><span>{TITLE}</span>
</blockquote>
</div>
<div class="clear">
</div>
<form method="post" action="{ACTION}">
    <table style="margin-bottom:8px;width:800px;">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" />
        <tr>
            <td>
                {SENDMAIL}:
            </td>
            <td>
            </td>
            <td>
                <input name="sendmail" id="sendmail" type="checkbox" value="1"{CHECKED} />
            </td>
        </tr>
    </table>
    <table style="margin-bottom:8px;width:800px;">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" />
        <tr>
            <td>
                {REASON0}:
            </td>
            <td>
            </td>
            <td>
                <input name="reason" id="reason" type="text" value="{REASON1}" style="width:300px" maxlength="{REASON2}" />
            </td>
        </tr>
    </table>
    <table style="margin-bottom:8px;width:800px;">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" />
        <tr>
            <td>
                {ADMIN_PASSWORD0}:
            </td>
            <td>
                <sup class="required">
                    &lowast;
                </sup>
            </td>
            <td>
                <input name="adminpass_iavim" id="adminpass_iavim" type="password" value="{ADMIN_PASSWORD1}" style="width:300px" maxlength="{ADMIN_PASSWORD2}" />
            </td>
        </tr>
    </table>
    <br/>
    <table style="margin-bottom:8px;width:800px;">
        <col valign="top" width="160px" />
        <tr>
            <td>
                <input name="ok" id="ok" type="hidden" value="1" />
            </td>
            <td>
                <input name="go_del" type="submit" value="{SUBMIT}" />
            </td>
        </tr>
    </table>
</form>
<!-- END: del -->