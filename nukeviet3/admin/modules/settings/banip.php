<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

function nv_save_file_banip ( )
{
    global $db, $db_config;
    $content_config_site = "";
    $content_config_admin = "";
    $sql = "SELECT ip, mask, area, begintime, endtime FROM `" . $db_config['prefix'] . "_banip`";
    $result = $db->sql_query( $sql );
    while ( list( $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
    {
        $dbendtime = intval( $dbendtime );
        $dbarea = intval( $dbarea );
        if ( $dbendtime == 0 or $dbendtime > NV_CURRENTTIME )
        {
            switch ( $dbmask )
            {
                case 3:
                    $ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/";
                    break;
                case 2:
                    $ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}$/";
                    break;
                case 1:
                    $ip_mask = "/\.[0-9]{1,3}$/";
                    break;
                default:
                    $ip_mask = "//";
            }
            if ( $dbarea == 1 or $dbarea == 3 )
            {
                $content_config_site .= "\$array_banip_site['" . $dbip . "'] = array('mask'=>\"" . $ip_mask . "\", 'begintime'=>" . $dbbegintime . ", 'endtime'=>" . $dbendtime . ");\n";
            }
            if ( $dbarea == 2 or $dbarea == 3 )
            {
                $content_config_admin .= "\$array_banip_admin['" . $dbip . "'] = array('mask'=>\"" . $ip_mask . "\", 'begintime'=>" . $dbbegintime . ", 'endtime'=>" . $dbendtime . ");\n";
            }
        }
    }
    
    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if ( ! defined( 'NV_MAINFILE' ) )\n";
    $content_config .= "{\n";
    $content_config .= "    die( 'Stop!!!' );\n";
    $content_config .= "}\n\n";
    $content_config .= "\$array_banip_site = array();\n";
    $content_config .= $content_config_site;
    $content_config .= "\n";
    $content_config .= "\$array_banip_admin = array();\n";
    $content_config .= $content_config_admin;
    $content_config .= "\n";
    $content_config .= "?>";
    
    return file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php", $content_config, LOCK_EX );

}
$page_title = $lang_module['banip'];
$cid = $nv_Request->get_int( 'id', 'get' );
$del = $nv_Request->get_int( 'del', 'get' );
if ( ! empty( $del ) && ! empty( $cid ) )
{
    $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_banip` WHERE id=$cid" );
    nv_save_file_banip();
}
$error = array();
$contents = "";
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $cid = $nv_Request->get_int( 'cid', 'post', 0 );
    $ip = filter_text_input( 'ip', 'post', '', 1 );
    $area = $nv_Request->get_int( 'area', 'post', 0 );
    $mask = $nv_Request->get_int( 'mask', 'post', 0 );
    $begintime = filter_text_input( 'begintime', 'post', 0, 1 );
    $endtime = filter_text_input( 'endtime', 'post', 0, 1 );
    
    if ( empty( $ip ) || ! $ips->nv_validip( $ip ) )
    {
        $error[] = $lang_module['banip_error_validip'];
    }
    if ( empty( $area ) )
    {
        $error[] = $lang_module['banip_error_area'];
    }
    if ( ! empty( $begintime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime, $m ) )
    {
        $begintime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $begintime = NV_CURRENTTIME;
    }
    if ( ! empty( $endtime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime, $m ) )
    {
        $endtime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $endtime = 0;
    }
    $notice = filter_text_input( 'notice', 'post', '', 1 );
    if ( empty( $error ) )
    {
        if ( $cid > 0 )
        {
            $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_banip` SET `ip`=" . $db->dbescape( $ip ) . ", `mask`=" . $db->dbescape( $mask ) . ",`area`=" . $area . ",`begintime`=" . $begintime . ", `endtime`=" . $endtime . ", `notice`=" . $db->dbescape( $notice ) . "" );
        }
        else
        {
            $db->sql_query( "REPLACE INTO `" . $db_config['prefix'] . "_banip` VALUES (NULL, " . $db->dbescape( $ip ) . "," . $db->dbescape( $mask ) . ",$area,$begintime, $endtime," . $db->dbescape( $notice ) . " )" );
        }
        nv_save_file_banip();
       	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
        die();
    }
    else
    {
        $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
        $contents .= "<blockquote class='error'>" . implode( '<br/>', $error ) . "</blockquote>\n";
        $contents .= "</div>\n";
        $contents .= "<div style='clear:both'></div>\n";
    }
}
else
{
    $id = $ip = $mask = $area = $begintime = $endtime = $notice = '';
}
$mask_text_array = array();
$mask_text_array[0] = "255.255.255.255";
$mask_text_array[3] = "255.255.255.xxx";
$mask_text_array[2] = "255.255.xxx.xxx";
$mask_text_array[1] = "255.xxx.xxx.xxx";

$banip_area_array = array();
$banip_area_array[0] = $lang_module['banip_area_select'];
$banip_area_array[1] = $lang_module['banip_area_front'];
$banip_area_array[2] = $lang_module['banip_area_admin'];
$banip_area_array[3] = $lang_module['banip_area_both'];

$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr align=\"center\">\n";
$contents .= "<td>" . $lang_module['banip_ip'] . "</td>\n";
$contents .= "<td>" . $lang_module['banip_mask'] . "</td>\n";
$contents .= "<td>" . $lang_module['banip_area'] . "</td>\n";
$contents .= "<td>" . $lang_module['banip_timeban'] . "</td>\n";
$contents .= "<td>" . $lang_module['banip_timeendban'] . "</td>\n";
$contents .= "<td>" . $lang_module['banip_funcs'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$sql = "SELECT id, ip, mask, area, begintime, endtime FROM `" . $db_config['prefix'] . "_banip` ORDER BY ip DESC";
$result = $db->sql_query( $sql );
while ( list( $dbid, $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
{
    $contents .= "<tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\">" . $dbip . "</td>\n";
    $contents .= "<td align=\"center\">" . $mask_text_array[$dbmask] . "</td>\n";
    $contents .= "<td align=\"center\">" . $banip_area_array[$dbarea] . "</td>\n";
    $contents .= "<td align=\"center\">" . ( ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '' ) . "</td>\n";
    $contents .= "<td align=\"center\">" . ( ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : $lang_module['banip_nolimit'] ) . "</td>\n";
    $contents .= "<td align=\"center\">
		<span class=\"edit_icon\">
			<a class='edit' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=banip&id=" . $dbid . "\">" . $lang_module['banip_edit'] . "</a>
		</span>	- 
		<span class=\"delete_icon\">
			<a class='deleteone' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=banip&del=1&id=" . $dbid . "\">" . $lang_module['banip_delete'] . "</a>
		</span></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
}
$contents .= "</table>\n";
if ( ! empty( $cid ) )
{
    list( $id, $ip, $mask, $area, $begintime, $endtime, $notice ) = $db->sql_fetchrow( $db->sql_query( "SELECT id, ip, mask, area, begintime, endtime, notice FROM `" . $db_config['prefix'] . "_banip` WHERE id=$cid" ) );
    $lang_module['banip_add'] = $lang_module['banip_edit'];
}
$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"cid\" value=\"" . $cid . "\" />";
$contents .= "<table class=\"tab1\" style=\"width:400px\">\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'><strong>" . $lang_module['banip_add'] . "</strong></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_address'] . " (<span style='color:red'>*</span>)<br/>(xxx.xxx.xxx.xxx)</td>\n";
$contents .= "<td><input type='text' name='ip' value='" . $ip . "' style='width:200px'/></td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_mask'] . "</td>\n";
$contents .= "<td>";
$contents .= "<select name='mask'>";
$contents .= "<option value='0'>" . $mask_text_array[0] . "</option>";
$contents .= "<option value='3' " . ( ( $mask == 3 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[3] . "</option>";
$contents .= "<option value='2' " . ( ( $mask == 2 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[2] . "</option>";
$contents .= "<option value='1' " . ( ( $mask == 1 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[1] . "</option>";
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_area'] . "</td>\n";
$contents .= "<td>";
$contents .= "<select name='area' id='area'>";
$contents .= "<option value='0'>" . $banip_area_array[0] . "</option>";
$contents .= "<option value='1' " . ( ( $area == 1 ) ? 'selected=selected' : '' ) . ">" . $banip_area_array[1] . "</option>";
$contents .= "<option value='2' " . ( ( $area == 2 ) ? 'selected=selected' : '' ) . ">" . $banip_area_array[2] . "</option>";
$contents .= "<option value='3' " . ( ( $area == 3 ) ? 'selected=selected' : '' ) . ">" . $banip_area_array[3] . "</option>";
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_begintime'] . "</td>\n";
$contents .= "<td><input type='text' name='begintime' id='begintime' value='" . ( ! empty( $begintime ) ? date( 'd.m.Y', $begintime ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'begintime', 'dd.mm.yyyy', true);\" alt=\"\" height=\"17\">\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_endtime'] . "</td>\n";
$contents .= "<td><input type='text' name='endtime' id='endtime' value='" . ( ! empty( $endtime ) ? date( 'd.m.Y', $endtime ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'endtime', 'dd.mm.yyyy', true);\" alt=\"\" height=\"17\">\n";
$contents .= "</tr>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['banip_notice'] . "</td>\n";
$contents .= "<td>";
$contents .= "<textarea name='notice' style='width:250px;height:100px'>" . $notice . "</textarea>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2' style='text-align:center'>";
$contents .= "<input type='submit' value='" . $lang_module['banip_confirm'] . "' name='submit'/>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";
$contents .= "
<script type='text/javascript'>
	$('input[name=submit]').click(function(){
		var ip = $('input[name=ip]').val();
		$('input[name=ip]').focus();
		if (ip==''){
			alert('" . $lang_module['banip_error_ip'] . "');
			return false;
		}
		var area = $('select[name=area]').val();
		$('select[name=area]').focus();
		if (area=='0'){
			alert('" . $lang_module['banip_error_area'] . "');
			return false;
		}		
	});
	$('a.deleteone').click(function(){
        if (confirm('" . $lang_module['banip_delete_confirm'] . "')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('" . $lang_module['banip_del_success'] . "');
		            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=banip';
		        }
	        });  
        }
		return false;
	});
</script>
";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>