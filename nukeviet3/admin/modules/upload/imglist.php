<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
		<title></title>	
		<link type="text/css" rel="stylesheet" href="' . NV_BASE_SITEURL . 'js/ui/jquery.ui.all.css" />
	    <script type="text/javascript" src="' . NV_BASE_SITEURL . 'js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="' . NV_BASE_SITEURL . 'js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="' . NV_BASE_SITEURL . 'js/ui/jquery.ui.dialog.min.js"></script>
		<script type="text/javascript" src="' . NV_BASE_SITEURL . 'js/contextmenu/jquery.contextmenu.r2.js"></script>
		<script type="text/javascript" src="' . NV_BASE_SITEURL . 'js/jquery/jquery.scrollTo.js"></script>
	</head>
	<style type="text/css">
    body{
        	font-family:Arial;
        	font-size:12px; 
        	background:#FFFFFF;
	    }
	    .viewimg { max-width:100px; max-height:60px; border:2px solid #F0F0F0;background:#FFFFFF; }
	    .imgcontent { 
			float:left; 
			margin-right:5px; 
			text-align:center;
			margin-bottom:2px;
			padding:5px;
			background:#F5F5F5;
			cursor:pointer;
			font-size:11px;
			line-height:16px;
			height:100px;
			width:100px;
		}
	</style>
	<body>';
$pathimg = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'get', NV_UPLOADS_DIR ) ), ENT_QUOTES );
if ( ! empty( $pathimg ) )
{
    $type = htmlspecialchars( trim( $nv_Request->get_string( 'type', 'get', 'file' ) ), ENT_QUOTES );
    $selectfile = htmlspecialchars( trim( $nv_Request->get_string( 'imgfile', 'get' ) ), ENT_QUOTES );
    
    $imglist = array();
    if ( ! nv_check_allow_upload_dir( $pathimg ) )
    {
        $pathimg = NV_UPLOADS_DIR;
    }
    $files = @scandir( NV_ROOTDIR . "/" . $pathimg );
    if ( ! empty( $files ) )
    {
        if ( $type == 'image' )
        {
            $filter = "\.(gif|jpg|jpeg|pjpeg|png)";
        }
        if ( $type == 'flash' )
        {
            $filter = "\.(flv|swf|swc)";
        }
        foreach ( $files as $file )
        {
            $full_d = NV_ROOTDIR . '/' . $pathimg . '/' . $file;
            if ( ! in_array( $file, $array_hidefolders ) and ! is_dir( $full_d ) )
            {
                if ( $type != 'file' )
                {
                    if ( preg_match( '/^[a-zA-Z0-9\-\_](.*)' . $filter . '$/', strtolower( $file ) ) )
                    {
                        $imglist[] = $file;
                    }
                }
                else
                {
                    $imglist[] = $file;
                }
            }
        }
    }
    for ( $i = 0; $i < count( $imglist ); $i ++ )
    {
        if ( $selectfile == $imglist[$i] )
        {
            $sel = ';border:2px solid red';
            $selid = 'id="imgselected"';
        }
        else
        {
            $sel = '';
            $selid = '';
        }
        $arr_temp = explode( '.', $imglist[$i] );
        $ext = strtolower( end( $arr_temp ) );
        echo '<div class="imgcontent" ' . $selid . '>';
        $fsize = @filesize( NV_ROOTDIR . '/' . $pathimg . '/' . $imglist[$i] ) ;
        if ( in_array( $ext, $array_images ) )
        {
            if ($fsize > 10000)
            {
        		//tao thumb cho anh qua 10kb
        		$thumb = $imglist[$i]; // ten thumb
        		$paththumb = $pathimg; // duong dan thumb
        		if (file_exists(NV_ROOTDIR . '/' . $pathimg . '/thumb/' . $imglist[$i]))
        		{
        			//kem tra theme
        			$paththumb =  $pathimg . '/thumb';
        		}
        		else {
        			//tao thumb 80x80
        		}
        		/////////////////////////////////////
            	echo '<img class="previewimg viewimg" title="' . $imglist[$i] . '" src="' . NV_BASE_SITEURL . $paththumb . '/' . $thumb . '" style="padding:2px' . $sel . '"/>';
            }
            else 
            {
            	echo '<img class="previewimg viewimg" title="' . $imglist[$i] . '" src="' . NV_BASE_SITEURL . $pathimg . '/' . $imglist[$i] . '" style="padding:2px' . $sel . '"/>';
            }
        }
        elseif ( in_array( $ext, $array_archives ) )
        {
            echo '<img class="previewimg viewimg" title="' . $imglist[$i] . '" src="' . NV_BASE_SITEURL . 'images/zip.gif" style="padding:2px' . $sel . '" width="32" height="32"/>';
        }
        elseif ( in_array( $ext, $array_documents ) )
        {
            echo '<img class="previewimg viewimg" title="' . $imglist[$i] . '" src="' . NV_BASE_SITEURL . 'images/doc.gif" style="padding:2px' . $sel . '" width="32" height="32"/>';
        }
        else
        {
            echo '<img class="previewimg viewimg" title="' . $imglist[$i] . '" src="' . NV_BASE_SITEURL . 'images/file.gif" style="padding:5px' . $sel . '" width="32" height="32"/>';
        }
        $filesize = nv_convertfromBytes( @filesize( NV_ROOTDIR . '/' . $pathimg . '/' . $imglist[$i] ) );
        echo '<div>'.$imglist[$i] .'<br />'. $filesize. '</div>';
        echo '</div>';
    }
    echo '
	<div id="imgpreview" style="overflow:auto" title="' . $lang_module['preview'] . '"></div>
	<div id="createimg" style="text-align:center;display:none" title="' . $lang_module['upload_size'] . '">' . $lang_module['upload_width'] . ':<input name="width" style="width:60px" type="text"/>' . $lang_module['upload_height'] . ':<input type="text" style="width:60px" name="height" disabled=disabled/></div>
	<div id="renameimg" style="display:none" title="' . $lang_module['rename'] . '">
	' . $lang_module['rename_newname'] . '<input type="text" name="imagename"/></div>
	<div id="movefolder" style="text-align:center;display:none" title="' . $lang_module['movefolder'] . '">' . $lang_module['select_folder'] . '
	<select name="selectfolder" id="selectfolder">';
    $listdir = viewdir( NV_UPLOADS_DIR );
    foreach ( $listdir as $folder )
    {
        $sel = ( $folder == $pathimg ) ? ' selected' : '';
        echo '<option value="' . $folder . '" ' . $sel . '>' . $folder . '</option>';
    }
    echo '	</select>';
    echo '</div>
		<div id="preview" style="display:none" title="' . $lang_module['preview'] . '"></div>
		
		<script type="text/javascript">
		$(function(){
		// for change height value of resize image width
		$("input[name=width]").keyup(function(){
			var newwidth = $("input[name=width]").val();
			$("#image",parent.document).attr("width",newwidth);
			$("input[name=height]").val($("#image",parent.document).height());
		});
		
		$("div#createimg").dialog({
			autoOpen: false,
			width: 250,
			height: 200,
			modal: true,
			position: "center",
			buttons: {
				Ok: function() {
					var folder = $("#foldervalue",parent.document).attr("title");
					var imgfile = $("#image",parent.document).attr("title");
					var img = $("#image",parent.document);
					img.removeAttr("width","height");
					var width = img.width();
					var height = img.height();
					var newheight = $("input[name=height]").val();	
					var newwidth = $("input[name=width]").val();
					if (width!=newwidth || height!=newheight){
						$.ajax({
						   type: "POST",
						   url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=createimg",
						   data: "path="+folder+"&img="+imgfile+"&width="+newwidth+"&height="+newheight,
						   success: function(data){
								$("div#imglist",parent.document).html("<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&imgfile="+data+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
						   }
						 });
					 }
					 $("div#createimg").dialog("close");
				}
			}
		});
		$("div#renameimg").dialog({
			autoOpen: false,
			width: 250,
			height: 250,
			modal: true,
			position: "center",
			buttons: {
				Ok: function() {
					var folder = $("#foldervalue",parent.document).attr("title");
					var imgfile = $("#image",parent.document).attr("title");
					var newname = $("input[name=imagename]").val();
					if (newname==""){
						alert("' . $lang_module['rename_noname'] . '");
						$("input[name=imagename]").focus();
						return false;
					}
					$.ajax({
					   type: "POST",
					   url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=renameimg",
					   data: "path="+folder+"&img="+imgfile+"&name="+newname,
					   success: function(data){
							$("div#imglist",parent.document).html("<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&imgfile="+data+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
							$("div#renameimg").dialog("close");
					   }
					 });
				}
			}
		});
		$("div#imgpreview").dialog({
			autoOpen: false,
			width: 400,
			height: 350,
			modal: true,
			position: "center",
			resizable: true
		});
		$("div#movefolder").dialog({
			autoOpen: false,
			width: 450,
			height: 200,
			modal: true,
			position: "center",
			resizable: true,
			buttons: {
				Ok: function() {
					var folder = $("#foldervalue",parent.document).attr("title");
					var imgfile = $("#image",parent.document).attr("title");
					var newfolder = $("select[name=selectfolder]").val();
					if (folder!=newfolder){
						$.ajax({
						   type: "POST",
						   url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=moveimg",
						   data: "path="+folder+"&img="+imgfile+"&folder="+newfolder,
						   success: function(data){
								$("div#imglist",parent.document).html("<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&imgfile="+imgfile+"&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
						   }
						 });
					}
					$("div#movefolder").dialog("close");
				}
			}
		});
		});
		</script>
		';
    
    echo '<div style="display:none" id="vs-context-menu">
		        <ul>
		            <li id="select"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/select.png"/>' . $lang_module['select'] . '</li>
		            <li id="view"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/view.png"/>' . $lang_module['preview'] . '</li>
		            <li id="download"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/download.png"/>' . $lang_module['download'] . '</li>';
    if ( $admin_info['allow_modify_files'] )
    {
        echo '<li id="create"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/copy.png"/>' . $lang_module['upload_createimage'] . '</li>
			<li id="cut"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/cut.png"/>' . $lang_module['move'] . '</li>
		            <li id="rename"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/rename.png"/>' . $lang_module['rename'] . '</li>
		            <li id="delete"><img src="' . NV_BASE_SITEURL . 'js/contextmenu/icons/delete.png"/>' . $lang_module['upload_delfile'] . '</li>';
    }
    echo '
		        </ul>
		    </div>';
    echo '<script type="text/javascript">
		$(function(){';
    if ( ! empty( $selectfile ) )
    {
        echo '$.scrollTo("#imgselected", 80)';
    }
    echo '
		function createimage(){
			$("div#createimg").dialog("open");
		}
		function deleteimage(){
			var folder = $("span#foldervalue",parent.document).attr("title");
			var imgfile = $("#image",parent.document).attr("title");
			if (confirm("' . $lang_module['upload_delimg_confirm'] . '"+imgfile+"")){
				$.ajax({
				   type: "POST",
				   url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=delimg",
				   data: "path="+folder+"&img="+imgfile,
				   success: function(data){
				   		//alert(data);
						$("div#imglist",parent.document).html("<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&path="+folder+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
				   }
				});		
			}
		}
		function downloadimage(){
			var folder = $("span#foldervalue",parent.document).attr("title");
			var imgfile = $("#image",parent.document).attr("title");
			window.location="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=dlimg&path="+folder+"&img="+imgfile+"";
		}
		function selectimage(){
			var folder = $("span#foldervalue",parent.document).attr("title");
			var imgfile = $("#image",parent.document).attr("title");
			var area = $("#image",parent.document).attr("name");
			$("#posthidden",parent.document).val("' . NV_BASE_SITEURL . '"+folder+"/"+imgfile);
			window.parent.insertvaluetofield(); 
			top.window.close();
		}
		function renameimage(){
			$("div#renameimg").dialog("open");
		}
		function previewimage(){
			var folder = $("span#foldervalue",parent.document).attr("title");
			var imgfile = $("#image",parent.document).attr("title");
			var ext = imgfile.slice(-3);
			if (ext=="jpg"||ext=="png"||ext=="gif"||ext=="bmp"){
				$("div#imgpreview").html("<img src=\'' . NV_BASE_SITEURL . '"+folder+"/"+imgfile+"\'/>");
				$("div#imgpreview").dialog("open");
			} else {
				alert("' . $lang_module['nopreview'] . '");
			}
		}
		function movefolder(){
			$("div#movefolder").dialog("open");
		}
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
				var folder = $("span#foldervalue",parent.document).attr("title");
				var imgfile = $("#image",parent.document).attr("title");
				var area = $("#image",parent.document).attr("name");
				$("#posthidden",parent.document).val("' . NV_BASE_SITEURL . '"+folder+"/"+imgfile);
				window.parent.insertvaluetofield(); 
				//$("#"+area,parent.window.top.document).val("' . NV_BASE_SITEURL . '"+folder+"/"+imgfile); 
				//opener.document.getElementById(area).value = folder+"/"+imgfile;
				top.window.close();
			});
			$("img.previewimg").mouseup(function(){
				var imgsrc = $(this).attr("src");
				var imgtitle = $(this).attr("title");		
				$("#image",parent.document).attr("src",imgsrc);
				$("#image",parent.document).attr("title",imgtitle);
				$("img.previewimg").attr("style","padding:2px");
				$(this).css("border","2px solid red");
				$("input[name=width]").val($("#image",parent.document).width());
				$("input[name=height]").val($("#image",parent.document).height());
				var ext = imgtitle.slice(-3);
				if (ext=="jpg"||ext=="png"||ext=="gif"||ext=="bmp"){
					$("li#create").remove();
					$("li#info").remove();
					$("li#view").remove();';
    if ( $admin_info['allow_modify_files'] )
    {
        echo '$("#vs-context-menu>ul").append("<li id=\"create\"><img src=\"' . NV_BASE_SITEURL . 'js/contextmenu/icons/copy.png\"/>' . $lang_module['upload_createimage'] . '</li>");';
    }
    echo '$("#vs-context-menu>ul").append("<li id=\"view\"><img src=\"' . NV_BASE_SITEURL . 'js/contextmenu/icons/view.png\"/>' . $lang_module['preview'] . '</li>");
				} else {
					$("li#create").remove();
					$("li#view").remove();
					$("li#info").remove();
				}
			});	
		});
		</script>
		';
}
else
{
    echo "<div style=\"padding:50px; text-align:center; color:red;fontSize: 16px;\">" . $lang_module['upload_empty_path'] . "</div>";
}
echo "</body></html>";
?>