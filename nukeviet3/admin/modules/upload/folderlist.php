<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

$path = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'request', NV_UPLOADS_DIR ) ), ENT_QUOTES );

if ( empty( $path ) and defined( 'NV_IS_SPADMIN' ) )
{
    $path = "";
}
elseif ( ! nv_check_allow_upload_dir( $path ) )
{
    $path = NV_UPLOADS_DIR;
}
$currentpath = htmlspecialchars( trim( $nv_Request->get_string( 'currentpath', 'request', NV_UPLOADS_DIR ) ), ENT_QUOTES );
$titlepath = empty( $path ) ? NV_BASE_SITEURL : $path;
echo '<ul id="foldertree" class="filetree">';
echo '<li class="open collapsable"><span ' . ( ( $path == $currentpath ) ? ' style="color:red"' : '' ) . ' class="folder" title="' . $path . '">&nbsp;' . $titlepath . '</span>';
echo '<ul>';
$arr_files = @scandir( NV_ROOTDIR . '/' . $path );
foreach ( $arr_files as $file )
{
    $path_file = empty( $path ) ? $file : $path . '/' . $file;
    if ( is_dir( NV_ROOTDIR . '/' . $path_file ) && ! in_array( $file, $array_hidefolders ) && nv_check_allow_upload_dir( $path_file ) )
    {
        if ( ( $path_file == $currentpath ) )
        {
            echo '<li class="open collapsable"><span style="color:red" class="folder" title="' . ( $path_file ) . '">&nbsp;' . $file . '</span>';
        }
        elseif ( strpos( $currentpath, $path_file . '/' ) !== false )
        {
            echo '<li class="open collapsable"><span class="folder" title="' . ( $path_file ) . '">&nbsp;' . $file . '</span>';
        }
        else
        {
            echo '<li class="expandable"><span class="folder" title="' . ( $path_file ) . '">&nbsp;' . $file . '</span>';
        }
        echo '<ul>';
        viewdirtree( $path_file, $currentpath );
        echo '</ul>';
        echo '</li>';
    }
}
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '
<script type="text/javascript">
	$("#foldertree").treeview({
		collapsed: true,
		unique: true,
		persist: "location"
	});
    $("span.folder").contextMenu("folder-menu", {
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
        "renamefolder": function(t) {
        	var foldervalue = $("span#foldervalue").attr("title");
        	var lastindex = foldervalue.lastIndexOf("/");
        	var foldername = foldervalue.substr(lastindex+1);
        	$("input[name=foldername]").val(foldername);
			$("div#renamefolder").dialog("open");
        },      
        "createfolder": function(t) {
			$("div#createfolder").dialog("open");
        },
        "deletefolder": function(t) {
          	var foldervalue = $("span#foldervalue").attr("title");
			if (confirm("' . $lang_module['delete_folder'] . '")){
				$.ajax({
				   type: "POST",
				   url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=delfolder",
				   data: "path="+foldervalue,
				   success: function(data){
						$("#imgfolder").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=folderlist&path=' . $path . '");
						$("div#imglist").html("<iframe src=\"' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&path=' . $path . '\" style=\"width:100%;height:360px;border:none\"></iframe>");
				   }
				});		
			}
        }
      }
    });
	$("span.folder").click(function(){
		var folder = $(this).attr("title");
		$("span#foldervalue").attr("title",folder);
		$("input[name=path]").val(folder);		
		$("span.folder").css("color","");
		$(this).css("color","red");
		var type = $("select[name=imgtype]").val();
		$("div#imglist").html("<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=imglist&path="+folder+"&type="+type+"\' style=\"width:100%;height:360px;border:none\"></iframe>");
	});
	$("span.folder").mouseup(function(){
		var foldervalue = $(this).attr("title");
		$("span#foldervalue").attr("title",foldervalue);
		$("input[name=path]").val(foldervalue);
	});	
</script>
';
?>