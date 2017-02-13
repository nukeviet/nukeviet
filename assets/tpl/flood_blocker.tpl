<!-- BEGIN: main -->
<!DOCTYPE html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{PAGE_TITLE}</title>
		<style type="text/css">
			.floodblocker {
				font: normal 14px/25px Arial, Helvetica, sans-serif;
				margin: 50px;
				padding: 50px;
				text-align: center
			}

			.floodblocker span {
				font: bold 14px/25px Arial, Helvetica, sans-serif;
			}

			#secField {
				color: #FF5B0D;
				font: bold 18px/25px Arial, Helvetica, sans-serif
			}
		</style>
	</head>
	<body>
		<div class="floodblocker">
			<img alt="{PAGE_TITLE}" src="{IMG_SRC}" width="{IMG_WIDTH}" height="{IMG_HEIGHT}" />
			<br/>
			{FLOOD_BLOCKER_INFO1}.
			<br/>
			<span>{FLOOD_BLOCKER_INFO2} <span id="secField">{FLOOD_BLOCKER_TIME}</span> {FLOOD_BLOCKER_INFO3}...</span>
		</div>
		<script type="text/javascript">
			var Timeout = '{FLOOD_BLOCKER_TIME}';
			var timeBegin = new Date();
			var msBegin = timeBegin.getTime();
			function showSeconds() {
				var timeCurrent = new Date();
				var msCurrent = timeCurrent.getTime();
				var ms = Math.round((msCurrent - msBegin) / 1000);
				document.getElementById('secField').innerHTML = Timeout - ms;
				if (Timeout <= ms)
					location.reload();
			}

			timerID = setInterval("showSeconds()", 1000);
		</script>
	</body>
</html>
<!-- END: main -->