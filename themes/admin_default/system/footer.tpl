<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/main.js"></script>
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