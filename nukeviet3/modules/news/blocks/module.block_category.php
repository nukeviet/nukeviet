<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
global $global_array_cat, $module_name;
$subcat = array();
$cat = array();

$subcat[0] = array();

foreach ( $global_array_cat as $key => $array_subcat )
{
    $parentid = $array_subcat['parentid'];
    $catid = $array_subcat['catid'];
    if ( $parrentid = $catid and $parentid != 0 )
    {
        $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $array_subcat['alias'];
        $subcat[$parentid][] = array( 
            'catid' => $catid, 'title' => $array_subcat['title'], 'link' => $link 
        );
    }
}
foreach ( $global_array_cat as $catid => $array_cat )
{
    $catid_i = $array_cat['catid'];
    if ( $array_cat['parentid'] == 0 and $catid_i != 0 )
    {
        $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $array_cat['alias'];
        $cat[] = array( 
            'catid' => $catid_i, 'title' => $array_cat['title'], 'link' => $link, 'sub' => $subcat[$catid_i] 
        );
    }
}
$content = nv_category( $cat );
?>