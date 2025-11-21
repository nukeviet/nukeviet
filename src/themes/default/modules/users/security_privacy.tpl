<!-- BEGIN: main -->
<div class="centered" id="security-privacy-page" data-checkss="{CHECKSS}" data-auto-toast="{DATA.auto_toast}" data-next-offset="{NEXT_OFFSET}" data-page="{DATA.page}">
    <div class="sm-container-box">
        <h1>{LANG.security_privacy}</h1>
        <p class="margin-bottom-lg">{LANG.security_privacy_des}.</p>
        <div class="box-security box-shadow-lg">
            <h2>{LANG.login_session}</h2>
            <!-- BEGIN: no_logins -->
            <div class="alert alert-danger">{LANG.login_session_none}.</div>
            <!-- END: no_logins -->
            <!-- BEGIN: has_logins -->
            <p class="margin-bottom-lg">{LANG.login_session_explain}.</p>
            <div data-toggle="logins-ctn">
                <!-- BEGIN: ctn_loop -->
                <!-- BEGIN: loop -->
                <div class="border-box<!-- BEGIN: current1 --> primary<!-- END: current1 --> margin-bottom">
                    <div class="usr-flex usr-align-center usr-gap-2">
                        <div class="usr-flex usr-align-center">
                            <div class="usr-login-platform">
                                <i class="fa {LOGIN.icon_os}" aria-hidden="true"></i>
                            </div>
                            <div class="usr-login-platform browser">
                                <i class="fa {LOGIN.icon_browser}" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div>
                            <div>
                                <strong>{LOGIN.os_name} - {LOGIN.browser_name}</strong>
                                <!-- BEGIN: current2 --><span class="label label-primary">{LANG.login_session_current}</span><!-- END: current2 -->
                                <!-- BEGIN: is_admin --><span class="label label-success">{LANG.login_session_admin}</span><!-- END: is_admin -->
                            </div>
                            <div class="text-muted">{LOGIN.ip} · {LANG.login}: {LOGIN.current_login_text}</div>
                        </div>
                        <!-- BEGIN: logout -->
                        <div class="usr-ml-auto">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="login-remove" data-idlogin="{LOGIN.id}">{GLANG.logout}</button>
                        </div>
                        <!-- END: logout -->
                    </div>
                </div>
                <!-- END: loop -->
                <!-- END: ctn_loop -->
            </div>
            <!-- BEGIN: more -->
            <div class="text-center margin-top-lg" data-toggle="login-more-ctn">
                <button type="button" class="btn btn-primary" data-toggle="login-more">{GLANG.view_more}</button>
            </div>
            <!-- END: more -->
            <!-- BEGIN: logout_all -->
            <hr class="usr-hr-space">
            <h2 class="margin-bottom-lg">{LANG.security_actions}</h2>
            <div class="border-box bg-gray-light">
                <div class="usr-flex usr-justify-between usr-align-center usr-gap-2">
                    <div>
                        <div><strong>{LANG.security_actions_logout_all}</strong></div>
                        <div>{LANG.security_actions_logout_all1}.</div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger" data-toggle="login-remove-all">{LANG.security_actions_logout_all2}</button>
                    </div>
                </div>
            </div>
            <!-- END: logout_all -->
            <!-- END: has_logins -->
            <hr class="usr-hr-space">
            <div class="text-center">
                <a class="danger small" href="{DATA.link_delete}">{LANG.secacts_delaccount}</a>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
