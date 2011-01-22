<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="X-UA-Compatible"content="IE=EmulateIE8" />
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
        <a href="javascript:;" class="clickimg"><img class="previewimg" title="{imglist.title}" name="{imglist.name}" src="{imglist.src}" style="padding:2px {imglist.sel}" /></a>
        <div>{imglist.name0} <br />{imglist.filesize}</div>
    </div>
    <!-- END: loopimg -->
    <div id="imgpreview" style="overflow:auto;" title="{LANG.preview}"></div>
    <div id="createimg" style="text-align:center; display:none" title="{LANG.upload_size}">
        {LANG.upload_width}: <input name="width" style="width:60px" type="text"/>
        {LANG.upload_height}: <input type="text" style="width:60px" name="height"/><br />
        <div id="origsize" style="font-size:10px;"></div>
        <div style="font-size:10px;">{MAXSIZESIZE}</div>
        <input type="button" id="idcreate" value="{LANG.upload_createimage}" />
    </div>
    <div id="renameimg" style="display:none" title="{LANG.rename}">
        {LANG.rename_newname}<input type="text" name="imagename"/><input type="button" id="idrename" value="OK" />
    </div>
    <div id="movefolder" style="text-align:center;display:none" title="{LANG.movefolder}">
        {LANG.select_folder} 
        <select name="selectfolder" id="selectfolder">
            <!-- BEGIN: floop -->
            <option value="{fol.name}" {fol.select}>{fol.name}</option>
            <!-- END: floop -->
        </select>
        <input type="button" id="idmove" value="OK" />
    </div>
    <input type="hidden" id="currentId" value="" />
    <input type="hidden" id="currentWidth" value="" />
    <input type="hidden" id="currentHeight" value="" />
    <input type="hidden" id="currentSize" value="" />
    <div id="preview" style="display:none;" title="{LANG.preview}"></div>
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
//<![CDATA[
<!-- BEGIN: slectfile -->
$.scrollTo("#imgselected", 80);
<!-- END: slectfile -->

function is_numeric(a) {
  return(typeof a === "number" || typeof a === "string") && a !== "" && !isNaN(a)
}

function resize_byWidth(a, b, c) {
  return Math.round(c / a * b)
}
function resize_byHeight(a, b, c) {
  return Math.round(c / b * a)
}

function previewimage() {
  var b = $("#currentId").val(), a = b.slice(-3);
  if(a == "jpg" || a == "png" || a == "gif" || a == "bmp") {
    var c = $("#currentWidth").val(), d = $("#currentHeight").val(), e = c, f = d, g = $("#currentId").val(), h = $("#currentSize").val();
    if(c > 360)
    {
        d = resize_byWidth(c,d,360);
        c = 360;
    }
    if(d > 230)
    {
        c = resize_byHeight(c,d,230);
        d = 230;
    }
    $("div#imgpreview").html("<div style=\"text-align:center\"><img width=\"" + c + "\" height=\"" + d + "\" src=\"{NV_BASE_SITEURL}{folder}/" + b + "\" /></div><div style=\"text-align:center;font-size:11px;margin-top:10px\"><strong>" + g + "</strong><br />{LANG.upload_size}: " + e + "x" + f + "px (" + h + ")</div>");
    $("div#imgpreview").dialog("open");
  }else {
    alert("{LANG.nopreview}")
  }
}

function downloadimage() {
  var a = $("#currentId").val();
  window.location = "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=dlimg&path={folder}&img=" + a + ""
}

function deleteimage() {
  var a = $("#currentId").val();
  confirm("{LANG.upload_delimg_confirm}" + a + "") && $.ajax({type:"POST", url:"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=delimg", data:"path={folder}&img=" + a, success:function() {
    $("div#imglist", parent.document).html("<iframe src=\"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path={folder}\" style=\"width:100%;height:360px;border:none\"></iframe>")
  }})
}

function renameimage() {
  $("div#renameimg").dialog("open")
}

function movefolder() {
  $("div#movefolder").dialog("open")
}

function createimage() {
  $("div#createimg").dialog("open")
}

function selectimage() {
  var a = $("#currentId").val();
  $("#posthidden", parent.document).val("{NV_BASE_SITEURL}{folder}/" + a);
  window.parent.insertvaluetofield() && top.window.close()
}

$("img.previewimg").contextMenu("vs-context-menu", {menuStyle:{border:"2px solid #000", width:"150px"}, itemStyle:{fontFamily:"verdana", backgroundColor:"#666", color:"white", border:"none", padding:"1px", fontSize:"12px"}, itemHoverStyle:{color:"#fff", backgroundColor:"#0f0", border:"none"}, bindings:{cut:function() {
  movefolder()
}, view:function() {
  previewimage()
}, rename:function() {
  renameimage()
}, select:function() {
  selectimage()
}, create:function() {
  var a = $("#currentWidth").val(), b = $("#currentHeight").val();
  $("input[name=width]").val(a);
  $("input[name=height]").val(b);
  $("#origsize").text("{LANG.origSize}: " + a + "x" + b + "px");
  createimage()
}, download:function() {
  downloadimage()
}, "delete":function() {
  deleteimage()
}}});
        
$("img.previewimg").dblclick(function() {
  var a = $(this).attr("title");
  $("#posthidden", parent.document).val("{NV_BASE_SITEURL}{folder}/" + a);
  window.parent.insertvaluetofield() && top.window.close()
});

$("img.previewimg").mouseup(function() {
  $(this).attr("src");
  var a = $(this).attr("title");
  $("#currentId").val(a);
  var b = $(this).attr("name");
  if(b != "") {
    b = b.split("|");
    $("#currentWidth").val(b[0]);
    $("#currentHeight").val(b[1]);
    $("#currentSize").val(b[2])
  }
  a = a.slice(-3);
  if(a == "jpg" || a == "png" || a == "gif" || a == "bmp") {
    $("li#create").remove();
    $("li#info").remove();
    $("li#view").remove();
    $("#vs-context-menu>ul").append('<li id="create"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/copy.png"/>{LANG.upload_createimage}</li>');
    $("#vs-context-menu>ul").append('<li id="view"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/view.png"/>{LANG.preview}</li>')
  }else {
    $("li#create").remove();
    $("li#view").remove();
    $("li#info").remove()
  }
});

$("input[name=width]").keyup(function() {
  var a = $("input[name=width]").val(), b = $("#currentWidth").val(), c = $("#currentHeight").val();
  if(!is_numeric(a) || a > {NV_MAX_WIDTH}) {
    $("input[name=width]").val("");
    $("input[name=height]").val("")
  }else {
    $("input[name=height]").val(resize_byWidth(b, c, a))
  }
});

$("input[name=height]").keyup(function() {
  var a = $("input[name=height]").val();
  if(!is_numeric(a) || a > {NV_MAX_HEIGHT}) {
    $("input[name=width]").val("");
    $("input[name=height]").val("")
  }else {
    var b = $("#currentWidth").val(), c = $("#currentHeight").val();
    $("input[name=width]").val(resize_byHeight(b, c, a))
  }
});

$("div#imgpreview").dialog({autoOpen:false, width:380, height:320, modal:true, position:"center", show:"slide"});

$("div#imgpreview").dblclick(function() {
  $(this).dialog("close");
});

$("div#renameimg").dialog({autoOpen:false, width:300, height:120, modal:true, position:"center"});

$("#idrename").click(function() {
  var b = $("#currentId").val(), a = $("input[name=imagename]").val();
  if(a == "") {
    alert("{LANG.rename_noname}");
    $("input[name=imagename]").focus();
    return false
  }
  $.ajax({type:"POST", url:"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=renameimg", data:"path={folder}&img=" + b + "&name=" + a, success:function(c) {
    $("div#imglist", parent.document).html("<iframe src=\"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&imgfile=" + c + "&path={folder}\" style=\"width:100%;height:360px;border:none\"></iframe>");
    $("div#renameimg").dialog("close")
  }})
});

$("div#movefolder").dialog({autoOpen:false, width:450, height:200, modal:true, position:"center", resizable:true});

$("#idmove").click(function() {
  var a = $("#currentId").val(), b = $("select[name=selectfolder]").val();
  "{folder}" != b && $.ajax({type:"POST", url:"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=moveimg", data:"path={folder}&img=" + a + "&folder=" + b, success:function() {
    $("div#imglist", parent.document).html("<iframe src=\"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path={folder}\" style=\"width:100%;height:360px;border:none\"></iframe>")
  }});
  $("div#movefolder").dialog("close")
});

$("div#createimg").dialog({autoOpen:false, width:250, height:200, modal:true, position:"center"});

$("#idcreate").click(function() {
  var c = $("#currentId").val(), d = $("#currentWidth").val(), e = $("#currentHeight").val(), a = $("input[name=height]").val(), b = $("input[name=width]").val();
  if(b == "" || !is_numeric(b) || b < 10 || b > {NV_MAX_WIDTH} || a == "" || !is_numeric(a) || a < 10 || a > {NV_MAX_HEIGHT}) {
    alert("{ERRORNEWSIZE}");
    $("input[name=width]").val("");
    $("input[name=height]").val("")
  }else {
    if(b == d && a == e) {
      alert("{LANG.sizenotchanged}")
    }else {
      $.ajax({type:"POST", url:"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=createimg", data:"path={folder}&img=" + c + "&width=" + b + "&height=" + a, success:function(f) {
        $("div#imglist", parent.document).html("<iframe src=\"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&imgfile=" + f + "&path={folder}\" style=\"width:100%;height:360px;border:none\"></iframe>")
      }});
      $("div#createimg").dialog("close")
    }
  }
});
//]]>
</script>
</body>
</html>
<!-- END: main -->