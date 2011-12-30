<!-- BEGIN: suspend -->
    <!-- BEGIN: suspend_info -->
    <div style="margin-top:10px;">{SUSPEND_INFO}</div>
    <!-- END: suspend_info -->
    <!-- BEGIN: suspend_info1 -->
    <table summary="" class="tab1">
        <caption>{SUSPEND_INFO}:</caption>
        <col span="2" valign="top" width="25%" />
        <col valign="top" width="50%" />
        <thead>
        <tr>
            <td>{SUSPEND_INFO2}</td>
            <td>{SUSPEND_INFO3}</td>
            <td>{SUSPEND_INFO4}</td>
        </tr>
        </thead>
        <!-- BEGIN: loop -->
        <tbody{CLASS}>
            <tr>
                <td>{VALUE0}</td>
                <td>{VALUE1}</td>
                <td>{VALUE2}</td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
    <br />
    <!-- END: suspend_info1 -->

<!-- BEGIN: change_suspend -->
    <div class="quote" style="width:780px;">
    <blockquote{CLASS}><span>{NEW_SUSPEND_CAPTION}</span></blockquote>
    </div>
    <div class="clear"></div>

    <form method="post" action="{ACTION}">
    <table summary="" style="margin-bottom:8px;margin-top:10px;width:800px;">
    <col valign="top" width="150px" />
    <col valign="top" width="10px" />
    <col valign="top" />

    <tfoot>
        <tr>
            <td colspan="2"><input name="save" id="save" type="hidden" value="1" /></td>
            <td><input name="go_change" type="submit" value="{SUBMIT}" /></td>
        </tr>
    </tfoot>
        
    <!-- BEGIN: new_reason -->
        <tr>
        <td>{NEW_REASON0}:</td>
        <td><sup class="required">&lowast;</sup></td>
        <td><input name="new_reason" id="new_reason" type="text" value="{NEW_REASON1}" style="width:300px" maxlength="{NEW_REASON2}" /></td>
        </tr>
    <!-- END: new_reason -->

    <tr>
    <td>{SENDMAIL}:</td>
    <td></td>
    <td><input name="sendmail" id="sendmail" type="checkbox" value="1"{CHECKED} /></td>
    </tr>

    <!-- BEGIN: clean_history -->
        <tr>
        <td>{CLEAN_HISTORY}:</td>
        <td></td>
        <td><input name="clean_history" id="clean_history" type="checkbox" value="1"{CHECKED1} /></td>
        </tr>
    <!-- END: clean_history -->
    </table>

    </form>
    <!-- END: change_suspend -->
<!-- END: suspend -->