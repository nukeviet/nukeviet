<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

global $pro_config;
$pro_config = $module_config[$module_name];
if (!empty($pro_config))
{
    $temp = explode("x", $pro_config['image_size']);
    $pro_config['homewidth'] = $temp[0];
    $pro_config['homeheight'] = $temp[1];
    $pro_config['blockwidth'] = $temp[0];
    $pro_config['blockheight'] = $temp[1];
}
if (empty($pro_config['format_order_id']))
{
    $pro_config['format_order_id'] = strtoupper($module_name) . "%d";
}

// lay ty gia ngoai te
global $money_config;
$sql = "SELECT `id` , `code` , `currency` , `exchange`  FROM `" . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "`";
$money_config = nv_db_cache($sql, 'code', $module_name);

function nv_comment_module($id, $page)
{
    global $db, $module_name, $module_data, $global_config, $module_config, $per_page_comment;
    $comment_array = array();
    list($numf) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1"));
    $all_page = ($numf) ? $numf : 1;
    $per_page = $per_page_comment;
    $sql = "SELECT `content`, `post_time`, `post_name`, `post_email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`= '" . $id . "' AND `status`=1 ORDER BY `id` ASC LIMIT " . $page . "," . $per_page . "";
    $comment = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($comment))
    {
        $row['post_email'] = ($module_config[$module_name]['emailcomm']) ? $row['post_email'] : "";
        $comment_array[] = array("content" => $row['content'], "post_time" => $row['post_time'], "post_name" => $row['post_name'], "post_email" => $row['post_email']);
    }
    $db->sql_freeresult($comment);
    unset($row, $comment);
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;id=" . $id . "&checkss=" . md5($id . session_id() . $global_config['sitekey']);
    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'showcomment');
    return array("comment" => $comment_array, "page" => $generate_page);
}

function nv_del_content_module($id)
{
    global $db, $module_name, $module_data, $title, $db_config;
    $content_del = "NO_" . $id;
    $title = "";
    list($id, $listcatid, $title, $homeimgfile, $homeimgthumb) = $db->sql_fetchrow($db->sql_query("SELECT `id`, `listcatid`, `" . NV_LANG_DATA . "_title`, `homeimgfile`, `homeimgthumb` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . intval($id) . ""));
    if ($id > 0)
    {
        if ($homeimgfile != "" or $homeimgthumb != "")
        {
            $homeimgfile .= "|" . $homeimgthumb;
            $homeimgfile_arr = explode("|", $homeimgfile);
            foreach ($homeimgfile_arr as $homeimgfile_i)
            {
                if (!empty($homeimgfile_i) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i))
                {
                    @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i);
                }
            }
        }
        $number_no_del = 0;
        $array_catid = explode(",", $listcatid);
        if ($number_no_del == 0)
        {
            $query = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . $id;
            $db->sql_query($query);
            if (!$db->sql_affectedrows())
            {
                {
                    ++$number_no_del;
                }
                $db->sql_freeresult();
            }
        }
        if ($number_no_del == 0)
        {
            $db->sql_query("DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_comments` WHERE `id` = " . $id);
            $db->sql_query("DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` WHERE `id` = " . $id);
            $content_del = "OK_" . $id;
        }
        else
        {
            $content_del = "ERR_" . $lang_module['error_del_content'];
        }
    }
    return $content_del;
}

function nv_archive_content_module($id, $listcatid)
{
    global $db, $module_data;
    $array_catid = explode(",", $listcatid);
    foreach ($array_catid as $catid_i)
    {
        $catid_i = intval($catid_i);
        if ($catid_i > 0)
        {
            $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `archive`='2' WHERE `id`=" . $id . "");
        }
    }
    $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `archive`='2' WHERE `id`=" . $id . "");
}

function nv_link_edit_page($id)
{
    global $lang_global, $module_name;
    $link = "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>";
    return $link;
}

function nv_link_delete_page($id)
{
    global $lang_global, $module_name;
    $link = "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5($id . session_id()) . "','" . NV_BASE_ADMINURL . "')\">" . $lang_global['delete'] . "</a></span>";
    return $link;
}

function nv_file_table($table)
{
    global $db_config, $db;
    $lang_value = nv_list_lang();
    $arrfield = array();
    $result = $db->sql_query("SHOW COLUMNS FROM " . $table . "");
    while (list($field) = $db->sql_fetchrow($result))
    {
        $tmp = explode("_", $field);
        foreach ($lang_value as $lang_i)
        {
            if (!empty($tmp[0]) && !empty($tmp[1]))
            {
                if ($tmp[0] == $lang_i)
                {
                    $arrfield[] = array($tmp[0], $tmp[1]);
                    break;
                }
            }
        }
    }
    return $arrfield;
}

function nv_list_lang()
{
    global $db_config, $db;
    $re = $db->sql_query("SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE setup=1");
    $lang_value = array();
    while (list($lang_i) = $db->sql_fetchrow($re))
    {
        $lang_value[] = $lang_i;
    }
    return $lang_value;
}

/*******
 tru so luong trong kho $type = "-"
 cong so luong trong kho $type = "+"
 $listid : danh sach cac id product
 $listnum : danh sach so luong tuong ung
 ********/
function product_number_order($listid, $listnum, $type = "-")
{
    global $db_config, $db, $module_data;
    $arrayid = explode("|", $listid);
    $arraynum = explode("|", $listnum);
    $i = 0;
    foreach ($arrayid as $id)
    {
        if ($id > 0)
        {
            if (empty($arraynum[$i]))
                $arraynum[$i] = 0;
            $query = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET 
                      `product_number` = `product_number` " . $type . " " . intval($arraynum[$i]) . " WHERE `id` =" . $id . "";
            $db->sql_query($query);
        }
        ++$i;
    }
}

function nv_number_format($number)
{
    $temp = explode(".", $number);
    if (sizeof($temp) == 2)
    {
        $strlen = strlen($temp[1]);
        $strlen = ($number < 1000) ? (($strlen > 8) ? 8 : $strlen) : 2;
        return number_format($number, $strlen, '.', ' ');
    }
    else
    {
        return number_format($number, 0, '.', ' ');
    }
}
?>