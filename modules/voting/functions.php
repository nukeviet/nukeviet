<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if ( ! in_array( $op, array( 'detail', 'result' ) ) )
{
    define( 'NV_IS_MOD_VOTING', true );
}

if ( ! empty( $array_op ) )
{
    unset( $matches );
    if ( preg_match( "/^result\-([0-9]+)$/", $array_op[0], $matches ) )
    {
        $id = ( int )$matches[1];
        $op = "result";
    }
}

?>