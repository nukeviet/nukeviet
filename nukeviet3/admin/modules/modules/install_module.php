<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
$title = $note = $module_file = "";
$page_title = $lang_module['autoinstall_module_install'];

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

if ( $nv_Request->isset_request( 'op', 'post' ) )
{
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    
    $allowfolder = array( 
        'modules', 'themes', 'uploads' 
    );
    if ( is_uploaded_file( $_FILES['modulefile']['tmp_name'] ) )
    {
        if ( move_uploaded_file( $_FILES['modulefile']['tmp_name'], $filename ) )
        {
            $zip = new PclZip( $filename );
            $status = $zip->properties();
            if ( $status['status'] == 'ok' )
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_module'], basename($_FILES['modulefile']['name']) , $admin_info['userid'] );
            	$filefolder = '';
                $validfolder = array();
                $filelist = '';
                $filesize = nv_convertfromBytes( $_FILES['modulefile']['size'] );
                $contents .= $lang_module['autoinstall_module_uploadedfile'] . '<span style="color:red;font-weight:bold">' . $_FILES['modulefile']['name'] . '</span> - ' . $lang_module['autoinstall_module_uploadedfilesize'] . '<span style="color:red;font-weight:bold">' . $filesize . '</span><br />';
                #show file and folder
                $list = $zip->listContent();
                $contents .= "<br /><b>" . $lang_module['autoinstall_module_uploaded_filenum'] . $status['nb'] . "</b><br />";
                for ( $i = 0, $j = 1; $i < sizeof( $list ); $i ++, $j ++ )
                {
                    if ( ! $list[$i]['folder'] )
                    {
                        $bytes = nv_convertfromBytes( $list[$i]['size'] );
                    }
                    else
                    {
                        $bytes = "";
                        $validfolder[] = $list[$i]['filename'];
                    }
                    $filefolder .= $list[$i]['filename'] . "\n";
                    $filelist .= '[' . $j . "] " . $list[$i]['filename'] . " $bytes<br />";
                }
                $contents .= '<div style="overflow:auto;height:200px;width:700px">' . $filelist . '</div>';
                #check to continue
                $contents .= '
				<div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif"/>' . $lang_module['autoinstall_package_processing'] . '</div>
				<div style="margin-top:20px" id="step1">
				<h4>' . $lang_module['autoinstall_module_checkfile_notice'] . '</h4>
				<p style="padding-left:250px">
				<input style="margin-top:10px;font-size:15px" type="button" name="checkfile" value="' . $lang_module['autoinstall_module_checkfile'] . '"/>
				</p>
				</div>
				<script type="text/javascript">
				 $(function(){
				 	$("input[name=checkfile]").click(function(){
				 		$("#message").show();
				 		$("#step1").html("");
				 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=install_check",function(){
							$("#message").hide();
						});
				 	});
				 });
				</script>
				';
            }
            else
            {
                $contents .= $lang_module['autoinstall_module_error_invalidfile'] . '  <a href="javascript:history.go(-1)">' . $lang_module['autoinstall_module_error_invalidfile_back'] . '</a>';
            }
        }
        else
            $contents .= "<span style='color:red'>" . $lang_module['autoinstall_module_error_uploadfile'] . "</span><br />";
    }
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    $op = $nv_Request->get_string( 'op', 'get' );
    $contents .= "<form name='install_module' enctype='multipart/form-data' action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" method=\"post\">";
    $contents .= "<table summary=\"\" class=\"tab1\">\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td align=\"right\"><strong>" . $lang_module['autoinstall_module_select_file'] . ": </strong></td>\n";
    $contents .= "<td>";
    $contents .= "<input type='hidden' name='" . NV_OP_VARIABLE . "' value='" . $op . "'/>";
    $contents .= "<input type='file' name='modulefile'/>";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tr>";
    $contents .= "<td colspan='2' align='center'>";
    $contents .= "<input name=\"continue\" type=\"button\" value=\"" . $lang_module['autoinstall_continue'] . "\" />\n";
    $contents .= "<input name=\"back\" type=\"button\" value=\"" . $lang_module['autoinstall_back'] . "\" />\n";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</table>";
    $contents .= "</form>";
    $contents .= '
				<script type="text/javascript">
				function checkext(myArray,myValue) {
					var type = eval(myArray).join().indexOf(myValue)>=0;
					return type;
				}
				 $(function(){
				 	$("input[name=continue]").click(function(){
						var modulefile = $("input[name=modulefile]").val();
						if (modulefile==""){
							alert("' . $lang_module['autoinstall_module_error_nofile'] . '");
							return false;
						}
						var filezip = modulefile.slice(-3);
						var filegzip = modulefile.slice(-2);
						var allowext = new Array("zip","gz");
						if (!checkext(allowext,filezip) || !checkext(allowext,filegzip)){
							alert("' . $lang_module['autoinstall_module_error_filetype'] . '");
						    return false;
						}
						$("form[name=install_module]").submit();
				 	});
				 	$("input[name=back]").click(function(){
				 		$("#content").slideUp();
						$("#step1").slideDown();
				 	});
				
				 });
				</script>
				';
    echo $contents;
}
?>