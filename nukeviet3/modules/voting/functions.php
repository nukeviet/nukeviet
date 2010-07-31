<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if ( ! in_array( $op, array( 
    'detail', 'result' 
) ) )
{
    define( 'NV_IS_MOD_VOTING', true );
}

if ( ! empty( $array_op ) )
{
    if ( substr( $array_op[0], 0, 7 ) == "result-" )
    {
        $array_page = explode( "-", $array_op[0] );
        $id = intval( end( $array_page ) );
        $op = "result";
    }
}

?>