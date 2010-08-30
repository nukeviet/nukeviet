<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_ABOUT', true );

$tmp_id = 0;
$tmp_alias_url = "";

unset( $matches );
if ( ! empty( $array_op ) and preg_match( "/^([a-z0-9\-]+)\-([0-9]+)$/i", $array_op[0], $matches ) )
{
    $tmp_id = $matches[2];
    $tmp_alias_url = $matches[1];
}

$abouts = array();
$ab_links = array();
$id = 0;
$a = 0;
$sql = $db->sql_query( "SELECT `id`,`title`,`alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY `weight` ASC" );
while ( $row = $db->sql_fetchrow( $sql ) )
{
    $a ++;
    if ( $a == 1 )
    {
        $id = $row['id'];
    }
    $abouts[$row['id']] = array(  //
        'title' => $row['title'], //
		'alias' => $row['alias'], //
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'] . "-" . $row['id'], //
		'act' => 0  //
    );
}

if ( isset( $abouts[$tmp_id] ) and $abouts[$tmp_id]['alias'] == $tmp_alias_url )
{
    $abouts[$tmp_id]['act'] = 1;
    $id = $tmp_id;
}
elseif ( $id )
{
    $abouts[$id]['act'] = 1;
}

if ( count( $abouts ) > 1 )
{
    foreach ( $abouts as $about )
    {
        $nv_vertical_menu[] = array( 
            $about['title'], $about['link'], $about['act'] 
        );
        if ( ! $about['act'] )
        {
            $ab_links[] = array( 
                "title" => $about['title'], "title" => $about['title'], "link" => $about['link'] 
            );
        }
    }
}

?>