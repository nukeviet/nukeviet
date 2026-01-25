        <!-- BEGIN: lt_ie9 --><p class="chromeframe">{LANG.chromeframe}</p><!-- END: lt_ie9 -->
        <!-- BEGIN: cookie_notice --><div class="cookie-notice"><div><button data-toggle="cookie_notice_hide">&times;</button>{COOKIE_NOTICE}</div></div><!-- END: cookie_notice -->
        <div id="openidResult" class="nv-alert" style="display:none"></div>
        <div id="openidBt" data-result="" data-redirect=""></div>
        <!-- BEGIN: crossdomain_listener -->
        <script type="text/javascript">
        function nvgSSOReciver(event) {
            if (event.origin !== '{SSO_REGISTER_ORIGIN}') {
                return false;
            }
            if (event.data == 'nv.reload') {
                location.reload();
            }
        }
        window.addEventListener('message', nvgSSOReciver, false);
        </script>
        <!-- END: crossdomain_listener -->
        <script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
    </body>
</html>
