<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');
$page_title = $lang_module['money'];

$currencies_array = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/currencies.ini', true);

if (!empty($pro_config['money_unit']) != "" and isset($currencies_array[$pro_config['money_unit']]))
{
    $page_title .= "  " . $lang_module['money_compare'] . "  " . $currencies_array[$pro_config['money_unit']]['currency'];
}

$error = "";
$savecat = 0;
$data = array();
$table_name = $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "";
$savecat = $nv_Request->get_int('savecat', 'post', 0);
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
if (!empty($savecat))
{
    $data['code'] = filter_text_input('code', 'post');
    $data['currency'] = filter_text_input('currency', 'post', '', 1);
    $data['exchange'] = $nv_Request->get_string('exchange', 'post,get', 0);
    $data['exchange'] = preg_replace('/([^0-9\.])/', '', $data['exchange']);
    if (isset($currencies_array[$data['code']]) and preg_match("/^([0-9]+)(\.[0-9]+)?$/", $data['exchange']))
    {
        $numeric = intval($currencies_array[$data['code']]['numeric']);
        if (!empty($pro_config['money_unit']) and $pro_config['money_unit'] == $data['code'])
        {
            $data['exchange'] = 1;
        }
        $data['currency'] = ( empty($data['currency'])) ? $currencies_array[$data['code']]['currency'] : $data['currency'];
        $query = "REPLACE INTO `" . $table_name . "` (`id`, `code`, `currency`, `exchange`) VALUES (" . $numeric . ", " . $db->dbescape_string($data['code']) . ", " . $db->dbescape_string($data['currency']) . ", " . $db->dbescape_string($data['exchange']) . ")";
        $db->sql_query($query);
        if ($db->sql_affectedrows() > 0)
        {
            $error = $lang_module['saveok'];
            $db->sql_freeresult();
            nv_del_moduleCache($module_name);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "");
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
        $db->sql_freeresult();
    }
}
elseif (!empty($data['id']))
{
    $data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `" . $table_name . "` WHERE id=" . $data['id']));
    $data['caption'] = $lang_module['money_edit'];
}
else
{
    $data['code'] = "";
    $data['currency'] = "";
    $data['exchange'] = 0;
    $data['caption'] = $lang_module['money_add'];
}

$xtpl = new XTemplate("money.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$count = 0;
$array_code_exit = array();
$re = $db->sql_query("SELECT `id`, `code`, `currency`, `exchange` FROM `" . $table_name . "` ORDER BY code DESC");
while ($row = $db->sql_fetchrow($re))
{
    $array_code_exit[] = $row['code'];
    $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $row['id'];
    $row['link_del'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delmoney&id=" . $row['id'];
    $temp = explode(".", $row['exchange']);
    if (sizeof($temp) == 2)
    {
        $row['exchange'] = number_format($row['exchange'], strlen($temp[1]), '.', ' ');
    }
    else
    {
        $row['exchange'] = number_format($row['exchange'], 0, '.', ' ');
    }
    $row['class'] = ($count % 2 == 0) ? ' class="second"' : '';

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.data.row');
    ++$count;
}
$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delmoney");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
if ($count > 0)
{
    $xtpl->parse('main.data');
}

$numeric = 0;
ksort($currencies_array);
foreach ($currencies_array as $code => $value)
{
    if (!in_array($code, $array_code_exit) or $code == $data['code'])
    {
        $array_temp = array();
        $array_temp['value'] = $code;
        $array_temp['title'] = $code . " - " . $value['currency'];
        $array_temp['selected'] = ($code == $data['code']) ? " selected=\"selected\"" : "";
        $xtpl->assign('DATAMONEY', $array_temp);
        $xtpl->parse('main.money');
    }
}
$temp = explode(".", $data['exchange']);
if (sizeof($temp) == 2)
{
    $data['exchange'] = number_format($data['exchange'], strlen($temp[1]), '.', ' ');
}
else
{
    $data['exchange'] = number_format($data['exchange'], 0, '.', ' ');
}

$xtpl->assign('DATA', $data);
$xtpl->parse('main');
$contents .= $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>