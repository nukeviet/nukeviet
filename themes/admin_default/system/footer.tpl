<!-- SiteModal Required!!! -->
<div id="sitemodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">&nbsp;</h3>
            </div>
            <div class="modal-body">
                    <em class="fa fa-spinner fa-spin">&nbsp;</em>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/main.js"></script>
<!-- BEGIN: notification_js -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/notification.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_FILES_DIR}/js/jquery/timeago/jquery.timeago.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_FILES_DIR}/js/jquery/timeago/locales/jquery.timeago.{NV_LANG_DATA}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_FILES_DIR}/js/jquery/jquery.slimscroll.min.js"></script>
<!-- END: notification_js -->

<!-- BEGIN: ckeditor -->
<script type="text/javascript">
	for (var i in CKEDITOR.instances) {
		CKEDITOR.instances[i].on('key', function(e) {
			$(window).bind('beforeunload', function() {
				return '{MSGBEFOREUNLOAD}';
			});
		});
	}
</script>
<!-- END: ckeditor -->
</body>
</html>