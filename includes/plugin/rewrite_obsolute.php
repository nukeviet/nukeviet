<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 07 Mar 2015 03:43:56 GMT
 */
 
//plugin rewrite obsolute url 

foreach ($rewrite_values as $key => $value)
{
	$rewrite_values[$key] = str_replace('"\\1', '"'.NV_MY_DOMAIN.'\\1', $value);
}