<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$contents .= '
<table>
<tbody>
		<tr>
			<td>
				<input type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/><input style="width:300px" type="text" name="selectedimg" id="selectedimg" value=""/>
				<script type="text/javascript">
					$("input[name=selectimg]").click(function(){
						var area = "selectedimg";
						var path= "uploads";
						var type= "image";
						MM_openBrWindow("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "400","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});
				</script>			
			</td>			
		</tr>
</tbody>
</table>
';

include ( NV_ROOTDIR . "/includes/header.php" );
echo "
<script type='text/javascript'>
function MM_openBrWindow(theURL,winName,w,h,features) {
		LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
		settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition;
		if(features != '') {
			settings = settings + ','+features;
		}
		window.open(theURL,winName,settings);
}
</script>
";
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>