    <div id="timeoutsess" class="text-bg-warning timeoutsess">
        <div class="p-2 text-center">
            {$LANG->getGlobal('timeoutsess_nouser')}, <a data-toggle="timeoutsesscancel" href="#">{$LANG->getGlobal('timeoutsess_click')}</a>. {$LANG->getGlobal('timeoutsess_timeout')}: <span id="secField"> 60 </span> {$LANG->getGlobal('sec')}
        </div>
    </div>
    <div id="openidResult" data-sso-domain="{$smarty.const.SSO_REGISTER_DOMAIN}" class="nv-alert"></div>
    <div id="openidBt" data-result="" data-redirect=""></div>
    <script src="{$smarty.const.NV_STATIC_URL}themes/{$GCONFIG.module_theme}/js/bootstrap.bundle.min.js"></script>
</body>
</html>
