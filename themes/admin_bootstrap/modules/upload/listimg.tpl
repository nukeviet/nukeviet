<!-- BEGIN: main -->
<!-- BEGIN: loopimg -->
<div class="imgcontent{IMG.sel}" title="{IMG.title}">
	<div style="width:100px;height:86px;display:table-cell; vertical-align:middle;">
		<img class="previewimg" alt="{IMG.alt}" title="{IMG.title}" name="{IMG.data}" src="{IMG.src}" width="{IMG.srcwidth}" height="{IMG.srcheight}" />
	</div>
	<div class="imgInfo">
		{IMG.name}
		<br />
		{IMG.size}
	</div>
</div>
<!-- END: loopimg -->
<div class="clearfix"></div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<div style="height:100px"></div>

<script type="text/javascript">
	//<![CDATA[
	$("img.previewimg").lazyload({
		placeholder : "{NV_BASE_SITEURL}images/grey.gif",
		container : $(".filebrowse")
	});
	$(".imgcontent").bind("mouseup", function() {
		fileMouseup(this)
	});

	$(".imgcontent").dblclick(function() {
		insertvaluetofield()
	});

	$(".imgcontent").contextMenu("contextMenu", {
		menuStyle : {
			width : "120px"
		},
		bindings : {
			select : function() {
				insertvaluetofield()
			},
			download : function() {
				download()
			},
			filepreview : function() {
				preview()
			},
			fileaddlogo : function() {
				addlogo()
			},
			create : function() {
				create()
			},
			move : function() {
				move()
			},
			rename : function() {
				filerename()
			},
			filedelete : function() {
				filedelete()
			},
            cropfile : function() {
				cropfile()
			},
			rotatefile : function() {
				rotatefile()
			}
		}
	});
	//]]>
</script>

<!-- BEGIN: imgsel -->
<script type="text/javascript">
	$(".imgcontent.imgsel").attr('id', 'nv_imgsel_{NV_CURRENTTIME}');
	window.location.href = "#nv_imgsel_{NV_CURRENTTIME}";
</script>
<!-- END: imgsel -->
<!-- END: main -->