<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.change_question_pagetitle}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
                <a href="{URL_HREF}main">{LANG.user_info}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
                <!-- BEGIN: allowopenid --><strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid -->
                <!-- BEGIN: regroups --><strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a><!-- END: regroups -->
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->
            </span>
        </div>
    <div class="clear"></div>
    </div>
    <ol id="info" style="padding-top:10px">
        <li>
            <strong>{LANG.changequestion_info}:</strong>
        </li>
        <li>
            <span>-</span>{LANG.changequestion_info1}
        </li>
        <li>
            <span>-</span>{LANG.changequestion_info2}
        </li>
    </ol>
    <!-- BEGIN: step1 -->
    <form id="changeQuestionForm" class="register1 clearfix" action="{FORM1_ACTION}" method="post">
        <div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
            <strong>{DATA.info}</strong>
        </div>
        <div class="clearfix rows">
            <label>
                {LANG.password}
            </label>
            <input class="required password" name="nv_password" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" />
        </div>
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="submit" value="{LANG.changequestion_submit1}" class="submit" />
    </form>
    <!-- END: step1 -->
    <!-- BEGIN: step2 -->
<script type="text/javascript">
    function question_change()
    {
        var question_option = document.getElementById( 'question' ).options[document.getElementById( 'question' ).selectedIndex].value;
        document.getElementById( 'question' ).value = '0';
        document.getElementById( 'your_question' ).value = question_option;
        document.getElementById( 'your_question' ).focus();
        return;
    } 
</script>
    <form id="changeQuestionForm" class="register1" action="{FORM2_ACTION}" method="post">
        <div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
            <strong>{DATA.info}</strong>
        </div>
        <div class="content">
            <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.question}
                    </label>
                </dt>
                <dd class="fr">
                    <select name="question" id="question" onchange="question_change();">
                        <!-- BEGIN: frquestion -->
                        <option value="{QUESTIONVALUE}">{QUESTIONTITLE}</option>
                        <!-- END: frquestion -->
                    </select>
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.your_question}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required" name="your_question" id="your_question" value="{DATA.your_question}" />
                </dd>
            </dl>
            <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.answer_your_question}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required" name="answer" value="{DATA.answer}" />
                </dd>
            </dl>
        </div>
        <input type="hidden" name="nv_password" value="{DATA.nv_password}" />
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="hidden" name="send" value="1" />
        <input type="submit" value="{LANG.changequestion_submit2}" class="submit" />
    </form>
    <!-- END: step2 -->
</div>
<!-- END: main -->