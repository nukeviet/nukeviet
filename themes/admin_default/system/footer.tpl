<script type="text/javascript">
	$('form').change(function() {
		$(window).bind('beforeunload', function() {
			return '{MSGBEFOREUNLOAD}';
		});
	});
	$('form').submit(function() {
		$(window).unbind();
	});

	for (var i in CKEDITOR.instances) {
		CKEDITOR.instances[i].on('key', function(e) {
			$(window).bind('beforeunload', function() {
				return '{MSGBEFOREUNLOAD}';
			});
		});
	}
</script>
</body>
</html>