<!-- BEGIN: main -->
<!-- BEGIN: header -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Management Upload File</title>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
</head>
<style type="text/css">
    body{
        font-family:Arial;
        font-size:12px; 
		margin:0;
		position:fixed;
		height:100%;
		width:100%;
		padding:0;
		background:#FFFFFF;
    }
</style>
<body>
<!-- END: header -->

<style type="text/css">
	.content{
			font-family:Arial;
			font-size:12px; 
			position:relative;
			background:#FFFFFF;
			height:100%;
			width:100%;
	}
	.error {
		font-size:12px;
		color:#FF0000;
		font-weight:bold;
		padding:2px;
	}
	.filetype {
		background:#EAEAEA;
		padding:2px;
	}
</style>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.all.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/jquery/jquery.treeview.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.treeview.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/contextmenu/jquery.contextmenu.r2.js"></script>
<div class="content">
    <table height="100%" width="100%">
        <tbody>
                <tr>
                    <td valign="top" width="200" bgcolor="#EAEAEA">
                    	<div class="filetype">
                            <select name="imgtype" id="imgtype">
                                <option value="file" {sfile}>{LANG.type_file}</option>
                                <option value="image" {simage}>{LANG.type_image}</option>
                                <option value="flash" {sflash}>{LANG.type_flash}</option>
                            </select>
                        </div>
                    	<div name="imgfolder" id="imgfolder" size="25" style="width:200px;height:320px;overflow:auto;cursor:pointer; background:#FFFFFF; margin:1px">&nbsp;</div>
                        <!-- BEGIN: error -->
                        <div class="error">
                            {error}
                        </div>
                        <!-- END: error -->
                    </td>
                    <td valign="top" bgcolor="#FFFFFF">
                        <div id="imglist">
                        &nbsp;
                        </div>
                        <div class="filetype">	
                            <form enctype="multipart/form-data" action="" name="uploadimg" id="uploadimg" method="post">
                                <input type="hidden" name="path" value="{currentpath}"/>
                                File : 
                                <input type="file" name="fileupload"/> {LANG.upload_otherurl}
                                <input type="text" name="imgurl"/> 
                                <input type="submit" value="Upload" name="confirm"/>
                            </form>
                        </div>
                    </td>
                </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
$(function(){
	$("#imgfolder").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=folderlist&path={path}&currentpath={currentpath}");
	$("#imglist").html("<iframe src='{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path={currentpath}&type={type}&imgfile={selectedfile}' width='100%' height='360px'></iframe>");
	$("select[name=imgtype]").change(function(){
		var folder = $("span#foldervalue").attr("title");
		var type = $(this).val();
		$("input[name=path]").val(folder);
		$("#imglist").html("<iframe src='{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path="+folder+"&type="+type+"' width='100%' height='355px'></iframe>");		
	});
});
</script>
<input type="hidden" id="posthidden" value=""/>  
<div id="renamefolder" style="display:none" title="{LANG.renamefolder}">{LANG.rename_newname}<input type="text" name="foldername"/></div>
<div id="createfolder" style="display:none" title="{LANG.createfolder}">{LANG.rename_newname}<input type="text" name="createfoldername"/></div>
<script type="text/javascript">
	function insertvaluetofield(){
		var value = $("#posthidden").val();
		var funcNum = '{funnum}';
		if (funcNum > 0){
			window.opener.CKEDITOR.tools.callFunction(funcNum, value,"");
		}
		else{
			$("#{area}",opener.document).val(value);
		} 
	}
	$("div#createfolder").dialog({
		autoOpen: false,
		width: 250,
		height: 160,
		modal: true,
		position: "center",
		buttons: {
			Ok: function() {
				var foldervalue = $("span#foldervalue").attr("title");
				var newname = $("input[name=createfoldername]").val();
				if (newname==""){
					alert("LANG.rename_nonamefolder}");
					$("input[name=foldername]").focus();
					return false;
				}
				$.ajax({
				   type: "POST",
				   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=createfolder",
				   data: "path="+foldervalue+"&newname="+newname,
				   success: function(data){
						$("div#imglist").html("<iframe src='{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path={currentpath}&type={type}' width='100%' height='360px'></iframe>");
						$("#imgfolder").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=folderlist&path={path}&currentpath="+data);
				   }
				 });
				$(this).dialog("close");
			}
		}
	});
	$("div#renamefolder").dialog({
		autoOpen: false,
		width: 250,
		height: 160,
		modal: true,
		position: "center",
		buttons: {
			Ok: function() {
				var foldervalue = $("span#foldervalue").attr("title");
				var newname = $("input[name=foldername]").val();
				if (newname=="" || newname==foldervalue){
					alert("' . $lang_module['rename_nonamefolder'] . '");
					$("input[name=foldername]").focus();
					return false;
				}
				$.ajax({
				   type: "POST",
				   url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=renamefolder",
				   data: "path="+foldervalue+"&newname="+newname,
				   success: function(data){
						$("div#imglist").html("<iframe src='{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=imglist&path="+newname+"'  width='100%' height='360px'></iframe>");
						$("#imgfolder").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=folderlist&currentpath="+data);
				   }
				 });
				$(this).dialog("close");
			}
		}
	});
</script>
<img id="image" src="" name="{area}" title="" style="display:none"/>
<span style="display:none" id="foldervalue" title="{currentpath}"></span>
<div style="display:none" id="folder-menu">
    <ul>
        <!-- BEGIN: allow_create_subdirectories -->
        <li id="createfolder"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/copy.png"/>{LANG.createfolder}</li>
        <!-- END: allow_create_subdirectories -->
        <!-- BEGIN: allow_modify_subdirectories -->
		<li id="renamefolder"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/rename.png"/>{LANG.renamefolder}</li>
        <li id="deletefolder"><img src="{NV_BASE_SITEURL}js/contextmenu/icons/delete.png"/>{LANG.deletefolder}</li>
        <!-- END: allow_modify_subdirectories -->
    </ul>
</div>
<!-- BEGIN: footer -->
</body>
</html>
<!-- END: footer -->
<!--  END: main  -->