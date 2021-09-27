        <!-- Captcha-Modal Required!!! -->
        <div id="modal-img-captcha" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="modal-title">{LANG.securitycode1}</div>
                    </div>
                    <div class="modal-body text-center">
                        <div class="margin-bottom">
                            <img class="captchaImg mr-1" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" width="{NV_GFX_WIDTH}" height="{NV_GFX_HEIGHT}" alt="" title="" /><span class="pointer" data-toggle="change_captcha" data-obj="#modal-captcha-value" title="{LANG.captcharefresh}"><em class="fa fa-refresh"></em></span>
                        </div>
                        <div class="margin-bottom">
                            <div>
                                <p>{LANG.securitycode}</p>
                                <p><input type="text" id="modal-captcha-value" value="" class="form-control display-inline-block required" maxlength="{NV_GFX_NUM}" style="width:200px" data-toggle="enterToEvent" data-obj="#modal-captcha-button" data-obj-event="click"/></p>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <p><button type="button" id="modal-captcha-button" class="btn btn-primary">{LANG.confirm}</button></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: lt_ie9 --><p class="chromeframe">{LANG.chromeframe}</p><!-- END: lt_ie9 -->
        <!-- BEGIN: cookie_notice --><div class="cookie-notice"><div><button data-toggle="cookie_notice_hide">&times;</button>{COOKIE_NOTICE}</div></div><!-- END: cookie_notice -->
        <div id="timeoutsess" class="chromeframe">
            {LANG.timeoutsess_nouser}, <a data-toggle="timeoutsesscancel" href="#">{LANG.timeoutsess_click}</a>. {LANG.timeoutsess_timeout}: <span id="secField"> 60 </span> {LANG.sec}
        </div>
        <div id="openidResult" class="nv-alert" style="display:none"></div>
        <div id="openidBt" data-result="" data-redirect=""></div>
        <!-- BEGIN: crossdomain_listener -->
        <script type="text/javascript">
        function nvgSSOReciver(event) {
            if (event.origin !== '{SSO_REGISTER_ORIGIN}') {
                return false;
            }
            if (
                event.data !== null && typeof event.data == 'object' && event.data.code == 'oauthback' &&
                typeof event.data.redirect != 'undefined' && typeof event.data.status != 'undefined' && typeof event.data.mess != 'undefined'
            ) {
                $('#openidResult').data('redirect', event.data.redirect);
                $('#openidResult').data('result', event.data.status);
                $('#openidResult').html(event.data.mess + (event.data.status == 'success' ? ' <span class="load-bar"></span>' : ''));
                $('#openidResult').addClass('nv-info ' + event.data.status);
                $('#openidBt').trigger('click');
            } else if (event.data == 'nv.reload') {
                location.reload();
            }
        }
        window.addEventListener('message', nvgSSOReciver, false);
        </script>
        <!-- END: crossdomain_listener -->
        <script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
    </body>
</html>
