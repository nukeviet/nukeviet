<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_FAQ', true );

/**
 * nv_setcats()
 * 
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @param mixed $is_parentlink
 * @return
 */
function nv_setcats( $id, $list, $name, $is_parentlink )
{
    global $module_name;

    if ( $is_parentlink )
    {
        $name = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
    }
    else
    {
        $name = $list[$id]['title'] . " &raquo; " . $name;
    }
    $parentid = $list[$id]['parentid'];
    if ( $parentid )
    {
        $name = nv_setcats( $parentid, $list, $name, $is_parentlink );
    }

    return $name;
}

/**
 * nv_list_cats()
 * 
 * @param bool $is_link
 * @param bool $is_parentlink
 * @return
 */
function nv_list_cats( $is_link = false, $is_parentlink = true )
{
    global $db, $module_data, $module_name, $module_info;

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `status`=1 ORDER BY `parentid`,`weight` ASC";
    $result = $db->sql_query( $sql );

    $list = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        if ( nv_set_allow( $row['who_view'], $row['groups_view'] ) )
        {
            $list[$row['id']] = array( //
                'id' => ( int )$row['id'], //
                'title' => $row['title'], //
                'alias' => $row['alias'], //
                'description' => $row['description'], //
                'parentid' => ( int )$row['parentid'], //
                'subcats' => array(), //
                'keywords' => $row['keywords'] //
                );
        }
    }

    $list2 = array();

    if ( ! empty( $list ) )
    {
        foreach ( $list as $row )
        {
            if ( ! $row['parentid'] or isset( $list[$row['parentid']] ) )
            {
                $list2[$row['id']] = $list[$row['id']];
                $list2[$row['id']]['name'] = $list[$row['id']]['title'];
                if ( $is_link )
                {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list2[$row['id']]['alias'] . "\">" . $list2[$row['id']]['name'] . "</a>";
                }

                if ( $row['parentid'] )
                {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];

                    $list2[$row['id']]['name'] = nv_setcats( $row['parentid'], $list, $list2[$row['id']]['name'], $is_parentlink );
                }

                if ( $is_parentlink )
                {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $module_info['custom_title'] . "</a> &raquo; " . $list2[$row['id']]['name'];
                }
            }
        }
    }

    return $list2;
}

/**
 * initial_config_data()
 * 
 * @return
 */
function initial_config_data ( )
{
    global $module_data;
    
    $sql = "SELECT `config_name`,`config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
    
    $list = nv_db_cache( $sql );
    
    $module_setting = array();
    foreach ( $list as $values )
    {
        $module_setting[$values['config_name']] = $values['config_value'];
    }
                        
    return $module_setting;
}

$module_setting = initial_config_data ( );

/**
 * update_keywords()
 * 
 * @param mixed $catid
 * @param mixed $faq
 * @return
 */
function update_keywords( $catid, $faq )
{
    global $db, $module_data;

    $content = array();
    foreach ( $faq as $row )
    {
        $content[] = $row['title'] . " " . $row['question'] . " " . $row['answer'];
    }

    $content = implode( " ", $content );

    $keywords = nv_get_keywords( $content );

    if ( ! empty( $keywords ) )
    {
        $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `keywords`=" . $db->dbescape( $keywords ) . " WHERE `id`=" . $catid );
    }

    return $keywords;
}

$alias = "";
if ( ! empty( $array_op ) )
{
    $alias = isset( $array_op[0] ) ? $array_op[0] : "";
}

$list_cats = nv_list_cats( true );

// Xac dinh ID cua chu de
$catid = 0;
foreach ( $list_cats as $c )
{
    if ( $c['alias'] == $alias )
    {
        $catid = intval( $c['id'] );
        break;
    }
}
//Het Xac dinh ID cua chu de

//Xac dinh menu
$nv_vertical_menu = array();

//Xac dinh RSS
if ($module_info['rss'])
{
	$rss[] = array( //
	    'title' => $module_info['custom_title'], //
	    'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss" //
	);
}

foreach ( $list_cats as $c )
{
    if ( $c['parentid'] == 0 )
    {
        $sub_menu = array();
        $act = ( $c['id'] == $catid ) ? 1 : 0;
        if ( $act or ( $catid > 0 and $c['id'] == $list_cats[$catid]['parentid'] ) )
        {
            foreach ( $c['subcats'] as $catid_i )
            {
                $s_c = $list_cats[$catid_i];
                $s_act = ( $s_c['alias'] == $alias ) ? 1 : 0;
                $s_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $s_c['alias'];
                $sub_menu[] = array( $s_c['title'], $s_link, $s_act );
            }
        }

        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias'];
        $nv_vertical_menu[] = array( $c['title'], $link, $act, 'submenu' => $sub_menu );
    }
    if ($module_info['rss'])
	{
	    $rss[] = array( //
	        'title' => $module_info['custom_title'] . ' - ' . $c['title'], //
	        'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $c['alias'] //
	    );
	}
}
//Het Xac dinh menu
//Het Xac dinh RSS


?>