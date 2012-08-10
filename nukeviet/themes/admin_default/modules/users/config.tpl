<!-- BEGIN: main -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
        <table class="tab1">
            <!-- BEGIN: user_forum -->
            <tbody class="second">
                <tr>
                    <td>{LANG.is_user_forum}</td>
                    <td>
                        <input name="is_user_forum" value="1" type="checkbox"{DATA.is_user_forum} />
                    </td>
                </tr>
            </tbody>
            <!-- END: user_forum -->
            <tbody>
                <tr>
                    <td>{LANG.type_reg}</td>
                    <td>
                        <select name="allowuserreg">
                            <!-- BEGIN: registertype -->
                            <option value="{REGISTERTYPE.id}"{REGISTERTYPE.select}> {REGISTERTYPE.value}</option>
                            <!-- END: registertype -->
                        </select>
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.whoviewlistuser}</td>
                    <td>
                        <select name="whoviewuser">
                            <!-- BEGIN: whoviewlistuser -->
                            <option value="{WHOVIEW.id}"{WHOVIEW.select}> {WHOVIEW.value}</option>
                            <!-- END: whoviewlistuser -->
                        </select>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.allow_login}</td>
                    <td>
                        <input name="allowuserlogin" value="1" type="checkbox"{DATA.allowuserlogin} />
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.allow_public}</td>
                    <td>
                        <input name="allowuserpublic" value="1" type="checkbox"{DATA.allowuserpublic} />
                    </td>
                </tr>
            </tbody>
            <tbody >
                <tr>
                    <td>{LANG.allow_question}</td>
                    <td>
                        <input name="allowquestion" value="1" type="checkbox"{DATA.allowquestion} />
                    </td>
                </tr>
            </tbody>
            <tbody class="second" >
                <tr>
                    <td>{LANG.allow_change_login}</td>
                    <td>
                        <input name="allowloginchange" value="1" type="checkbox"{DATA.allowloginchange} />
                    </td>
                </tr>
            </tbody>
            <tbody >
                <tr>
                    <td>{LANG.allow_change_email}</td>
                    <td>
                        <input name="allowmailchange" value="1" type="checkbox"{DATA.allowmailchange} />
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.allow_openid}</td>
                    <td>
                        <input name="openid_mode" value="1" type="checkbox"{DATA.openid_mode} />
                    </td>
                </tr>
            </tbody>
            <tbody >
                <tr>
                    <td>{LANG.openid_servers}</td>
                    <td>
                        <!-- BEGIN: openid_servers -->
                        <input name="openid_servers[]" value="{OPENID.name}" type="checkbox"{OPENID.checked} /> {OPENID.name}<br />
                        <!-- END: openid_servers -->
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.deny_email}</td>
                    <td>
                        <textarea name="deny_email" rows="7" cols="70">{DATA.deny_email}</textarea>
                    </td>
                </tr>
            </tbody>
            <tbody >
                <tr>
                    <td>{LANG.deny_name}</td>
                    <td>
                        <textarea name="deny_name" rows="7" cols="70">{DATA.deny_name}</textarea>
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="submit" value="{LANG.save}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div><!-- END: main -->
