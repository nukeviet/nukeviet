<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu ['content'] = $lang_module ['aabout1'];

$allow_func = array( 
    'main', 'list', 'content', 'alias', 'change_status', 'change_weight', 'del' 
);

function nv_show_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data;
    $contents = "";
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td>" . $lang_module ['aabout3'] . "</td>\n";
        $contents .= "<td>" . $lang_module ['aabout2'] . "</td>\n";
        $contents .= "<td>" . $lang_module ['aabout4'] . "</td>\n";
        $contents .= "<td></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td><select id=\"change_weight_" . $row ['id'] . "\" onchange=\"nv_chang_weight('" . $row ['id'] . "');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row ['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td>" . $row ['title'] . "</td>\n";
            $contents .= "<td><select id=\"change_status_" . $row ['id'] . "\" onchange=\"nv_chang_status('" . $row ['id'] . "');\">\n";
            $array = array( 
                $lang_module ['aabout6'], $lang_module ['aabout5'] 
            );
            foreach ( $array as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $row ['status'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            
            $contents .= "<td><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row ['id'] . "\">" . $lang_global ['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_module_del(" . $row ['id'] . ")\">" . $lang_global ['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    else
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content" );
        die();
    }
    return $contents;
}

define( 'NV_IS_FILE_ADMIN', true );

?>