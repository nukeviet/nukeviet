<!-- BEGIN: step -->
<script type="text/javascript">
$(document).ready(function(){
    $("#site_config").validate({
        rules :{
            nv_login :{
                minlength : 5
            },
            nv_password :{
                minlength : 6
            },
            re_password :{
                equalTo : "#nv_password_iavim"
            }
        }
    });
});
</script>
<form action="{ACTIONFORM}" id="site_config" method="post">
    <table cellspacing="0" summary="{LANG.website_info}">
        <caption>
            {LANG.properties} <span class="highlight_red">*</span>{LANG.is_required}
        </caption>
        <tr>
            <th scope="col" class="nobg" style="width: 200px;">&nbsp;</th>
            <th scope="col">{LANG.enter_form}</th>
            <th scope="col">{LANG.note}</th>
        </tr>
        <tr>
            <th scope="row" class="spec"> {LANG.sitename} <span class="highlight_red">*</span></th>
            <td>
            <input type="text" name="site_name" value="{DATA.site_name}" class="required" />
            </td>
            <td>{LANG.sitename_note}</td>
        </tr>
        <tr>
            <th scope="row" class="specalt"> {LANG.admin_account} <span class="highlight_red">*</span></th>
            <td class="alt">
            <input type="text" value="{DATA.nv_login}" name="nv_login" class="required" id="nv_login_iavim"/>
            </td>
            <td class="alt">{LANG.admin_account_note}</td>
        </tr>
        <tr>
            <th scope="row" class="spec"> {LANG.admin_email} <span class="highlight_red">*</span></th>
            <td>
            <input type="text" value="{DATA.nv_email}" name="nv_email" class="required email" id="nv_email_iavim"/>
            </td>
            <td>{LANG.admin_email_note}</td>
        </tr>
        <tr>
            <th scope="row" class="specalt"> {LANG.admin_pass} <span class="highlight_red">*</span></th>
            <td class="alt">
            <input autocomplete="off" type="password" value="{DATA.nv_password}" id="nv_password_iavim" name="nv_password" class="required" />
            </td>
            <td class="alt">{LANG.admin_pass_note}</td>
        </tr>
        <tr>
            <th scope="row" class="spec"> {LANG.admin_repass} <span class="highlight_red">*</span></th>
            <td>
            <input autocomplete="off" type="password" value="{DATA.re_password}" id="re_password_iavim" name="re_password" class="required" />
            </td>
            <td>{LANG.admin_repass_note}</td>
        </tr>
        <tr>
            <th scope="row" class="specalt"> {LANG.question} <span class="highlight_red">*</span></th>
            <td class="alt">
            <input type="text" value="{DATA.question}" id="question" name="question" class="required" />
            </td>
            <td class="alt">{LANG.question_note}</td>
        </tr>
        <tr>
            <th scope="row" class="spec"> {LANG.answer_question} <span class="highlight_red">*</span></th>
            <td>
            <input type="text" value="{DATA.answer_question}"  id="answer_question" name="answer_question" class="required" />
            </td>
            <td>{LANG.answer_question_note}</td>
        </tr>
        <tr>
            <th scope="row" class="specalt"> {LANG.lang_multi}</th>
            <td class="alt">
            <input type="checkbox" value="1" name="lang_multi" {CHECK_LANG_MULTI}/>
            </td>
            <td class="alt">{LANG.lang_multi_note}</td>
        </tr>
        <tr>
            <th class="spec">&nbsp;</th>
            <td class="spec" colspan="2">
            <input class="button" type="submit" value="{LANG.refesh}" />
            </td>
        </tr>
    </table><!-- BEGIN: errordata --><span class="highlight_red"> {DATA.error} </span>
    <!-- END: errordata -->
</form>
<ul class="control_t fr">
    <li>
        <span class="back_step"><a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5">{LANG.previous}</a></span>
    </li>
    <!-- BEGIN: nextstep -->
    <li>
        <span class="next_step"><a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=7">{LANG.next_step}</a></span>
    </li>
    <!-- END: nextstep -->
</ul>
<script type="text/javascript">
//<![CDATA[
document.getElementById('site_config').setAttribute("autocomplete", "off");
//]]>
</script>
<!-- END: step -->