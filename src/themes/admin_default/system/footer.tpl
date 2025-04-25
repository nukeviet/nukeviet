<!-- BEGIN: lt_ie9 --><p class="chromeframe">{LANG.chromeframe}</p><!-- END: lt_ie9 -->

<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/bootstrap.min.js"></script>
<!-- BEGIN: notification_js -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/notification.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery/jquery.timeago.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.timeago-{NV_LANG_DATA}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery/jquery.slimscroll.min.js"></script>
<!-- END: notification_js -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/main.js"></script>

<!-- BEGIN: ckeditor -->
<script type="text/javascript">
if (typeof CKEDITOR != "undefined") {
    for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].on('key', function(e) {
            $(window).bind('beforeunload', function() {
                return '{LANG.msgbeforeunload}';
            });
        });
    }
}
</script>
<!-- END: ckeditor -->
</body>
</html>
