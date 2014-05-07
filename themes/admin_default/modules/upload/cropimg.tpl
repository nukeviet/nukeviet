<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.crop}</title>
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/cropimages/cropper.min.css" rel="stylesheet" />
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/cropimages/cropper.min.js"></script>
	</head>
	<body>
	<div id="infoimage">
        <img class="cropper" src="{NV_BASE_SITEURL}{IMG_PATH}/{IMG_FILE}?{IMG_MTIME}" />
        <hr />
		X:
		<input type="text" id="dataX1" name="dataX1" value="" style="width:30px;" readonly="readonly"/>
		Y:
		<input type="text" id="dataY1" name="dataY1" value="" style="width:30px;" readonly="readonly"/>
		W:
		<input type="text" id="dataX2" name="dataX2" value="" style="width:30px;" readonly="readonly"/>
		H:
		<input type="text" id="dataY2" name="dataY2" value="" style="width:30px;" readonly="readonly"/>
		<input type="button" name="save" value="{LANG.addlogosave}" onclick="cropimg('{IMG_PATH}', '{IMG_FILE}')"/>
		<br />         
        <script>
            $("#infoimage").css("width", $(".cropper").width());
            $("#infoimage").css("height", $(".cropper").height());
            function cropimg(path, file) {
				$.post('{NV_OP_URL}', 'path=' + path + '&file=' + file + '&x=' + $('#dataX1').val() + '&y=' + $('#dataY1').val() + '&w=' + $('#dataX2').val() + '&h=' + $('#dataY2').val(), function(theResponse) {
					var r_split = theResponse.split("#");
					if (r_split[0] != 'OK') {
						alert(r_split[1]);
					} else {
						opener.location.reload();
						window.close();
					}
				});
			}
            $(function() {
                var $image = $(".cropper"),
                    $dataX1 = $("#dataX1"),
                    $dataY1 = $("#dataY1"),
                    $dataX2 = $("#dataX2"),
                    $dataY2 = $("#dataY2"),
                    $dataHeight = $("#dataHeight"),
                    $dataWidth = $("#dataWidth");
                $image.cropper({
                    aspectRatio: 16 / 9,
                    preview: ".extra-preview",
                    done: function(data) {
                        $dataX1.val(data.x1);
                        $dataY1.val(data.y1);
                        $dataX2.val(data.x2);
                        $dataY2.val(data.y2);
                        $dataHeight.val(data.height);
                        $dataWidth.val(data.width);
                    }
                });

                $("#enable").click(function() {
                    $image.cropper("enable");
                });

                $("#disable").click(function() {
                    $image.cropper("disable");
                });

                $("#free-ratio").click(function() {
                    $image.cropper("setAspectRatio", "auto");
                });

                $("#get-data").click(function() {
                    var data = $image.cropper("getData"),
                        val = "";

                    try {
                        val = JSON.stringify(data);
                    } catch (e) {
                        console.log(data);
                    }

                    $("#get-data-input").val(val);
                });

                var $setDataX1 = $("#set-data-x1"),
                    $setDataY1 = $("#set-data-y1"),
                    $setDataWidth = $("#set-data-width"),
                    $setDataHeight = $("#set-data-height"),
                    $setDataX2 = $("#set-data-x2"),
                    $setDataY2 = $("#set-data-y2");

                $("#set-data").click(function() {
                    var data = {
                        x1: $setDataX1.val(),
                        y1: $setDataY1.val(),
                        width: $setDataWidth.val(),
                        height: $setDataHeight.val(),
                        x2: $setDataX2.val(),
                        y2: $setDataY2.val()
                    }

                    $image.cropper("setData", data);
                });

                $("#set-aspect-ratio").click(function() {
                    var val = $("#set-aspect-ratio-input").val(),
                        aspectRatio = parseInt(val, 10);

                    $image.cropper("setAspectRatio", aspectRatio);
                });

                $("#get-img-info").click(function() {
                    var data = $image.cropper("getImgInfo"),
                        val = "";

                    try {
                        val = JSON.stringify(data);
                    } catch (e) {
                        console.log(data);
                    }

                    $("#get-img-info-input").val(val);
                });
            });
        </script>
		</div>
	</body>
</html>
<!-- END: main -->