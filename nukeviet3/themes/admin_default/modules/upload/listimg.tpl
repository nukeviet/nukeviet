<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title></title>	
    <link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}js/ui/jquery.ui.all.css" />
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/contextmenu/jquery.contextmenu.r2.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.scrollTo.js"></script>
</head>
<body>
	<style type="text/css">
    body{
        font-family:Arial;
        font-size:12px; 
        background:#FFFFFF;
		margin:0;
		padding:0;
    }
    img.previewimg { 
		max-width:100px; 
		max-height:60px;
		width: expression(this.width > 90 ? 90: true);
	    height: expression(this.height > 60 ? 60: true); 
		border:2px solid #F0F0F0;
		background:#FFFFFF;
	}
    .imgcontent { 
        float:left; 
        margin-right:2px; 
        text-align:center;
        margin-bottom:2px;
        padding:5px;
        background:#F5F5F5;
        cursor:pointer; 
        font-size:11px;
        line-height:16px;
        height:110px;
        width:110px;
    }
	.clickimg:hover img{ border:2px solid red}
	</style>
	<!-- BEGIN: loopimg -->
    <div class="imgcontent" {imglist.selid}>
        <a href="javascript:;" class="clickimg"><img class="previewimg" title="{imglist.name}" src="{imglist.src}" style="padding:2px {imglist.sel}" /></a>
        <div>{imglist.name0} <br />{imglist.filesize}</div>
    </div>
    <!-- END: loopimg -->
    <div id="imgpreview" style="overflow:auto" title="{LANG.preview}"></div>
    <div id="createimg" style="text-align:center; display:none" title="{LANG.upload_size}">
        {LANG.upload_width}: <input name="width" style="width:60px" type="text"/>
        {LANG.upload_height}: <input type="text" style="width:60px" name="height"/>
        <input type="button" id="idcreate" value="OK">
    </div>
    <div id="renameimg" style="display:none" title="{LANG.rename}">
        {LANG.rename_newname}<input type="text" name="imagename"/><input type="button" id="idrename" value="OK">
    </div>
    <div id="movefolder" style="text-align:center;display:none" title="{LANG.movefolder}">
        {LANG.select_folder} 
        <select name="selectfolder" id="selectfolder">
            <!-- BEGIN: floop -->
            <option value="{fol.name}" {fol.select}>{fol.name}</option>
            <!-- END: floop -->
        </select>
        <input type="button" id="idmove" value="OK">
    </div>
    <input type="hidden" id="idcurent" value="" />
    <div id="preview" style="display:none" title="{LANG.preview}"></div>
    <div style="display:none" id="vs-context-menu">
        <ul>
            <li id="select"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/select.png"/>{LANG.select}</li>
            <li id="view"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/view.png"/>{LANG.preview}</li>
            <li id="download"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/download.png"/>{LANG.download}</li>
            <!-- BEGIN: allow_modify_files -->
			<li id="create"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/copy.png"/>{LANG.upload_createimage}</li>
    		<li id="cut"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/cut.png"/>{LANG.move}</li>
            <li id="rename"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/rename.png"/>{LANG.rename}</li>
            <li id="delete"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/delete.png"/>{LANG.upload_delfile}</li>
            <!-- END: allow_modify_files -->
        </ul>
    </div>
    <script type="text/javascript">
		<!-- BEGIN: slectfile -->
		$.scrollTo("#imgselected", 80);
		<!-- END: slectfile -->
		$("img.previewimg").contextMenu("vs-context-menu", {
          menuStyle: {
            border: "2px solid #000",
            width: "150px"
          },
          itemStyle: {
            fontFamily : "verdana",
            backgroundColor : "#666",
            color: "white",
            border: "none",
            padding: "1px",
            fontSize: "12px"
          },
          itemHoverStyle: {
            color: "#fff",
            backgroundColor: "#0f0",
            border: "none"
          },
          bindings: {
            "cut": function(t) {
              movefolder();
            },      
            "view": function(t) {
              previewimage();
            },      
            "rename": function(t) {
              renameimage();
            },      
            "select": function(t) {
              selectimage();
            },
            "create": function(t) {
              createimage();
            },
            "download": function(t) {
              downloadimage();
            },
            "delete": function(t) {
              deleteimage();
            }
          }
        });
    	$("img.previewimg").dblclick(function(){
            var folder = "{folder}";
            var imgfile =  $(this).attr("title");
            var area = $("#image",parent.document).attr("name");
            $("#posthidden",parent.document).val("{NV_BASE_SITEURL}"+folder+"/"+imgfile);
            window.parent.insertvaluetofield(); 
            top.window.close();
        });
		$("img.previewimg").mouseup(function(){
            var imgsrc = $(this).attr("src");
            var imgtitle = $(this).attr("title");
			$("#idcurent").val(imgtitle);
            var ext = imgtitle.slice(-3);
            if (ext=="jpg"||ext=="png"||ext=="gif"||ext=="bmp"){
                $("li#create").remove();
                $("li#info").remove();
                $("li#view").remove();
				<!-- BEGIN: allow_modify_files1 -->
				$("#vs-context-menu>ul").append("<li id=\"create\"><img src=\"{NV_BASE_SITEURL}js/contextmenu/icons/copy.png\"/>{LANG.upload_createimage}</li>");
				<!-- END: allow_modify_files1 -->
				$("#vs-context-menu>ul").append("<li id=\"view\"><img src=\"{NV_BASE_SITEURL}js/contextmenu/icons/view.png\"/>{LANG.preview}</li>");
            } else {
                $("li#create").remove();
                $("li#view").remove();
                $("li#info").remove();
            }
        });
		// for change height value of resize image width
		$("input[name=width]").keyup(function(){
			var newwidth = $("input[name=width]").val();
			$("#image",parent.document).attr("width",newwidth);
			$("input[name=height]").val($("#image",parent.document).height());
		});
		function previewimage(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			var ext = imgfile.slice(-3);
			if (ext=="jpg"||ext=="png"||ext=="gif"||ext=="bmp"){
				$("div#imgpreview").html("<img src='{NV_BASE_SITEURL}"+folder+"/"+imgfile+"'/>");
				$("div#imgpreview").dialog("open");
			} else {
				alert("{LANG.nopreview}");
			}
		}
		$("div#imgpreview").dialog({
			autoOpen: false,
			width: 400,
			height: 350,
			modal: true,
			position: "center"
		});
		function downloadimage(){
       		var folder = '{folder}';
			var imgfile = $("#idcurent").val();
       		window.location="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=dlimg&path="+folder+"&img="+imgfile+"";
    	}
		function deleteimage(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			if (confirm("{LANG.upload_delimg_confirm}"+imgfile+"")){
				$.ajax({
				   type: "POST",
				   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=delimg",
				   data: "path="+folder+"&img="+imgfile,
				   success: function(data){
						$("div#imglist",parent.document).html("<iframe src=\'{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
				   }
				});		
			}
		}
		$("div#renameimg").dialog({
			autoOpen: false,
			width: 300,
			height: 120,
			modal: true,
			position: "center"
		});
		function renameimage(){
			$("div#renameimg").dialog("open");
		}
		$("#idrename").click(function(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			var newname = $("input[name=imagename]").val();
			if (newname==""){
				alert("{LANG.rename_noname}");
				$("input[name=imagename]").focus();
				return false;
			}
			$.ajax({
			   type: "POST",
			   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=renameimg",
			   data: "path="+folder+"&img="+imgfile+"&name="+newname,
			   success: function(data){
					$("div#imglist",parent.document).html("<iframe src=\'{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&imgfile="+data+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
					$("div#renameimg").dialog("close");
			   }
			 });
		});
		/////////////////////////////////////////////////////////
		$("div#movefolder").dialog({
			autoOpen: false,
			width: 450,
			height: 200,
			modal: true,
			position: "center",
			resizable: true
		});
		function movefolder(){
			$("div#movefolder").dialog("open");
		}
		$("#idmove").click(function(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			var newfolder = $("select[name=selectfolder]").val(); 
			if (folder!=newfolder){
				$.ajax({
				   type: "POST",
				   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=moveimg",
				   data: "path="+folder+"&img="+imgfile+"&folder="+newfolder,
				   success: function(data){
						$("div#imglist",parent.document).html("<iframe src=\'{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&imgfile="+imgfile+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
				   }
				 });
			}
			$("div#movefolder").dialog("close");
		});
		/////////////////////////////////////////
		function createimage(){
			$("div#createimg").dialog("open");
		}
		////////////////////////////////////////
		$("div#createimg").dialog({
			autoOpen: false,
			width: 250,
			height: 200,
			modal: true,
			position: "center"
		});
		$("#idcreate").click(function(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			var img = $("#image",parent.document);
			var newheight = $("input[name=height]").val();	
			var newwidth = $("input[name=width]").val();
			$.ajax({
			   type: "POST",
			   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=createimg",
			   data: "path="+folder+"&img="+imgfile+"&width="+newwidth+"&height="+newheight,
			   success: function(data){
					$("div#imglist",parent.document).html("<iframe src='{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&imgfile="+data+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
			   }
			});
			$("div#createimg").dialog("close");
		});
		/////////////////////////////
		function selectimage(){
			var folder = '{folder}';
			var imgfile = $("#idcurent").val();
			var area = $("#image",parent.document).attr("name");
			$("#posthidden",parent.document).val("{NV_BASE_SITEURL}"+folder+"/"+imgfile);
			window.parent.insertvaluetofield(); 
			top.window.close();
		}
    </script>
</body>
</html>
<!-- END: main -->