<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="quote" style="width:780px;">
    <blockquote class="error">
        <p>
            <span>{LANG.modforum}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: is_forum -->
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<!-- BEGIN: add_user -->
<form id="form_add_user" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
    <table class="tab1">
        <tbody>
            <tr>
                <td>
                    {LANG.account}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" value="{DATA.username}" name="username" id="username_iavim" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.email}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" value="{DATA.email}" name="email" id="email_iavim" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.password}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" type="password" style="width: 150px" id="pass_iavim" name="password1" value="{DATA.password1}" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.repassword}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" type="password" style="width: 150px" name="password2" value="{DATA.password2}" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.question}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.question}" name="question" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.answer}
                </td>
                <td style="width:10px">
                    (<span style="color:#FF0000">*</span>)
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.answer}" name="answer" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.name}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.full_name}" name="full_name" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td colspan="2">
                    {LANG.gender}
                </td>
                <td>
                    <select name="gender">
                        <!-- BEGIN: gender -->
                        <option value="{GENDER.key}"{GENDER.selected}>{GENDER.title}</option>
                        <!-- END: gender -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.avata}
                </td>
                <td>
                    <input type="file" name='photo' />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td colspan="2">
                    {LANG.birthday}
                </td>
                <td>
                    <input name="birthday" id="birthday" value="{DATA.birthday}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
                    <img src="{NV_BASE_SITEURL}images/calendar.jpg" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'birthday', 'dd.mm.yyyy', true);" alt="" height="17" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.website}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.website}" name="website" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td colspan="2">
                    {LANG.address}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.location}" name="location" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.ym}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.yim}" name="yim" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td colspan="2">
                    {LANG.phone}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.telephone}" name="telephone" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.fax}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.fax}" name="fax" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td colspan="2">
                    {LANG.mobile}
                </td>
                <td>
                    <input class="txt" type="text" value="{DATA.mobile}" name="mobile" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    {LANG.show_email}
                </td>
                <td>
                    <input type="checkbox" name="view_mail" value="1"{DATA.view_mail} />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td style="vertical-align:top" colspan="2">
                    {LANG.sig}
                </td>
                <td>
                    <textarea name="sig" cols="70" rows="5" style="width:300px">{DATA.sig}</textarea>
                </td>
            </tr>
        </tbody>
        <!-- BEGIN: group -->
        <tbody>
            <tr>
                <td style="vertical-align:top" colspan="2">
                    {LANG.in_group}
                </td>
                <td>
                    <ul>
                        <!-- BEGIN: list -->
                        <li>
                            <input type="checkbox" value="{GROUP.id}" name="group[]"{GROUP.checked} />
                            <span>{GROUP.title}</span>
                        </li>
                        <!-- END: list -->
                    </ul>
                </td>
            </tr>
        </tbody>
        <!-- END: group -->
        <tbody>
            <tr>
                <td colspan="3">
                    <input type="submit" name="confirm" value="{LANG.member_add}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
//<![CDATA[
document.getElementById('form_add_user').setAttribute("autocomplete", "off");
//]]>
</script>
<!-- END: add_user -->
<!-- END: main -->