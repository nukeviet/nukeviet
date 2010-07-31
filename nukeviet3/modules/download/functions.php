<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_DOWNLOAD', true );

// initial config data
$sql = "SELECT name,value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query( $sql );
while ( $data = $db->sql_fetch_assoc( $result ) )
{
    $configdownload[$data['name']] = $data['value'];
}

#--
function adminlink ( $id )
{
    global $lang_module, $module_name;
    $link = "<span class=\"delete_icon\" style=\"width:30px\"><a class=\"delfile\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delfile&amp;id=" . $id . "\">" . $lang_module['delete'] . "</a></span>";
    $link .= "<span class=\"edit_icon\" style=\"width:30px\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_module['edit'] . "</a></span>";
    return $link;
}

#get sub cat
function getsubcat ( $parentid, $i )
{
    global $db, $module_name, $module_data, $catparent;
    $sql = $db->sql_query( "SELECT cid,title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid = " . $parentid . " ORDER BY weight" );
    $i .= $i;
    while ( $subcat = $db->sql_fetchrow( $sql ) )
    {
        $sel = ( $subcat['cid'] == $catparent ) ? ' selected' : '';
        $content .= "<option value='" . $subcat['cid'] . "' " . $sel . ">" . $i . $subcat['title'] . "</option>";
        $content .= getsubcat( $subcat['cid'], $i );
    
    }
    return $content;
}

function trimtext ( $id, $title, $length )
{
    if ( strlen( $title ) > $length )
    {
        $maintitle = $title;
        $title = substr( $title, 0, $length );
        $determine = strrpos( $title, ' ' );
        $title = substr( $title, 0, $determine );
        return $title . '...';
    }
    else
        return $title;
}

?>