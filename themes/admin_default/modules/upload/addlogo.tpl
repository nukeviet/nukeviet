<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.addlogo}</title>
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.resizable.css" rel="stylesheet" />
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.draggable.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.resizable.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.watermarker.js"></script>
		<style type="text/css">
			div.watermark {
				border: 1px dashed #000;
			}

			img.watermark:hover {
				cursor: move;
			}
		</style>
		<script type="text/javascript">
			$().ready(function() {
				$('#watermarked').Watermarker({
					watermark_img : '{LOGOSITE.p}',
					x : '{LOGOSITE.x}',
					y : '{LOGOSITE.y}',
					w : '{LOGOSITE.w}',
					h : '{LOGOSITE.h}',
					opacity : 1,
					position : 'bottomright',
					onChange : showCoords
				});
				$(window).scrollTop(9999);
				$(window).scrollLeft(9999);
			});

			function showCoords(c) {
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function addlogo(path, file) {
				$.post('{NV_OP_URL}', 'path=' + path + '&file=' + file + '&x=' + $('#x').val() + '&y=' + $('#y').val() + '&w=' + $('#w').val() + '&h=' + $('#h').val(), function(theResponse) {
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
		X:
		<input type="text" id="x" name="x" value="" style="width:30px;" readonly="readonly"/>
		Y:
		<input type="text" id="y" name="y" value="" style="width:30px;" readonly="readonly"/>
		W:
		<input type="text" id="w" name="w" value="" style="width:30px;" readonly="readonly"/>
		H:
		<input type="text" id="h" name="h" value="" style="width:30px;" readonly="readonly"/>
		<input type="button" name="save" value="{LANG.addlogosave}" onclick="addlogo('{IMG_PATH}', '{IMG_FILE}')"/>
		<br />
	</body>
</html>
<!-- END: main -->