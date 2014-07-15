<!-- BEGIN: main -->
<script type="text/javascript">
$(document).ready(function() {
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
<div class="panel-body">
	<img src="{NV_BASE_SITEURL}{IMG_PATH}/{IMG_FILE}?{IMG_MTIME}" id="watermarked" />
	<hr />
	<div class="form-inline">
		X: <input type="text" id="x" name="x" value="" class="form-control w50" readonly="readonly"/>
		Y: <input type="text" id="y" name="y" value="" class="form-control w50" readonly="readonly"/>
		W: <input type="text" id="w" name="w" value="" class="form-control w50" readonly="readonly"/>
		H: <input type="text" id="h" name="h" value="" class="form-control w50" readonly="readonly"/>
		<input type="button" name="save" value="{LANG.addlogosave}" onclick="addlogo('{IMG_PATH}', '{IMG_FILE}')" class="btn btn-primary"/>
	</div>
</div>
<!-- END: main -->