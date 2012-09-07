<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

global $global_array_cat, $global_array_group;
$global_array_cat = array();

$sql = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
    );
}

$submenu['items'] = $lang_module['content_add_items'];
$submenu['content'] = $lang_module['content_add'];
$submenu['cat'] = $lang_module['categories'];
//$submenu['topics'] = $lang_module['topics'];
$submenu['group'] = $lang_module['group'];
$submenu['blockcat'] = $lang_module['block'];
$submenu['sources'] = $lang_module['sources'];
$submenu['comment'] = $lang_module['comment'];
$submenu['prounit'] = $lang_module['prounit'];
$submenu['order'] = $lang_module['order_title'];
$submenu['money'] = $lang_module['money'];
$submenu['payport'] = $lang_module['setup_payment'];
$submenu['docpay'] = $lang_module['document_payment'];
$submenu['setting'] = $lang_module['setting'];
$allow_func = array( 
    'main', 'alias', 'items', 'exptime', 'publtime', 'setting', 'content', 'keywords', 'del_content', 'cat', 'change_cat', 'list_cat', 'del_cat', 'topicsnews', 'topics', 'topicdelnews', 'addtotopics', 'change_topic', 'list_topic', 'del_topic', 'topicajax', 'sources', 'change_source', 'list_source', 'del_source', 'sourceajax', 'block', 'blockcat', 'del_block_cat', 'list_block_cat', 'chang_block_cat', 'change_block', 'list_block', 'comment', 'del_comment', 'active_comment', 'loadcate', 'prounit', 'delunit', 'order', 'or_del', 'or_view', 'money', 'delmoney', 'active_pay', 'payport', 'changepay', 'actpay', 'docpay', 'group', 'del_group', 'list_group', 'change_group', 'getcatalog', 'getgroup' 
);

$array_viewcat_full = array( 
    'view_home_cat' => $lang_module['view_home_cat'], 'viewcat_page_list' => $lang_module['viewcat_page_list'], 'viewcat_page_gird' => $lang_module['viewcat_page_gird'] 
);
$array_viewcat_nosub = array( 
    'viewcat_page_list' => $lang_module['viewcat_page_list'], 'viewcat_page_gird' => $lang_module['viewcat_page_gird'] 
);
$array_who_view = array( 
    $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
);
$array_allowed_comm = array( 
    $lang_global['no'], $lang_global['who_view0'], $lang_global['who_view1'] 
);
define( 'NV_IS_FILE_ADMIN', true );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

function nv_fix_cat_order ( $parentid = 0, $order = 0, $lev = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `catid`, `parentid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
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
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `weight`=" . $weight . ", `order`=" . $order . ", `lev`='" . $lev . "' WHERE `catid`=" . intval( $catid_i );
        $db->sql_query( $sql );
        $order = nv_fix_cat_order( $catid_i, $order, $lev );
    }
    $numsubcat = $weight;
    if ( $parentid > 0 )
    {
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `numsubcat`=" . $numsubcat;
        if ( $numsubcat == 0 )
        {
            $sql .= ",`subcatid`='', `viewcat`='viewcat_page_list'";
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
    $result = $db->sql_query( "SHOW CREATE TABLE `" . $db_config['prefix'] . "_" . $module_data . "_rows`" );
    $show = $db->sql_fetchrow( $result );
    $db->sql_freeresult( $result );
    $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
    $sql = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
    $sql = str_replace( $db_config['prefix'] . "_" . $module_data . "_rows", $db_config['prefix'] . "_" . $module_data . "_" . $catid, $sql );
    $db->sql_query( $sql );
    $db->sql_query( "TRUNCATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_" . $catid . "" );
}

function nv_fix_topic ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `topicid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_topics` SET `weight`=" . $weight . " WHERE `topicid`=" . intval( $row['topicid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_fix_block_cat ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `bid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $weight = 0;
    $result = $db->sql_query( $query );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` SET `weight`=" . $weight . " WHERE `bid`=" . intval( $row['bid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_fix_source ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `sourceid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_sources` SET `weight`=" . $weight . " WHERE `sourceid`=" . intval( $row['sourceid'] );
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
        $query = "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` where `bid`='" . $bid . "' ORDER BY `weight` ASC";
        $result = $db->sql_query( $query );
        $weight = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $weight ++;
            if ( $weight <= 500 )
            {
                $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_block` SET `weight`=" . $weight . " WHERE `bid`='" . $bid . "' AND `id`=" . intval( $row['id'] );
            }
            else
            {
                $sql = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` WHERE `bid`='" . $bid . "' AND `id`=" . intval( $row['id'] );
            }
            $db->sql_query( $sql );
        }
        $db->sql_freeresult();
        if ( $repairtable )
        {
            $db->sql_query( "REPAIR TABLE `" . $db_config['prefix'] . "_" . $module_data . "_block`" );
            $db->sql_query( "OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $module_data . "_block`" );
        }
    }
}

function nv_show_cat_list ( $parentid = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_full, $array_viewcat_nosub;
    $contents = "";
    if ( $parentid > 0 )
    {
        $parentid_i = $parentid;
        $array_cat_title = array();
        $a = 0;
        while ( $parentid_i > 0 )
        {
            list( $catid_i, $parentid_i, $title_i ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid`, `parentid`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `catid`=" . intval( $parentid_i ) . "" ) );
            $array_cat_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid_i . "\"><strong>" . $title_i . "</strong></a>";
            $a ++;
        }
        $contents .= "<div class=\"divbor1\">";
        for ( $i = $a - 1; $i >= 0; $i -- )
        {
            $contents .= $array_cat_title[$i];
            if ( $i > 0 ) $contents .= " -> ";
        }
        $contents .= "</div>";
    }
    $sql = "SELECT catid, parentid, " . NV_LANG_DATA . "_title, weight, viewcat, numsubcat, inhome, numlinks FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `parentid` = '" . $parentid . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead style=\"height:24px;\">\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"width:40px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['catalog_name'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:120px;\">" . $lang_module['inhome'] . "</td>\n";
        $contents .= "<td>" . $lang_module['viewcat_page'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:90px;\">" . $lang_module['numlinks'] . "</td>\n";
        $contents .= "<td style=\"width:200px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $array_inhome = array( 
            $lang_global['no'], $lang_global['yes'] 
        );
        while ( list( $catid, $parentid, $title, $weight, $viewcat, $numsubcat, $inhome, $numlinks ) = $db->sql_fetchrow( $result ) )
        {
            $array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
            if ( ! array_key_exists( $viewcat, $array_viewcat ) )
            {
                $viewcat = "viewcat_page_list";
                $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` SET `viewcat`=" . $db->dbescape( $viewcat ) . " WHERE `catid`=" . intval( $catid );
                $db->sql_query( $sql );
            }
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $weight ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid . "\"><strong>" . $title . "</strong></a>";
            if ( $numsubcat > 0 ) $contents .= "  <span style=\"color:#FF0101;\">(" . $numsubcat . ")</span>";
            $contents .= "</td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_inhome_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','inhome');\">\n";
            foreach ( $array_inhome as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $inhome ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"left\"><select id=\"id_viewcat_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','viewcat');\">\n";
            foreach ( $array_viewcat as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $viewcat ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_numlinks_" . $catid . "\" onchange=\"nv_chang_cat('" . $catid . "','numlinks');\">\n";
            for ( $i = 0; $i <= 10; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $numlinks ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"right\">";
            
            $contents .= "<span class=\"add_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;catid=" . $catid . "&parentid=" . $parentid . "\">" . $lang_module['content_add'] . "</a></span>&nbsp;-\n";
            
            $contents .= "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;catid=" . $catid . "&parentid=" . $parentid . "#edit\">" . $lang_global['edit'] . "</a></span>&nbsp;-\n";
            $contents .= "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_cat(" . $catid . ")\">" . $lang_global['delete'] . "</a></span>";
            $contents .= "</td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    $db->sql_freeresult();
    unset( $sql, $result );
    return $contents;
}

function nv_fix_group_order ( $parentid = 0, $order = 0, $lev = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `groupid`, `parentid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $array_group_order = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $array_group_order[] = $row['groupid'];
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
    foreach ( $array_group_order as $groupid_i )
    {
        $order ++;
        $weight ++;
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `weight`=" . $weight . ", `order`=" . $order . ", `lev`='" . $lev . "' WHERE `groupid`=" . intval( $groupid_i );
        $db->sql_query( $sql );
        $order = nv_fix_group_order( $groupid_i, $order, $lev );
    }
    $numsubgroup = $weight;
    if ( $parentid > 0 )
    {
        $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `numsubgroup`=" . $numsubgroup;
        if ( $numsubgroup == 0 )
        {
            $sql .= ",`subgroupid`='', `viewgroup`='viewcat_page_list'";
        }
        else
        {
            $sql .= ",`subgroupid`='" . implode( ",", $array_group_order ) . "'";
        }
        $sql .= " WHERE `groupid`=" . intval( $parentid );
        $db->sql_query( $sql );
    }
    return $order;
}

function nv_show_group_list ( $parentid = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_full, $array_viewcat_nosub;
    $contents = "";
    if ( $parentid > 0 )
    {
        $parentid_i = $parentid;
        $array_group_title = array();
        $a = 0;
        while ( $parentid_i > 0 )
        {
            list( $groupid_i, $parentid_i, $title_i ) = $db->sql_fetchrow( $db->sql_query( "SELECT `groupid`, `parentid`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`=" . intval( $parentid_i ) . "" ) );
            $array_group_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid_i . "\"><strong>" . $title_i . "</strong></a>";
            $a ++;
        }
        $contents .= "<div class=\"divbor1\">";
        for ( $i = $a - 1; $i >= 0; $i -- )
        {
            $contents .= $array_group_title[$i];
            if ( $i > 0 ) $contents .= " -> ";
        }
        $contents .= "</div>";
    }
    $sql = "SELECT groupid, parentid, " . NV_LANG_DATA . "_title, weight, viewgroup, numsubgroup, inhome, numlinks FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `parentid` = '" . $parentid . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead style=\"height:24px;\">\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"width:40px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['catalog_name'] . "</td>\n";
        $contents .= "<td align=\"center\" style=\"width:120px;\">" . $lang_module['inhome'] . "</td>\n";
        $contents .= "<td>" . $lang_module['viewcat_page'] . "</td>\n";
        $contents .= "<td style=\"width:100px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $array_inhome = array( 
            $lang_global['no'], $lang_global['yes'] 
        );
        while ( list( $groupid, $parentid, $title, $weight, $viewgroup, $numsubgroup, $inhome, $numlinks ) = $db->sql_fetchrow( $result ) )
        {
            $array_viewgroup = $array_viewcat_nosub;
            if ( ! array_key_exists( $viewgroup, $array_viewgroup ) )
            {
                $viewgroup = "viewcat_page_list";
                $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `viewgroup`=" . $db->dbescape( $viewgroup ) . " WHERE `groupid`=" . intval( $groupid );
                $db->sql_query( $sql );
            }
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $groupid . "\" onchange=\"nv_chang_group('" . $groupid . "','weight');\">\n";
            for ( $i = 1; $i <= $num; $i ++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $weight ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid . "\"><strong>" . $title . "</strong></a>";
            if ( $numsubgroup > 0 ) $contents .= "  <span style=\"color:#FF0101;\">(" . $numsubgroup . ")</span>";
            $contents .= "</td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_inhome_" . $groupid . "\" onchange=\"nv_chang_group('" . $groupid . "','inhome');\">\n";
            foreach ( $array_inhome as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $inhome ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"left\"><select id=\"id_viewgroup_" . $groupid . "\" onchange=\"nv_chang_group('" . $groupid . "','viewgroup');\">\n";
            foreach ( $array_viewgroup as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $viewgroup ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select><td align=\"right\">";
            $contents .= "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&amp;groupid=" . $groupid . "&parentid=" . $parentid . "#edit\">" . $lang_global['edit'] . "</a></span>&nbsp;-\n";
            $contents .= "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_group(" . $groupid . ")\">" . $lang_global['delete'] . "</a></span>";
            $contents .= "</td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    $db->sql_freeresult();
    unset( $sql, $result );
    return $contents;
}

function nv_show_topics_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $contents = "";
    $sql = "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr align=\"center\">\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td>" . $lang_module['description'] . "</td>\n";
        $contents .= "<td style=\"width:120px;\"></td>\n";
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
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*)  FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `topicid`=" . $row['topicid'] . "" ) );
            $contents .= ( $numnews > 0 ) ? "<td><a href='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicsnews&amp;topicid=" . $row['topicid'] . "'>" . $row['title'] . " ($numnews " . $lang_module['topic_num_news'] . ")</a></td>\n" : "<td><a href='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicsnews&amp;topicid=" . $row['topicid'] . "'>" . $row[NV_LANG_DATA . '_title'] . "</a></td>\n";
            $contents .= "<td>" . $row[NV_LANG_DATA . "_description"] . "</td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topics&amp;topicid=" . $row['topicid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_topic(" . $row['topicid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    $db->sql_freeresult();
    return $contents;
}

function nv_show_block_cat_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $contents = "";
    $sql = "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr align=\"center\">\n";
        $contents .= "<td style=\"width:50px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td style=\"width:40px;\">ID</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td>" . $lang_module['adddefaultblock'] . "</td>\n";
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
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*)  FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` where `bid`=" . $row['bid'] . "" ) );
            if ( $numnews )
            {
                $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=block&amp;bid=" . $row['bid'] . "\">" . $row[NV_LANG_DATA . '_title'] . " ($numnews " . $lang_module['topic_num_news'] . ")</a>";
            }
            else
            {
                $contents .= "<td><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=block&amp;bid=" . $row['bid'] . "\">" . $row[NV_LANG_DATA . '_title'] . "</a>";
            }
            $contents .= " </td>\n";
            $contents .= "<td align=\"center\"><select id=\"id_adddefault_" . $row['bid'] . "\" onchange=\"nv_chang_block_cat('" . $row['bid'] . "','adddefault');\">\n";
            foreach ( $array_adddefault as $key => $val )
            {
                $contents .= "<option value=\"" . $key . "\"" . ( $key == $row['adddefault'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;bid=" . $row['bid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_block_cat(" . $row['bid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
    }
    $db->sql_freeresult();
    return $contents;
}

function nv_show_sources_list ( )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $nv_Request;
    $contents = "";
    $num = $db->sql_numrows( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` ORDER BY `weight` ASC" ) );
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=sources";
    $all_page = ( $num > 1 ) ? $num : 1;
    $per_page = 15;
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    if ( $num > 0 )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr align=\"center\">\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['name'] . "</td>\n";
        $contents .= "<td>" . $lang_module['link'] . "</td>\n";
        $contents .= "<td style=\"width:120px;\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";
        $a = 0;
        $result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` ORDER BY `weight` LIMIT $page, $per_page" );
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
            $contents .= "<td>" . $row[NV_LANG_DATA . '_title'] . "</td>\n";
            $contents .= "<td>" . $row['link'] . "</td>\n";
            $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=sources&amp;sourceid=" . $row['sourceid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_source(" . $row['sourceid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a ++;
        }
        $contents .= "</table>\n";
        $contents .= nv_generate_page( $base_url, $all_page, $per_page, $page );
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
        $memory_limit = intval( nv_converttoBytes( ini_get( 'memory_limit' ) ) );
        if ( $memory_limit > 60000000 and file_exists( NV_ROOTDIR . "/includes/keywords/" . NV_LANG_DATA . ".txt" ) )
        {
            //$memory_limit > 57.220MB
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
            $commonly_used_words = "";
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
    
    $global_array_cat = array();
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other";
    $global_array_cat[0] = array( 
        "catid" => 0, "parentid" => 0, "title" => "Other", "alias" => "Other", "link" => $link_i, "viewcat" => "viewcat_page_list", "subcatid" => 0, "numlinks" => 3, "description" => "", "keywords" => "" 
    );
    
    $sql = "SELECT catid, parentid, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, " . NV_LANG_DATA . "_keywords, lev FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
    $result = $db->sql_query( $sql );
    while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
    {
        $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
        $global_array_cat[$catid_i] = array( 
            "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "keywords" => $keywords_i 
        );
    }
    $contents = "<form name=\"block_list\">";
    $contents .= "<table class=\"tab1\">\n";
    $contents .= "<thead>\n";
    $contents .= "<tr align=\"center\">\n";
    $contents .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);\" /></td>\n";
    $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
    $contents .= "<td>" . $lang_module['name'] . "</td>\n";
    $contents .= "<td style=\"width:200px;\"></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $sql = "SELECT t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t2.weight FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` as t1 INNER JOIN `" . $db_config['prefix'] . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.inhome='1' ORDER BY t2.weight ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    $a = 0;
    while ( list( $id, $listcatid, $title, $alias, $weight ) = $db->sql_fetchrow( $result ) )
    {
        $catid_i = end( explode( ",", $listcatid ) );
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td align=\"center\"><input type=\"checkbox\" onclick=\"nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);\" value=\"" . $id . "\" name=\"idcheck[]\"></td>\n";
        $contents .= "<td align=\"center\"><select id=\"id_weight_" . $id . "\" onchange=\"nv_chang_block(" . $bid . ", " . $id . ",'weight');\">\n";
        for ( $i = 1; $i <= $num; $i ++ )
        {
            $contents .= "<option value=\"" . $i . "\"" . ( $i == $weight ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $contents .= "</select></td>\n";
        $contents .= "<td align=\"left\"><a target=\"_blank\" href=\"" . $link . "\">" . $title . "</a></td>\n";
        $contents .= "<td align=\"center\">\n";
        $contents .= "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>\n";
        $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_chang_block(" . $bid . ", " . $id . ",'delete')\">" . $lang_module['delete_from_block'] . "</a></span>\n";
        $contents .= "</td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
        $a ++;
    }
    $contents .= "<tfoot>\n";
    $contents .= "<tr align=\"left\">\n";
    $contents .= "<td colspan=\"5\"><input type=\"button\" onclick=\"nv_del_block_list(this.form, " . $bid . ")\" value=\"" . $lang_module['delete_from_block'] . "\">\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tfoot>\n";
    $contents .= "</table>\n";
    $contents .= "</form>\n";
    $db->sql_freeresult();
    return $contents;
}

function FormatNumber ( $number, $decimals = 0, $thousand_separator = '&nbsp;', $decimal_point = '.' )
{
    $str = number_format( $number, 0, ',', '.' );
    return $str;
}

function drawselect_number ( $select_name = "", $number_start = 0, $number_end = 1, $number_curent = 0, $func_onchange = "" )
{
    $html = "<select name=\"" . $select_name . "\" onchange=\"" . $func_onchange . "\">";
    for ( $i = $number_start; $i < $number_end; $i ++ )
    {
        $select = ( $i == $number_curent ) ? "selected=\"selected\"" : "";
        $html .= "<option value=\"" . $i . "\"" . $select . ">" . $i . "</option>";
    }
    $html .= "</select>";
    return $html;
}

//
function nv_fix_group_count ( $listid )
{
    global $db, $module_name, $module_data, $db_config;
    $array_id = explode( ',', $listid );
    foreach ( $array_id as $id )
    {
        if ( ! empty( $id ) )
        {
            $sql = "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE group_id LIKE '%" . intval( $id ) . ",%' AND status=1 AND publtime <= " . NV_CURRENTTIME . " AND (exptime=0 OR exptime >=" . NV_CURRENTTIME . ")";
            $result = $db->sql_query( $sql );
            list( $num ) = $db->sql_fetchrow( $result );
            $db->sql_freeresult();
            $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_group` SET `numpro`=" . $num . " WHERE `groupid`=" . intval( $id );
            $db->sql_query( $sql );
            $db->sql_freeresult();
            unset( $result );
        }
    }
}
function GetCatidInChild ( $catid )
{
    global $global_array_cat, $array_cat;
    $array_cat[] = $catid;
    if ( $global_array_cat[$catid]['parentid'] > 0 )
    {
    	$array_cat[] = $global_array_cat[$catid]['parentid'];
    	$array_cat_temp = GetCatidInChild( $global_array_cat[$catid]['parentid'] );
        foreach ( $array_cat_temp as $catid_i )
        {
            $array_cat[] = $catid_i;
        }
    }
    return array_unique( $array_cat );
}
?>