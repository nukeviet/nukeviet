<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */
if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$is_refresh = false;
$array_cat_admin = nv_array_cat_admin();

if ( ! empty( $module_info['admins'] ) )
{
    $module_admin = explode( ",", $module_info['admins'] );
    foreach ( $module_admin as $userid_i )
    {
        if ( ! isset( $array_cat_admin[$userid_i] ) )
        {
            $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_admins` (`userid`, `catid`, `admin`, `add_content`, `pub_content`, `edit_content`, `del_content`, `comment`) VALUES ('" . $userid_i . "', '0', '1', '1', '1', '1', '1', '1')" );
            $is_refresh = true;
        }
    }
}

if ( $is_refresh )
{
    $array_cat_admin = nv_array_cat_admin();
}

$admin_id = $admin_info['admin_id'];
if ( defined( 'NV_IS_SPADMIN' ) )
{
    define( 'NV_IS_ADMIN_MODULE', true );
    define( 'NV_IS_ADMIN_FULL_MODULE', true );
}
else
{
    if ( isset( $array_cat_admin[$admin_id][0] ) )
    {
        define( 'NV_IS_ADMIN_MODULE', true );
        if ( intval( $array_cat_admin[$admin_id][0]['admin'] ) == 2 )
        {
            define( 'NV_IS_ADMIN_FULL_MODULE', true );
        }
    }
}

$allow_func = array( 
    'main', 'exptime', 'publtime', 'content', 'del_content', 'comment', 'edit_comment', 'active_comment', 'del_comment', 'keywords', 'alias', 'topicajax', 'sourceajax', 'cat', 'change_cat', 'list_cat', 'del_cat' 
);

$submenu['cat'] = $lang_module['categories'];
$submenu['content'] = $lang_module['content_add'];
$submenu['comment'] = $lang_module['comment'];

if ( defined( 'NV_IS_ADMIN_MODULE' ) )
{
    $submenu['topics'] = $lang_module['topics'];
    $submenu['blockcat'] = $lang_module['block'];
    $submenu['sources'] = $lang_module['sources'];
    $submenu['setting'] = $lang_module['setting'];
    
    $allow_func[] = 'topicsnews';
    $allow_func[] = 'topics';
    $allow_func[] = 'topicdelnews';
    $allow_func[] = 'addtotopics';
    $allow_func[] = 'change_topic';
    $allow_func[] = 'list_topic';
    $allow_func[] = 'del_topic';
    
    $allow_func[] = 'sources';
    $allow_func[] = 'change_source';
    $allow_func[] = 'list_source';
    $allow_func[] = 'del_source';
    
    $allow_func[] = 'block';
    $allow_func[] = 'blockcat';
    $allow_func[] = 'del_block_cat';
    $allow_func[] = 'list_block_cat';
    $allow_func[] = 'chang_block_cat';
    $allow_func[] = 'change_block';
    $allow_func[] = 'list_block';
    
    $allow_func[] = 'setting';
}

if ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/admin/admins.php" ) )
{
    $submenu['admins'] = $lang_module['admin'];
    $allow_func[] = 'admins';
}

$array_viewcat_full = array( 
    'viewcat_page_new' => $lang_module['viewcat_page_new'], 'viewcat_page_old' => $lang_module['viewcat_page_old'], 'viewcat_list_new' => $lang_module['viewcat_list_new'], 'viewcat_list_old' => $lang_module['viewcat_list_old'], 'viewcat_grid_new' => $lang_module['viewcat_grid_new'], 'viewcat_grid_old' => $lang_module['viewcat_grid_old'], 'viewcat_main_left' => $lang_module['viewcat_main_left'], 'viewcat_main_right' => $lang_module['viewcat_main_right'], 'viewcat_main_bottom' => $lang_module['viewcat_main_bottom'], 'viewcat_two_column' => $lang_module['viewcat_two_column'] 
);
$array_viewcat_nosub = array( 
    'viewcat_page_new' => $lang_module['viewcat_page_new'], 'viewcat_page_old' => $lang_module['viewcat_page_old'], 'viewcat_list_new' => $lang_module['viewcat_list_new'], 'viewcat_list_old' => $lang_module['viewcat_list_old'], 'viewcat_grid_new' => $lang_module['viewcat_grid_new'], 'viewcat_grid_old' => $lang_module['viewcat_grid_old'] 
);
$array_who_view = array( 
    $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
);
$array_allowed_comm = array( 
    $lang_global['no'], $lang_global['who_view0'], $lang_global['who_view1'] 
);

define( 'NV_IS_FILE_ADMIN', true );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

global $global_array_cat;
$global_array_cat = array();
$sql = "SELECT catid, parentid, title, alias, lev, viewcat,numsubcat, subcatid, numlinks, del_cache_time, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $lev_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
{
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "numsubcat" => $numsubcat_i, "lev" => $lev_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i 
    );
}

function nv_fix_cat_order ( $parentid = 0, $order = 0, $lev = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `catid`, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $array_cat_order = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $array_cat_order[] = $row['catid'];
    }
    $db->sql_freeresult();
    $weight = 0;
    if ( $parentid > 0 )
    {
        $lev ++;
    }
    else
    {
        $lev = 0;
    }
    foreach ( $array_cat_order as $catid_i )
    {
        $order ++;
        $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . ", `order`=" . $order . ", `lev`='" . $lev . "' WHERE `catid`=" . intval( $catid_i );
        $db->sql_query( $sql );
        $order = nv_fix_cat_order( $catid_i, $order, $lev );
    }
    $numsubcat = $weight;
    if ( $parentid > 0 )
    {
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `numsubcat`=" . $numsubcat;
        if ( $numsubcat == 0 )
        {
            $sql .= ",`subcatid`='', `viewcat`='viewcat_page_new'";
        }
        else
        {
            $sql .= ",`subcatid`='" . implode( ",", $array_cat_order ) . "'";
        }
        $sql .= " WHERE `catid`=" . intval( $parentid );
        $db->sql_query( $sql );
    }
    return $order;
}

// tao bang co so du lieu cho cac chu de
function nv_create_table_rows ( $catid )
{
    global $db, $module_name, $module_data;
    $db->sql_query( "SET SQL_QUOTE_SHOW_CREATE = 1" );
    $result = $db->sql_query( "SHOW CREATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_rows`" );
    $show = $db->sql_fetchrow( $result );
    $db->sql_freeresult( $result );
    $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
    $sql = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
    $sql = str_replace( NV_PREFIXLANG . "_" . $module_data . "_rows", NV_PREFIXLANG . "_" . $module_data . "_" . $catid, $sql );
    $db->sql_query( $sql );
    $db->sql_query( "TRUNCATE TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "" );
}

function nv_fix_topic ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `topicid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topics` SET `weight`=" . $weight . " WHERE `topicid`=" . intval( $row['topicid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_fix_block_cat ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `bid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $weight = 0;
    $result = $db->sql_query( $query );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` SET `weight`=" . $weight . " WHERE `bid`=" . intval( $row['bid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_fix_source ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `sourceid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_sources` SET `weight`=" . $weight . " WHERE `sourceid`=" . intval( $row['sourceid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_news_fix_block ( $bid, $repairtable = true )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $bid = intval( $bid );
    if ( $bid > 0 )
    {
        $query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` where `bid`='" . $bid . "' ORDER BY `weight` ASC";
        $result = $db->sql_query( $query );
        $weight = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $weight ++;
            if ( $weight <= 100 )
            {
                $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_block` SET `weight`=" . $weight . " WHERE `bid`='" . $bid . "' AND `id`=" . intval( $row['id'] );
            }
            else
            {
                $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` WHERE `bid`='" . $bid . "' AND `id`=" . intval( $row['id'] );
            }
            $db->sql_query( $sql );
        }
        $db->sql_freeresult();
        if ( $repairtable )
        {
            $db->sql_query( "REPAIR TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_block`" );
            $db->sql_query( "OPTIMIZE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_block`" );
        }
    }
}

function nv_show_cat_list ( $parentid = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_full, $array_viewcat_nosub, $admin_info, $array_cat_admin, $global_array_cat, $admin_id;
    
    // Cac chu de co quyen han
    $array_cat_check_content = array();
    foreach ( $global_array_cat as $catid_i => $array_value )
    {
        if ( defined( 'NV_IS_ADMIN_MODULE' ) )
        {
            $array_cat_check_content[] = $catid_i;
        }
        elseif ( isset( $array_cat_admin[$admin_id][$catid_i] ) )
        {
            if ( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
            {
                $array_cat_check_content[] = $catid_i;
            }
            elseif ( $array_cat_admin[$admin_id][$catid_i]['add_content'] == 1 )
            {
                $array_cat_check_content[] = $catid_i;
            }
            elseif ( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 )
            {
                $array_cat_check_content[] = $catid_i;
            }
            elseif ( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
            {
                $array_cat_check_content[] = $catid_i;
            }
        }
    }
    // Cac chu de co quyen han    
    $contents = "";
    if ( $parentid > 0 )
    {
        $parentid_i = $parentid;
        $array_cat_title = array();
        while ( $parentid_i > 0 )
        {
            $array_cat_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $parentid_i . "\"><strong>" . $global_array_cat[$parentid_i]['title'] . "</strong></a>";
            $parentid_i = $global_array_cat[$parentid_i]['parentid'];
        }
        sort( $array_cat_title, SORT_NUMERIC );
        $contents .= implode( " -> ", $array_cat_title );
    }
    
    $sql = "SELECT catid, parentid, title, weight, viewcat, numsubcat, inhome, numlinks FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid` = '" . $parentid . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td align=\"center\" style=\"width:40px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:150px;\">" . $lang_module['inhome'] . "</td>\n";
        $contents .= "<td>" . $lang_module['viewcat_page'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:90px;\">" . $lang_module['numlinks'] . "</td>\n";
        $contents .= "<td style=\"width:200px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 1;
        $array_inhome = array( 
            $lang_global['no'], $lang_global['yes'] 
        );
        
        while ( list( $catid, $parentid, $title, $weight, $viewcat, $numsubcat, $inhome, $numlinks ) = $db->sql_fetchrow( $result ) )
        {
            if ( defined( 'NV_IS_ADMIN_MODULE' ) )
            {
                $check_show = 1;
            }
            else
            {
                $array_cat = GetCatidInParent( $catid );
                $check_show = array_intersect( $array_cat, $array_cat_check_content );
            }
            if ( ! empty( $check_show ) )
            {
                $array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
                if ( ! array_key_exists( $viewcat, $array_viewcat ) )
                {
                    $viewcat = "viewcat_page_new";
                    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `viewcat`=" . $db->dbescape( $viewcat ) . " WHERE `catid`=" . intval( $catid );
                    $db->sql_query( $sql );
                }
                
                $admin_funcs = array();
                $weight_disabled = $func_cat_disabled = true;
                if ( defined( 'NV_IS_ADMIN_MODULE' ) or ( isset( $array_cat_admin[$admin_id][$catid] ) and $array_cat_admin[$admin_id][$catid]['add_content'] == 1 ) )
                {
                    $func_cat_disabled = false;
                    $admin_funcs[] = "<span class=\"add_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;catid=" . $catid . "&amp;parentid=" . $parentid . "\">" . $lang_module['content_add'] . "</a></span>\n";
                }
                if ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
                {
                    $func_cat_disabled = false;
                    $admin_funcs[] = "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;catid=" . $catid . "&amp;parentid=" . $parentid . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
                }
                if ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) )
                {
                    $weight_disabled = false;
                    $admin_funcs[] = "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_cat(" . $catid . ")\">" . $lang_global['delete'] . "</a></span>";
                }
                
                $class = ( $a % 2 ) ? " class=\"second\"" : "";
                $contents .= "<tbody" . $class . ">\n";
                $contents .= "<tr>\n";
                $contents .= "<td align=\"center\">";
                if ( $weight_disabled )
                {
                    $contents .= $a;
                }
                else
                {
                    $contents .= "<select id=\"id_weight_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','weight');\">\n";
                    for ( $i = 1; $i <= $num; $i ++ )
                    {
                        $contents .= "<option value=\"" . $i . "\"" . ( $i == $weight ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
                    }
                    $contents .= "</select>";
                }
                $contents .= "</td>\n";
                $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid . "\"><strong>" . $title . "</strong></a>";
                if ( $numsubcat > 0 ) $contents .= "  <span style=\"color:#FF0101;\">(" . $numsubcat . ")</span>";
                $contents .= "</td>\n";
                $contents .= "<td align=\"center\">";
                if ( $func_cat_disabled )
                {
                    $contents .= $array_inhome[$inhome];
                }
                else
                {
                    $contents .= "<select id=\"id_inhome_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','inhome');\">\n";
                    foreach ( $array_inhome as $key => $val )
                    {
                        $contents .= "<option value=\"" . $key . "\"" . ( $key == $inhome ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
                    }
                    $contents .= "</select>";
                }
                $contents .= "</td>\n";
                $contents .= "<td align=\"left\">";
                if ( $func_cat_disabled )
                {
                    $contents .= $array_viewcat[$viewcat];
                }
                else
                {
                    $contents .= "<select id=\"id_viewcat_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','viewcat');\">\n";
                    foreach ( $array_viewcat as $key => $val )
                    {
                        $contents .= "<option value=\"" . $key . "\"" . ( $key == $viewcat ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
                    }
                    $contents .= "</select>";
                }
                $contents .= "</td>\n";
                $contents .= "<td align=\"center\">";
                if ( $func_cat_disabled )
                {
                    $contents .= $numlinks;
                
                }
                else
                {
                    $contents .= "<select id=\"id_numlinks_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','numlinks');\">\n";
                    for ( $i = 0; $i <= 10; $i ++ )
                    {
                        $contents .= "<option value=\"" . $i . "\"" . ( $i == $numlinks ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
                    }
                    $contents .= "</select>";
                }
                $contents .= "</td>\n";
                $contents .= "<td align=\"center\">";
                $contents .= implode( "&nbsp;-&nbsp;", $admin_funcs );
                $contents .= "</td>\n";
                $contents .= "</tr>\n";
                $contents .= "</tbody>\n";
                $a ++;
            }
        }
        $contents .= "</table>\n";
    }
    else
    {
        $contents .= "&nbsp;";
    }
    $db->sql_freeresult();
    unset( $sql, $result );
    return $contents;
}

function nv_show_topics_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents = "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td>" . $lang_module['description'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:120px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $array_inhome = array( 
            $lang_global['no'], $lang_global['yes'] 
        );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $row['topicid'] . "\" onchange=\"nv_chang_topic('" . $row['topicid'] . "','weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*)  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `topicid`=" . $row['topicid'] . "" ) );
            $contents .= ( $numnews > 0 ) ? "<td><a href='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topicsnews&amp;topicid=" . $row['topicid'] . "'>" . $row['title'] . " ($numnews " . $lang_module['topic_num_news'] . ")</a></td>\n" : "<td><a href='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topicsnews&amp;topicid=" . $row['topicid'] . "'>" . $row['title'] . "</a></td>\n";
            $contents .= "<td>" . $row['description'] . "</td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topics&amp;topicid=" . $row['topicid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_topic(" . $row['topicid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    else
    {
        $contents = "&nbsp;";
    }
    $db->sql_freeresult();
    return $contents;
}

function nv_show_block_cat_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents = "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"width:50px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:40px;\">ID</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td align=\"center\">" . $lang_module['adddefaultblock'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:90px;\">" . $lang_module['numlinks'] . "</td>\n";
        $contents .= "<td style=\"width:100px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $array_adddefault = array( 
            $lang_global['no'], $lang_global['yes'] 
        );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $row['bid'] . "\" onchange=\"nv_chang_block_cat('" . $row['bid'] . "','weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"center\"><b>" . $row['bid'] . "</b></td>\n";
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*)  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` where `bid`=" . $row['bid'] . "" ) );
            if ( $numnews )
            {
                $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=block&amp;bid=" . $row['bid'] . "\">" . $row['title'] . " ($numnews " . $lang_module['topic_num_news'] . ")</a>";
            }
            else
            {
                $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=block&amp;bid=" . $row['bid'] . "\">" . $row['title'] . "</a>";
            }
            $contents .= " </td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_adddefault_" . $row['bid'] . "\" onchange=\"nv_chang_block_cat('" . $row['bid'] . "','adddefault');\">\n";
            foreach ( $array_adddefault as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $row['adddefault'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_numlinks_" . $row['bid'] . "\" onchange=\"nv_chang_block_cat('" . $row['bid'] . "','numlinks');\">\n";
            for ( $i = 1; $i <= 30; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row['number'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;bid=" . $row['bid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_block_cat(" . $row['bid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    else
    {
        $contents = "&nbsp;";
    }
    $db->sql_freeresult();
    return $contents;
}

function nv_show_sources_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $nv_Request;
    $num = $db->sql_numrows( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` ASC" ) );
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&amp;" . NV_OP_VARIABLE . "=sources";
    $all_page = ( $num > 1 ) ? $num : 1;
    $per_page = 15;
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    if ( $num > 0 )
    {
        $contents = "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td>" . $lang_module['link'] . "</td>\n";
        $contents .= "<td style=\"width:120px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` LIMIT $page, $per_page" );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $row['sourceid'] . "\" onchange=\"nv_chang_sources('" . $row['sourceid'] . "','weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td>" . $row['title'] . "</td>\n";
            $contents .= "<td>" . $row['link'] . "</td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=sources&amp;sourceid=" . $row['sourceid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_source(" . $row['sourceid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
        $contents .= nv_generate_page( $base_url, $all_page, $per_page, $page );
    }
    else
    {
        $contents = "&nbsp;";
    }
    $db->sql_freeresult();
    return $contents;
}

function nv_content_keywords ( $content )
{
    global $db, $db_config, $lang_module, $module_name, $op, $global_config, $sys_info;
    $keywords = "n/a";
    if ( $content != "" )
    {
        $arrkw = array();
        $memoryLimitMB = ( integer )ini_get( 'memory_limit' );
        if ( $memoryLimitMB > 60 and file_exists( NV_ROOTDIR . "/includes/keywords/" . NV_LANG_DATA . ".txt" ) )
        {
            require_once ( NV_ROOTDIR . "/includes/keywords/" . NV_LANG_DATA . ".txt" );
        }
        $keywords_return = array();
        if ( ! empty( $arrkw ) )
        {
            nv_internal_encoding( $global_config['site_charset'] );
            $content = nv_strtolower( $content );
            $content = str_replace( array( 
                '&quot;', '&copy;', '&gt;', '&lt;', '&nbsp;' 
            ), " ", $content );
            $content = str_replace( array( 
                ',', ')', '(', '.', "'", '"', '<', '>', ';', '!', '?', '/', '-', '_', '[', ']', ':', '+', '=', '#', '$', chr( 10 ), chr( 13 ), chr( 9 ) 
            ), " ", $content );
            $content = preg_replace( '/ {2,}/si', " ", $content );
            $content_array = explode( " ", $content );
            $a = 0;
            $b = count( $content_array );
            for ( $i = 0; $i < $b - 3; $i ++ )
            {
                $key3 = $content_array[$i] . " " . $content_array[$i + 1] . " " . $content_array[$i + 2];
                $key2 = $content_array[$i] . " " . $content_array[$i + 1];
                if ( array_search( $key3, $arrkw ) )
                {
                    $keywords_return[] = $key3;
                }
                elseif ( array_search( $key2, $arrkw ) )
                {
                    $keywords_return[] = $key2;
                }
                $keywords_return = array_unique( $keywords_return );
                if ( count( $keywords_return ) > 20 )
                {
                    break;
                }
            }
            $keywords = implode( ", ", $keywords_return );
        }
        else
        {
            $keywords = nv_get_keywords( $content );
            if ( empty( $keywords ) )
            {
                $keywords = "n/a";
            }
        }
    }
    return $keywords;
}

function nv_show_block_list ( $bid )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $contents = "";
    
    $global_array_cat = array();
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other";
    $global_array_cat[0] = array( 
        "catid" => 0, "parentid" => 0, "title" => "Other", "alias" => "Other", "link" => $link_i, "viewcat" => "viewcat_page_new", "subcatid" => 0, "numlinks" => 3, "description" => "", "keywords" => "" 
    );
    
    $sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, del_cache_time, description, keywords, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
    $result = $db->sql_query( $sql );
    while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
    {
        $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
        $global_array_cat[$catid_i] = array( 
            "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "keywords" => $keywords_i 
        );
    }
    
    $sql = "SELECT t1.id, t1.listcatid, t1.title, t1.alias, t2.weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.inhome='1' ORDER BY t2.weight ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents = "<form name=\"block_list\" action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;bid=" . $bid . "\" method=\"get\">";
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr>\n";
        $contents .= "<td align=\"center\"><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td style=\"width:200px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $contents .= "<tfoot>\n";
        $contents .= "<tr align=\"left\">\n";
        $contents .= "<td colspan=\"5\"><input type=\"button\" onclick=\"nv_del_block_list(this.form, " . $bid . ")\" value=\"" . $lang_module['delete_from_block'] . "\">\n";
        $contents .= "</td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tfoot>\n";
        $a = 0;
        while ( list( $id, $listcatid, $title, $alias, $weight ) = $db->sql_fetchrow( $result ) )
        {
            $arr_listcatid = explode( ",", $listcatid );
            $catid_i = end( $arr_listcatid );
            
            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><input type=\"checkbox\" onclick=\"nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);\" value=\"" . $id . "\" name=\"idcheck[]\" /></td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $id . "\" onchange=\"nv_chang_block(" . $bid . ", " . $id . ",'weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $weight ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"left\"><a target=\"_blank\" href=\"" . $link . "\">" . $title . "</a></td>\n";
            $contents .= "<td align=\"center\">\n";
            $contents .= "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_chang_block(" . $bid . ", " . $id . ",'delete')\">" . $lang_module['delete_from_block'] . "</a></span>\n";
            $contents .= "</td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
        $contents .= "</form>\n";
        $db->sql_freeresult();
    }
    return $contents;
}

function GetCatidInParent ( $catid )
{
    global $global_array_cat;
    $array_cat = array();
    $array_cat[] = $catid;
    $subcatid = explode( ",", $global_array_cat[$catid]['subcatid'] );
    if ( ! empty( $subcatid ) )
    {
        foreach ( $subcatid as $id )
        {
            if ( $id > 0 )
            {
                if ( $global_array_cat[$id]['numsubcat'] == 0 )
                {
                    $array_cat[] = $id;
                }
                else
                {
                    $array_cat_temp = GetCatidInParent( $id );
                    foreach ( $array_cat_temp as $catid_i )
                    {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }
    return array_unique( $array_cat );
}

function nv_array_cat_admin ( )
{
    global $db, $module_data;
    $array_cat_admin = array();
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_admins` ORDER BY `userid` ASC";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $array_cat_admin[$row['userid']][$row['catid']] = $row;
    }
    return $array_cat_admin;
}

function redriect ( $msg1 = "", $msg2 = "", $nv_redirect )
{
    if ( empty( $nv_redirect ) )
    {
        $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
    }
    //////////////////////////////////////////////////////////////////////
    $contents = "<table><tr><td>";
    $contents .= "<div align=\"center\">";
    $contents .= "<strong>" . $msg1 . "</strong><br /><br />\n";
    $contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" /><br /><br />\n";
    $contents .= "<strong><a href=\"" . $nv_redirect . "\">" . $msg2 . "</a></strong>";
    $contents .= "</div>";
    $contents .= "</td></tr></table>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
?>