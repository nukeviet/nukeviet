<?php

/**
 * @Project NUKEVIET 3.x
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
$sql = "SELECT catid, parentid, title, titlesite, alias, lev, viewcat,numsubcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $title_i, $titlesite_i, $alias_i, $lev_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
{
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "titlesite" => $titlesite_i, "alias" => $alias_i, "numsubcat" => $numsubcat_i, "lev" => $lev_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i 
    );
}

/**
 * nv_fix_cat_order()
 * 
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order ( $parentid = 0, $order = 0, $lev = 0 )
{
    global $db, $module_data;
	
    $sql = "SELECT `catid`, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $array_cat_order = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $array_cat_order[] = $row['catid'];
    }
    $db->sql_freeresult();
    $weight = 0;
    if ( $parentid > 0 )
    {
        ++$lev;
    }
    else
    {
        $lev = 0;
    }
    foreach ( $array_cat_order as $catid_i )
    {
        ++$order;
        ++$weight;
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
/**
 * nv_create_table_rows()
 * 
 * @param mixed $catid
 * @return
 */
function nv_create_table_rows ( $catid )
{
    global $db, $module_data;
    $db->sql_query( "SET SQL_QUOTE_SHOW_CREATE = 1" );
    $result = $db->sql_query( "SHOW CREATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_rows`" );
    $show = $db->sql_fetchrow( $result );
    $db->sql_freeresult( $result );
    $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
    $sql = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
    $sql = str_replace( NV_PREFIXLANG . "_" . $module_data . "_rows", NV_PREFIXLANG . "_" . $module_data . "_" . $catid, $sql );
    $db->sql_query( $sql );
    $db->sql_query( "TRUNCATE TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
}

/**
 * nv_fix_topic()
 * 
 * @return
 */
function nv_fix_topic ( )
{
    global $db, $module_data;
    $sql = "SELECT `topicid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topics` SET `weight`=" . $weight . " WHERE `topicid`=" . intval( $row['topicid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

/**
 * nv_fix_block_cat()
 * 
 * @return
 */
function nv_fix_block_cat ( )
{
    global $db, $module_data;
    $sql = "SELECT `bid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $weight = 0;
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` SET `weight`=" . $weight . " WHERE `bid`=" . intval( $row['bid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

/**
 * nv_fix_source()
 * 
 * @return
 */
function nv_fix_source ( )
{
    global $db, $module_data;
    $sql = "SELECT `sourceid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_sources` SET `weight`=" . $weight . " WHERE `sourceid`=" . intval( $row['sourceid'] );
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

/**
 * nv_news_fix_block()
 * 
 * @param mixed $bid
 * @param bool $repairtable
 * @return
 */
function nv_news_fix_block ( $bid, $repairtable = true )
{
    global $db, $module_data;
    $bid = intval( $bid );
    if ( $bid > 0 )
    {
        $sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` where `bid`='" . $bid . "' ORDER BY `weight` ASC";
        $result = $db->sql_query( $sql );
        $weight = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            ++$weight;
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

/**
 * nv_show_cat_list()
 * 
 * @param integer $parentid
 * @return
 */
function nv_show_cat_list ( $parentid = 0 )
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $array_viewcat_full, $array_viewcat_nosub, $array_cat_admin, $global_array_cat, $admin_id, $global_config, $module_file;

	$xtpl = new XTemplate( "cat_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
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
		
		$xtpl->assign( 'CAT_TITLE', implode( " &raquo; ", $array_cat_title ) );
		$xtpl->parse( 'main.cat_title' );
    }
    
    $sql = "SELECT `catid`, `parentid`, `title`, `weight`, `viewcat`, `numsubcat`, `inhome`, `numlinks` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid` = '" . $parentid . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
	
    if ( $num > 0 )
    {
        $a = 1;
        $array_inhome = array( $lang_global['no'], $lang_global['yes'] );
        
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
				
				$xtpl->assign( 'ROW', array(
					"class" => ( $a % 2 ) ? " class=\"second\"" : "",
					"catid" => $catid,
					"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid,
					"title" => $title,
					"adminfuncs" => implode( "&nbsp;-&nbsp;", $admin_funcs ),
				) );
                
                if ( $weight_disabled )
                {
					$xtpl->assign( "STT", $a );
					$xtpl->parse( 'main.data.loop.stt' );
                }
                else
                {
					for ( $i = 1; $i <= $num; ++ $i )
					{
						$xtpl->assign( 'WEIGHT', array( "key" => $i, "title" => $i, "selected" => $i == $weight ? " selected=\"selected\"" : "" ) );
						$xtpl->parse( 'main.data.loop.weight.loop' );
					}
					$xtpl->parse( 'main.data.loop.weight' );
                }
				
                if ( $func_cat_disabled )
                {
					$xtpl->assign( "INHOME", $array_inhome[$inhome] );
					$xtpl->parse( 'main.data.loop.disabled_inhome' );
					
					$xtpl->assign( "VIEWCAT", $array_viewcat[$viewcat] );
					$xtpl->parse( 'main.data.loop.disabled_viewcat' );
					
					$xtpl->assign( "NUMLINKS", $numlinks );
					$xtpl->parse( 'main.data.loop.title_numlinks' );
                }
                else
                {
                    foreach ( $array_inhome as $key => $val )
					{
						$xtpl->assign( 'INHOME', array( "key" => $key, "title" => $val, "selected" => $key == $inhome ? " selected=\"selected\"" : "" ) );
						$xtpl->parse( 'main.data.loop.inhome.loop' );
					}
					$xtpl->parse( 'main.data.loop.inhome' );
					
                    foreach ( $array_viewcat as $key => $val )
                    {
						$xtpl->assign( 'VIEWCAT', array( "key" => $key, "title" => $val, "selected" => $key == $viewcat ? " selected=\"selected\"" : "" ) );
						$xtpl->parse( 'main.data.loop.viewcat.loop' );
                    }
					$xtpl->parse( 'main.data.loop.viewcat' );
					
                    for ( $i = 0; $i <= 10; ++ $i )
                    {
						$xtpl->assign( 'NUMLINKS', array( "key" => $i, "title" => $i, "selected" => $i == $numlinks ? " selected=\"selected\"" : "" ) );
						$xtpl->parse( 'main.data.loop.numlinks.loop' );
                    }
					$xtpl->parse( 'main.data.loop.numlinks' );
                }
				
				if( $numsubcat )
				{
					$xtpl->assign( 'NUMSUBCAT', $numsubcat );
					$xtpl->parse( 'main.data.loop.numsubcat' );
				}
				
				$xtpl->parse( 'main.data.loop' );
                ++ $a;
            }
        }

		$xtpl->parse( 'main.data' );
    }
    else
    {
        $contents = "&nbsp;";
    }
    $db->sql_freeresult();
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

    return $contents;
}

/**
 * nv_show_topics_list()
 * 
 * @return
 */
function nv_show_topics_list ( )
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $global_config, $module_file;
	
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
	
	$xtpl = new XTemplate( "topics_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
    if ( $num > 0 )
    {
        $a = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*)  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `topicid`=" . $row['topicid'] . "" ) );
			
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"topicid" => $row['topicid'],
				"description" => $row['description'],
				"title" => $row['title'],
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topicsnews&amp;topicid=" . $row['topicid'],
				"numnews" => $numnews ? " (" . $numnews . " " . $lang_module['topic_num_news'] . ")" : "",
				"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topics&amp;topicid=" . $row['topicid'] . "#edit",
			) );
			
            for ( $i = 1; $i <= $num; ++ $i )
            {
				$xtpl->assign( 'WEIGHT', array( "key" => $i, "title" => $i, "selected" => $i == $row['weight'] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.weight' );
            }
			
			$xtpl->parse( 'main.loop' );
            ++ $a;
        }
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
    }
    else
    {
        $contents = "&nbsp;";
    }
    $db->sql_freeresult();
    return $contents;
}

/**
 * nv_show_block_cat_list()
 * 
 * @return
 */
function nv_show_block_cat_list ( )
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $op, $module_file, $global_config;
	
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
	
	$xtpl = new XTemplate( "blockcat_lists.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
    if ( $num > 0 )
    {
        $a = 0;
        $array_adddefault = array( $lang_global['no'], $lang_global['yes'] );
		
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            list( $numnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*)  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` where `bid`=" . $row['bid'] ) );
			
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"bid" => $row['bid'],
				"title" => $row['title'],
				"numnews" => $numnews ? " (" . $numnews . " " . $lang_module['topic_num_news'] . ")" : "",
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=block&amp;bid=" . $row['bid'],
				"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;bid=" . $row['bid'] . "#edit",
			) );
			
            for( $i = 1; $i <= $num; ++ $i )
            {
				$xtpl->assign( 'WEIGHT', array( "key" => $i, "title" => $i, "selected" => $i == $row['weight'] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.weight' );
            }
			
			foreach( $array_adddefault as $key => $val )
			{
				$xtpl->assign( 'ADDDEFAULT', array( "key" => $key, "title" => $val, "selected" => $key == $row['adddefault'] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.adddefault' );
			}
			
			for( $i = 1; $i <= 30; ++ $i )
			{
				$xtpl->assign( 'NUMBER', array( "key" => $i, "title" => $i, "selected" => $i == $row['number'] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.number' );
			}
			
			$xtpl->parse( 'main.loop' );
            ++ $a;
        }
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
    }
    else
    {
        $contents = "&nbsp;";
    }
	
    $db->sql_freeresult();
    return $contents;
}

/**
 * nv_show_sources_list()
 * 
 * @return
 */
function nv_show_sources_list ( )
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;
	
    $num = $db->sql_numrows( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` ASC" ) );
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&amp;" . NV_OP_VARIABLE . "=sources";
    $all_page = ( $num > 1 ) ? $num : 1;
    $per_page = 15;
    $page = $nv_Request->get_int( 'page', 'get', 0 );

	$xtpl = new XTemplate( "sources_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
    if ( $num > 0 )
    {
        $a = 0;
        $result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` LIMIT $page, $per_page" );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"sourceid" => $row['sourceid'],
				"title" => $row['title'],
				"link" => $row['link'],
				"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=sources&amp;sourceid=" . $row['sourceid'] . "#edit",
			) );
			
            for ( $i = 1; $i <= $num; ++ $i )
            {
				$xtpl->assign( 'WEIGHT', array( "key" => $i, "title" => $i, "selected" => $i == $row['weight'] ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.weight' );
            }
			
			$xtpl->parse( 'main.loop' );
            ++ $a;
        }
		
		$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.generate_page' );
		}
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
    }
    else
    {
        $contents = "&nbsp;";
    }
	
    $db->sql_freeresult();
	
    return $contents;
}

/**
 * nv_show_block_list()
 * 
 * @param mixed $bid
 * @return
 */
function nv_show_block_list ( $bid )
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $module_file, $global_config;
	
	$xtpl = new XTemplate( "block_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'BID', $bid );
    
    $global_array_cat[0] = array( "alias" => "Other" );
    
    $sql = "SELECT t1.id, t1.catid, t1.title, t1.alias, t2.weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.status='1' ORDER BY t2.weight ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );
    if ( $num > 0 )
    {
        $a = 0;
        while ( list( $id, $catid_i, $title, $alias, $weight ) = $db->sql_fetchrow( $result ) )
        {
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"id" => $id,
				"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id,
				"title" => $title,
			) );
			
            for ( $i = 1; $i <= $num; ++ $i )
            {
				$xtpl->assign( 'WEIGHT', array( "key" => $i, "title" => $i, "selected" => $i == $weight ? " selected=\"selected\"" : "" ) );
				$xtpl->parse( 'main.loop.weight' );
            }
			
			$xtpl->parse( 'main.loop' );
            ++ $a;
        }
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
		
        $db->sql_freeresult();
    }
    else
    {
        $contents = "&nbsp;";
    }
    return $contents;
}

/**
 * GetCatidInParent()
 * 
 * @param mixed $catid
 * @return
 */
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

/**
 * nv_array_cat_admin()
 * 
 * @return
 */
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

/**
 * redriect()
 * 
 * @param string $msg1
 * @param string $msg2
 * @param mixed $nv_redirect
 * @return
 */
function redriect ( $msg1 = "", $msg2 = "", $nv_redirect )
{
	global $global_config, $module_file, $module_name;
	$xtpl = new XTemplate( "redriect.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	
	if ( empty( $nv_redirect ) )
	{
		$nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
	}
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_REDIRECT', $nv_redirect );
	$xtpl->assign( 'MSG1', $msg1 );
	$xtpl->assign( 'MSG2', $msg2 );
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

?>