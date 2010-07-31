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

?>