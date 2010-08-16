<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_NEWS' ) )
{
    die( 'Stop!!!' );
}

$alias_cat_url = $array_op[1];
$array_page = explode( "-", $array_op[2] );
$id = intval( end( $array_page ) );
$catid = 0;
foreach ( $global_array_cat as $catid_i => $array_cat_i )
{
    if ( $alias_cat_url == $array_cat_i['alias'] )
    {
        $catid = $catid_i;
        break;
    }
}
if ( $id > 0 and $catid > 0 )
{
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `id` ='" . $id . "' AND `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")";
    $result = $db->sql_query( $sql );
    $content = $db->sql_fetchrow( $result );
    unset( $sql, $result );
    if ( $content['id'] > 0 )
    {
        $sql = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid` = '" . $content['sourceid'] . "'";
        $result = $db->sql_query( $sql );
        list( $sourcetext ) = $db->sql_fetchrow( $result );
        unset( $sql, $result );
        $result = "";
        $img = "";
        if ( $content['allowed_print'] == 1 )
        {
            $link = $global_config['site_url'] . '/' . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $content['alias'] . "-" . $id . "";
            $link = "<a href=\"" . $link . "\" title=\"" . $content['title'] . "\">" . $link . "</a>\n";
            
            if ( $content['imgposition'] == 1 )
            {
                $homeimg = explode( "|", $content['homeimgthumb'] );
                $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimg[0] );
                if ( $size[0] > 0 )
                {
                    $width = $size[0];
                    $height = $size[1];
                    $alt = $content['homeimgalt'] ? $content['homeimgalt'] : $content['title'];
                    $note = $content['homeimgalt'];
                    $src = $global_config['site_url'] . "/" . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimg[0];
                    $img = array( 
                        "width" => $width, "height" => $height, "alt" => $alt, "note" => $note, "src" => $src, "position" => $content['imgposition'] 
                    );
                }
            }
            elseif ( $content['imgposition'] == 2 )
            {
                $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $content['homeimgfile'] );
                if ( $size[0] > 0 )
                {
                    $imagefull = $module_config[$module_name]['imagefull'];
                    if ( $size[0] > $imagefull )
                    {
                        $size[1] = round( ( $imagefull / $size[0] ) * $size[1] );
                        $size[0] = $imagefull;
                    }
                    $width = $size[0];
                    $height = $size[1];
                    $alt = $content['homeimgalt'] ? $content['homeimgalt'] : $content['title'];
                    $note = $content['homeimgalt'];
                    $src = $global_config['site_url'] . "/" . NV_UPLOADS_DIR . '/' . $module_name . '/' . $content['homeimgfile'];
                    $img = array( 
                        "width" => $width, "height" => $height, "alt" => $alt, "note" => $note, "src" => $src, "position" => $content['imgposition'] 
                    );
                }
            }
            $resuilt = array( 
                "url" => $global_config['site_url'], "meta_tags" => nv_html_meta_tags(), "sitename" => $global_config['site_name'], "title" => $content['title'], "alias" => $content['alias'], "image" => $img, "position" => $content['imgposition'], "time" => nv_date( "l - d/m/Y  H:i", $content['publtime'] ), "print" => $lang_module['news_print'], "close" => $lang_module['news_print_close'], "hometext" => $content['hometext'], "bodytext" => $content['bodytext'], "copyright" => $content['copyright'], "copyvalue" => $module_config[$module_name]['copyright'], "link" => $link, "lang_link" => $lang_module['news_print_link'], "contact" => $global_config['site_email'], "lang_author" => $lang_module['author'], "author" => $content['author'], "lang_source" => $lang_module['news_source'], "source" => $sourcetext 
            );
            $contents = call_user_func( "news_print", $resuilt );
            header( "Content-Type: text/x-delimtext; name=\"" . $resuilt['alias'] . ".html\"" );
            header( "Content-disposition: attachment; filename=" . $resuilt['alias'] . ".html" );
            include ( NV_ROOTDIR . "/includes/header.php" );
            echo $contents;
            include ( NV_ROOTDIR . "/includes/footer.php" );
        }
    }
}
header( "Location: " . $global_config['site_url'] );
exit();
?>