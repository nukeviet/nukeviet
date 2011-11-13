<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
function _substr($str, $length, $minword = 3)
{
    $sub = '';
    $len = 0;
   
    foreach (explode(' ', $str) as $word)
    {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
       
        if (isset($word{$minword}) && isset($sub{$length-1}))
        {
            break;
        }
    }
   
    return $sub . ((isset($str{$len})) ? '...' : '');
}
?>