<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.register}</h2>
    <div style="text-align:center;padding:10px">
        {DATA.info}
    </div>
    <form id="registerForm" action="{USER_REGISTER}" method="post" class="register">
        <div class="content">
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.name}
                    </label>
                </dd>
                <dt class="fr">
                    <input class="txt" name="full_name" value="{DATA.full_name}" maxlength="255" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.email}
                    </label>
                </dd>
                <dt class="fr">
                    <input class="txt email required" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.account}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required" style="margin-right:2px;margin-top:2px;" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.password}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required password" name="password" value="{DATA.password}" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
                </dd>
            </dl>
            <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.re_password}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required password" name="re_password" value="{DATA.re_password}" id="nv_re_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.question}
                    </label>
                </dt>
                <dd class="fr">
                    <select name="question">
                        <!-- BEGIN: frquestion -->
                        <option value="{QUESTIONVALUE.qid}"{QUESTIONVALUE.selected}>{QUESTIONVALUE.title}</option>
                        <!-- END: frquestion -->
                    </select>
                </dd>
            </dl>
            <!-- BEGIN: groups -->
             <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.in_group}
                    </label>
                </dt>
                <dd class="fr">
                <!-- BEGIN: list -->                      
                      <input type="checkbox" value="{GROUP.id}" name="group[]"{GROUP.checked} />
                       <span>{GROUP.title}</span>
                <!-- END: list -->
                </dd>
            </dl>
            <!-- END: groups -->
            <dl class="clearfix gray">
                <dt class="fl">
                    <label>
                        {LANG.your_question}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt" name="your_question" value="{DATA.your_question}" />
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.answer_your_question}
                    </label>
                </dt>
                <dd class="fr">
                    <input class="txt required" name="answer" value="{DATA.answer}" />
                </dd>
            </dl>
            <!-- BEGIN: captcha -->
            <dl class="clearfix captcha gray">
                <dt class="fl">
                    <label>
                        {LANG.captcha}
                    </label>
                </dt>
                <dd class="fr" style="width:290px;">
                    <p style="text-align:left;">
                        <img id="vimg" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimg','nv_seccode_iavim');"/>
                    </p>
                    <input name="nv_seccode" id="nv_seccode_iavim" class="required" maxlength="{GFX_MAXLENGTH}" />
                </dd>
            </dl>
            <!-- END: captcha -->
            <div id="accept" style="padding-top:20px">
                <p>
                    <strong>{LANG.usage_terms}</strong>
                </p>
                <div id="accept_scoll" style="border:1px solid #ccc;">
                    <div style="margin:10px;">
                        {NV_SITETERMS}
                    </div>
                </div>
            </div>
            <dl class="clearfix" style="padding-top:10px">
                <dt class="fl">
                    <input class="fl required" name="agreecheck" type="checkbox" value="1"{DATA.agreecheck} />
                </dt>
                <dd>
                    <strong>{LANG.accept}</strong>
                </dd>
            </dl>
            <input type="hidden" name="checkss" value="{DATA.checkss}" />
            <input id="submit" type="submit" class="submit" value="{LANG.register}" />
            <!-- BEGIN: lostactivelink -->
            <a href="{LOSTACTIVELINK_SRC}">{LANG.resend_activelink}</a>
            <!-- END: lostactivelink -->
        </div>
    </form>
</div>
<!-- END: main -->
