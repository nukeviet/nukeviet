<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['content'] = $lang_module['weblink_add_link'];
$submenu['cat'] = $lang_module['weblink_catlist'];
//$submenu['checklink'] = $lang_module['weblink_checkalivelink'];
$submenu['brokenlink'] = $lang_module['weblink_link_broken'];
$submenu['config'] = $lang_module['weblink_config'];
$allow_func = array( 
    'main', 'cat', 'list_cat', 'change_cat', 'del_cat', 'content', 'del_link', 'config', 'multidel', 'checklink', 'brokenlink', 'delbroken' 
);
define( 'NV_IS_FILE_ADMIN', true );

function nv_fix_cat ( $parentid )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `catid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $row['catid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_show_cat_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_cat, $numcat;
    
    $array_cat = array();
    $sql = "SELECT catid, parentid, title, weight, inhome, numlinks FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `parentid`, `weight` ASC";
    $result = $db->sql_query( $sql );
    $numcat = 0;
    while ( list( $catid_i, $parentid, $title, $weight, $inhome, $numlinks ) = $db->sql_fetchrow( $result ) )
    {
        if ( $parentid == 0 )
        {
            $array_cat[$catid_i] = array( 
                'catid' => $catid_i, 'title' => $title, 'weight' => $weight, 'inhome' => $inhome, 'numlinks' => $numlinks 
            );
            $array_cat[$catid_i]['arraysubcat'] = array();
            $numcat ++;
        }
        else
        {
            $array_cat[$parentid]['arraysubcat'][$catid_i] = array( 
                'catid' => $catid_i, 'title' => $title, 'weight' => $weight, 'inhome' => $inhome, 'numlinks' => $numlinks 
            );
        }
    }
    $db->sql_freeresult();
    unset( $sql, $result );
    
    $contents = "";
    $array_inhome = array( 
        $lang_global['no'], $lang_global['yes'] 
    );
    if ( $numcat > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr align=\"center\">\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td style=\"width:90px;\">" . $lang_module['inhome'] . "</td>\n";
        $contents .= "<td style=\"width:120px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        foreach ( $array_cat as $catid_i => $array_cat_i )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $catid_i . "\" onchange=\"nv_chang_cat('" . $catid_i . "','weight');\">\n";
            for ( $i = 1; $i <= $numcat; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $array_cat_i['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            
            $contents .= "<td><strong>" . $array_cat_i['title'] . "</strong></td>\n";
            
            $contents .= "<td align=\"center\"><select id=\"id_inhome_" . $catid_i . "\" onchange=\"nv_chang_cat('" . $catid_i . "','inhome');\">\n";
            foreach ( $array_inhome as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $array_cat_i['inhome'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;catid=" . $catid_i . "\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_cat(" . $catid_i . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
            $sub_array_cat = $array_cat_i['arraysubcat'];
            $sub_num = count( $sub_array_cat );
            if ( $sub_num > 0 )
            {
                foreach ( $sub_array_cat as $sub_catid_i => $sub_array_cat_i )
                {
                    $class = ( $a % 2 ) ? " class=\"second\"" : "";
                    $contents .= "<tbody" . $class . ">\n";
                    $contents .= "<tr>\n";
                    $contents .= "<td align=\"right\"><select id=\"id_weight_" . $sub_catid_i . "\" onchange=\"nv_chang_cat('" . $sub_catid_i . "','weight');\">\n";
                    for ( $i = 1; $i <= $sub_num; $i ++ )
                    {
                        $contents .= "<option value=\"" . $i . "\"" . ( $i == $sub_array_cat_i['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
                    }
                    $contents .= "</select></td>\n";
                    
                    $contents .= "<td>" . $sub_array_cat_i['title'] . "</td>\n";
                    
                    $contents .= "<td align=\"center\"><select id=\"id_inhome_" . $sub_catid_i . "\" onchange=\"nv_chang_cat('" . $sub_catid_i . "','inhome');\">\n";
                    foreach ( $array_inhome as $key => $val )
                    {
                        $contents .= "<option value=\"" . $key . "\"" . ( $key == $sub_array_cat_i['inhome'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
                    }
                    $contents .= "</select></td>\n";
                    
                    $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;catid=" . $sub_catid_i . "\">" . $lang_global['edit'] . "</a></span>\n";
                    $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_cat(" . $sub_catid_i . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
                    $contents .= "</tr>\n";
                    $contents .= "</tbody>\n";
                    $a ++;
                }
            }
        }
        $contents .= "</table>\n";
    }
    
    return $contents;
}

?>