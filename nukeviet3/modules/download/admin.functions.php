<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */
if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
$submenu['content'] = $lang_module['download_addfile'];
$submenu['cat'] = $lang_module['download_catmanager'];
$submenu['report'] = $lang_module['download_report'];
$submenu['filequeue'] = $lang_module['download_filequeue'];
$submenu['comment'] = $lang_module['download_comment'];
$submenu['config'] = $lang_module['download_config'];
$allow_func = array( 
    'main', 'down', 'filequeue', 'content', 'editfilequeue', 'delfile', 'delfilequeue', 'delfilelist', 'delfilequeuelist', 'report', 'delreport', 'config', 'updateconfig', 'cat', 'ordercat', 'editcat', 'delcat', 'addcat', 'tag', 'comment', 'del_comment', 'active_comment', 'actcat', 'actfile' 
);
define( 'NV_IS_FILE_ADMIN', true );

#get sub cat
function getsubcat ( $parentid, $i )
{
    global $db, $module_name, $module_data, $catparent;
    $sql = $db->sql_query( "SELECT cid,title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid = " . $parentid . " ORDER BY weight" );
    $i .= $i;
    $content = '';
    while ( $subcat = $db->sql_fetchrow( $sql ) )
    {
        $sel = ( $subcat['cid'] == $catparent ) ? ' selected' : '';
        $content .= "<option value='" . $subcat['cid'] . "' " . $sel . ">" . $i . $subcat['title'] . "</option>";
        $content .= getsubcat( $subcat['cid'], $i );
    }
    return $content;
}
?>