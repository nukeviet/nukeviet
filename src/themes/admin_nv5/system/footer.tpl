    <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.core.js"></script>
    <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.main.js"></script>
    {* Gọi các thành phần js khi kích hoạt hệ thống thông báo *}
    {if $NOTIFICATION_ACTIVE}
    <script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery/jquery.timeago.js"></script>
    <script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/jquery.timeago-{$NV_LANG_DATA}.js"></script>
    <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.notification.js"></script>
    {/if}
</html>
