<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.rotate}</title>
        <script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript">
			function rotateimg(path, file) {
				$.post('{NV_OP_URL}', 'path=' + path + '&file=' + file + '&direction=' + $('#direction').val(), function(theResponse) {
					var r_split = theResponse.split("#");
					if (r_split[0] != 'OK') {
						alert(r_split[1]);
					} else {
						opener.location.reload();
						window.close();
					}
				});
			}
		</script>
	</head>
	<body>
		<img src="{NV_BASE_SITEURL}{IMG_PATH}/{IMG_FILE}?{IMG_MTIME}" id="watermarked" />
		<hr />
		Direction:
		<input type="text" id="direction" name="direction" value="" maxlength="3" style="width:30px;" />
		<input type="button" name="save" value="{LANG.addlogosave}" onclick="rotateimg('{IMG_PATH}', '{IMG_FILE}')"/>
		<br />
	</body>
</html>
<!-- END: main -->