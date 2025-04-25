    {* Thao tác với tệp này cần chú ý nó được gọi ở cả theme_login.php nên cần kiểm soát các biến cùng nhau *}
    [THEME_ERROR_INFO]
    {if $IS_IE}
    <div class="nv-offcanvas text-bg-warning p-3 show">
        {$LANG->getGlobal('chromeframe')}
    </div>
    {/if}
    <script type="text/javascript" src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_THEME}/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_THEME}/js/nv.core.js"></script>
    {if not empty($GCONFIG.notification_active) and !(not empty($MODULE_NAME) and $MODULE_NAME eq 'siteinfo' and $OP eq 'notification')}
    <script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.timeago.js"></script>
    <script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.timeago-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
    <script src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_THEME}/js/nv.notification.js"></script>
    {/if}
</body>
</html>
