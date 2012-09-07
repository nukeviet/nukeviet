<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

global $pro_config;
$pro_config = $module_config[$module_name];
if ( ! empty( $pro_config ) )
{
    $temp = explode( "x", $pro_config['image_size'] );
    $pro_config['homewidth'] = $temp[0];
    $pro_config['homeheight'] = $temp[1];
    $pro_config['blockwidth'] = $temp[0];
    $pro_config['blockheight'] = $temp[1];
}
if ( empty( $pro_config['format_order_id'] ) )
{
    $pro_config['format_order_id'] = strtoupper( $module_name ) . "%d";
}

// lay ty gia ngoai te
global $money_config;
$money_config = array();
$sql = "SELECT `id` , `code` , `currency` , `exchange`  FROM `" . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "`";
$result = $db->sql_query( $sql );
while ( list( $id_i, $code_i, $currency_i, $exchange_i ) = $db->sql_fetchrow( $result ) )
{
    $is_config = ( $code_i == $pro_config['money_unit'] ) ? 1 : 0;
    $money_config[$code_i] = array( 
        'code' => $code_i, 'currency' => $currency_i, 'exchange' => $exchange_i, "is_config" => $is_config 
    );
}

/////////////////////////////////////////////////////////////////////
global $value_email;
$value_email = array();
$value_email['vinades'] = "dinhpc.it@gmail.com";
$value_email['nganluong'] = "dinhpc86@gmail.com";

function nv_comment_module ( $id, $page )
{
    global $db, $module_name, $module_data, $global_config, $module_config, $per_page_comment;
    $comment_array = array();
    list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
    $all_page = ( $numf ) ? $numf : 1;
    $per_page = $per_page_comment;
    $sql = "SELECT `content`, `post_time`, `post_name`, `post_email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`= '" . $id . "' AND `status`=1 ORDER BY `id` ASC LIMIT " . $page . "," . $per_page . "";
    $comment = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $comment ) )
    {
        $row['post_email'] = ( $module_config[$module_name]['emailcomm'] ) ? $row['post_email'] : "";
        $comment_array[] = array( 
            "content" => $row['content'], "post_time" => $row['post_time'], "post_name" => $row['post_name'], "post_email" => $row['post_email'] 
        );
    }
    $db->sql_freeresult( $comment );
    unset( $row, $comment );
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;id=" . $id . "&checkss=" . md5( $id . session_id() . $global_config['sitekey'] );
    $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'showcomment' );
    return array( 
        "comment" => $comment_array, "page" => $generate_page 
    );
}

function nv_del_content_module ( $id )
{
    global $db, $module_name, $module_data, $title, $db_config;
    $content_del = "NO_" . $id;
    $title = "";
    list( $id, $listcatid, $title, $homeimgfile, $homeimgthumb,$group_id ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id`, `listcatid`, `" . NV_LANG_DATA . "_title`, `homeimgfile`, `homeimgthumb`,`group_id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . intval( $id ) . "" ) );
    if ( $id > 0 )
    {
        if ( $homeimgfile != "" or $homeimgthumb != "" )
        {
            $homeimgfile .= "|" . $homeimgthumb;
            $homeimgfile_arr = explode( "|", $homeimgfile );
            foreach ( $homeimgfile_arr as $homeimgfile_i )
            {
                if ( ! empty( $homeimgfile_i ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i ) )
                {
                    @nv_deletefile( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i );
                }
            }
        }
        $number_no_del = 0;
        $array_catid = explode( ",", $listcatid );
        if ( $number_no_del == 0 )
        {
            $query = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . $id;
            $db->sql_query( $query );
            if ( ! $db->sql_affectedrows() )
            {
                {
                    $number_no_del ++;
                }
                $db->sql_freeresult();
            }
        }
        if ( $number_no_del == 0 )
        {
            $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_comments` WHERE `id` = " . $id );
            $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` WHERE `id` = " . $id );
            $content_del = "OK_" . $id;
            nv_fix_group_count ( $group_id );
        }
        else
        {
            $content_del = "ERR_" . $lang_module['error_del_content'];
        }
    }
    return $content_del;
}

function nv_archive_content_module ( $id, $listcatid )
{
    global $db, $module_data;
    $array_catid = explode( ",", $listcatid );
    foreach ( $array_catid as $catid_i )
    {
        $catid_i = intval( $catid_i );
        if ( $catid_i > 0 )
        {
            $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `archive`='2' WHERE `id`=" . $id . "" );
        }
    }
    $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `archive`='2' WHERE `id`=" . $id . "" );
}

function nv_link_edit_page ( $id )
{
    global $lang_global, $module_name;
    $link = "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>";
    return $link;
}

function nv_link_delete_page ( $id )
{
    global $lang_global, $module_name;
    $link = "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5( $id . session_id() ) . "','" . NV_BASE_ADMINURL . "')\">" . $lang_global['delete'] . "</a></span>";
    return $link;
}

function nv_products_page ( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true )
{
    global $lang_global;
    $total_pages = ceil( $num_items / $per_page );
    if ( $total_pages == 1 ) return '';
    @$on_page = floor( $start_item / $per_page ) + 1;
    $page_string = "";
    if ( $total_pages > 10 )
    {
        $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
        for ( $i = 1; $i <= $init_page_max; $i ++ )
        {
            $href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
            $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
            if ( $i < $init_page_max ) $page_string .= " ";
        }
        if ( $total_pages > 3 )
        {
            if ( $on_page > 1 && $on_page < $total_pages )
            {
                $page_string .= ( $on_page > 5 ) ? " ... " : ", ";
                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
                for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i ++ )
                {
                    $href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
                    $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
                    if ( $i < $init_page_max + 1 )
                    {
                        $page_string .= " ";
                    }
                }
                $page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
            }
            else
            {
                $page_string .= " ... ";
            }
            
            for ( $i = $total_pages - 2; $i < $total_pages + 1; $i ++ )
            {
                $href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
                $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
                if ( $i < $total_pages )
                {
                    $page_string .= " ";
                }
            }
        }
    }
    else
    {
        for ( $i = 1; $i < $total_pages + 1; $i ++ )
        {
            $href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
            $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
            if ( $i < $total_pages )
            {
                $page_string .= " ";
            }
        }
    }
    if ( $add_prevnext_text )
    {
        if ( $on_page > 1 )
        {
            $href = "href=\"" . $base_url . "/page-" . ( ( $on_page - 2 ) * $per_page ) . "\"";
            $page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
        }
        if ( $on_page < $total_pages )
        {
            $href = "href=\"" . $base_url . "/page-" . ( $on_page * $per_page ) . "\"";
            $page_string .= "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
        }
    }
    return $page_string;
}

function nv_file_table ( $table )
{
    global $db_config, $db;
    $lang_value = nv_list_lang();
    $arrfield = array();
    $result = $db->sql_query( "SHOW COLUMNS FROM " . $table . "" );
    while ( list( $field ) = $db->sql_fetchrow( $result ) )
    {
        $tmp = explode( "_", $field );
        foreach ( $lang_value as $lang_i )
        {
            if ( ! empty( $tmp[0] ) && ! empty( $tmp[1] ) )
            {
                if ( $tmp[0] == $lang_i )
                {
                    $arrfield[] = array( 
                        $tmp[0], $tmp[1] 
                    );
                    break;
                }
            }
        }
    }
    return $arrfield;
}

function nv_list_lang ( )
{
    global $db_config, $db;
    $re = $db->sql_query( "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE setup=1" );
    $lang_value = array();
    while ( list( $lang_i ) = $db->sql_fetchrow( $re ) )
    {
        $lang_value[] = $lang_i;
    }
    return $lang_value;
}
if ( ! function_exists( 'nv_insert_logs' ) )
{

    function nv_insert_logs ( $lang = "", $module_name = "", $name_key = "", $note_action = "", $userid = 0, $link_acess = "" )
    {
        return "";
    }
}

/*******
tru so luong trong kho $type = "-"
cong so luong trong kho $type = "+"
$listid : danh sach cac id product
$listnum : danh sach so luong tuong ung
 ********/
function product_number_order ( $listid, $listnum, $type = "-" )
{
    global $db_config, $db, $module_data;
    $arrayid = explode( "|", $listid);
    $arraynum = explode( "|", $listnum );
    $i = 0;
    foreach ( $arrayid as $id )
    {
    	if ($id > 0)
    	{
        	if ( empty( $arraynum[$i] ) ) $arraynum[$i] = 0;
        	$query = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET 
                      `product_number` = `product_number` ".$type." ". intval( $arraynum[$i] ) . " WHERE `id` =" . $id . "";
            $db->sql_query( $query );
    	}
        $i++;
    }
}
?>