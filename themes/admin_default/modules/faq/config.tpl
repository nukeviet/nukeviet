<!-- BEGIN: main -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
        <table class="tab1">
            <tbody>
                <tr>
                    <td width="260">{LANG.config_type_main}</td>
                    <td>
                        <select name="type_main">
                            <!-- BEGIN: type_main -->
                            <option value="{TYPE_MAIN.key}"{TYPE_MAIN.selected}> {TYPE_MAIN.title}</option>
                            <!-- END: type_main -->
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align:center;padding-top:15px">
            <input type="submit" name="submit" value="{LANG.faq_save}" />
        </div>
    </form>
</div><!-- END: main -->
