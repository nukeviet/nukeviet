<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_FAQ', true );

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
                $s_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $s_c['alias'];
                $sub_menu[] = array( $s_c['title'], $s_link, $s_act );
            }
        }

        $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias'];
        $nv_vertical_menu[] = array( $c['title'], $link, $act, 'submenu' => $sub_menu );
    }
}
//Het Xac dinh menu

/**
 * nv_set_allow()
 * 
 * @param mixed $who
 * @param mixed $groups
 * @return
 */
function nv_set_allow( $who, $groups )
{
    global $user_info;

    if ( ! $who or ( $who == 1 and defined( 'NV_IS_USER' ) ) or ( $who == 2 and defined( 'NV_IS_ADMIN' ) ) )
    {
        return true;
    } elseif ( $who == 3 and ! empty( $groups ) and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups ) )
    {
        return true;
    }

    return false;
}

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
        $name = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
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
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list2[$row['id']]['alias'] . "\">" . $list2[$row['id']]['name'] . "</a>";
                }

                if ( $row['parentid'] )
                {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];

                    $list2[$row['id']]['name'] = nv_setcats( $row['parentid'], $list, $list2[$row['id']]['name'], $is_parentlink );
                }

                if ( $is_parentlink )
                {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $module_info['custom_title'] . "</a> &raquo; " . $list2[$row['id']]['name'];
                }
            }
        }
    }

    return $list2;
}

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

    require_once ( NV_ROOTDIR . '/includes/class/keywords.class.php' );

    $keys = new keywords( NV_SITEWORDS_MIN_WORD_LENGTH, NV_SITEWORDS_MIN_WORD_OCCUR, NV_SITEWORDS_MIN_2WORDS_LENGTH, NV_SITEWORDS_MIN_2WORDS_PHRASE_LENGTH, NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR, SITEWORDS_MIN_3WORDS_LENGTH, NV_SITEWORDS_MIN_3WORDS_PHRASE_LENGTH, NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR, "", NV_SITEWORDS_MAX_STRLEN );
    $keywords = $keys->getkeywords( $content );

    if ( ! empty( $keywords ) )
    {
        $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `keywords`=" . $db->dbescape( $keywords ) . " WHERE `id`=" . $catid );
    }

    return $keywords;
}

?>