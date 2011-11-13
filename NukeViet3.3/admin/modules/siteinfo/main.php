<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:5
 */

if (!defined('NV_IS_FILE_SITEINFO'))
    die('Stop!!!');

$page_title = $lang_global['mod_siteinfo'];

/**
 * nv_get_lang_module()
 *
 * @param mixed $mod
 * @return
 */
function nv_get_lang_module($mod)
{
    global $site_mods;
    $lang_module = array();
    if (isset($site_mods[$mod]))
    {
        if (file_exists(NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php"))
        {
            include (NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_INTERFACE . ".php");
        }
        elseif (file_exists(NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php"))
        {
            include (NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_" . NV_LANG_DATA . ".php");
        }
        elseif (file_exists(NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php"))
        {
            include (NV_ROOTDIR . "/modules/" . $site_mods[$mod]['module_file'] . "/language/admin_en.php");
        }
    }
    return $lang_module;
}

//Noi dung chinh cua trang
$info = array();

foreach ($site_mods as $mod => $value)
{
    if (file_exists(NV_ROOTDIR . "/modules/" . $value['module_file'] . "/siteinfo.php"))
    {
        $siteinfo = array();
        $mod_data = $value['module_data'];
        include (NV_ROOTDIR . "/modules/" . $value['module_file'] . "/siteinfo.php");
        if (!empty($siteinfo))
        {
            $info[$mod]['caption'] = $value['custom_title'];
            $info[$mod]['field'] = $siteinfo;
        }
    }
}

if (!empty($info))
{
    $xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAPTION', $lang_module['moduleInfo']);
    $a = 0;
    foreach ($info as $if)
    {
        foreach ($if['field'] as $field)
        {
            $xtpl->assign('CLASS', ($a % 2) ? " class=\"second\"" : "");
            $xtpl->assign('KEY', $field['key']);
            $xtpl->assign('VALUE', $field['value']);
            $xtpl->assign('MODULE', $if['caption']);
            $xtpl->parse('main.main1.loop');
            ++$a;
        }
    }
    $xtpl->parse('main.main1');

    //Thong tin phien ban NukeViet
    if (defined('NV_IS_GODADMIN'))
    {
        $field = array();
        $field[] = array('key' => $lang_module['version_user'], 'value' => $global_config['version'] . '.r' . $global_config['revision']);
        if (file_exists(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml'))
        {
            $new_version = simplexml_load_file(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
        }
        else
        {
            $new_version = array();
        }
        $info = "";
        if (!empty($new_version))
        {
            $field[] = array(//
                'key' => $lang_module['version_news'], //
                'value' => sprintf($lang_module['newVersion_detail'], //
                ( string )$new_version->version, //
                nv_date("d-m-Y H:i", strtotime($new_version->date))));

            if (nv_version_compare($global_config['version'], $new_version->version) < 0)
            {
                $info = sprintf($lang_module['newVersion_info'], NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=webtools&amp;" . NV_OP_VARIABLE . "=checkupdate");
            }
        }

        $xtpl->assign('CAPTION', $lang_module['version']);
        $xtpl->assign('ULINK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=webtools&amp;" . NV_OP_VARIABLE . "=checkupdate");
        $xtpl->assign('CHECKVERSION', $lang_module['checkversion']);

        foreach ($field as $key => $value)
        {
            $xtpl->assign('CLASS', ($key % 2) ? " class=\"second\"" : "");
            $xtpl->assign('KEY', $value['key']);
            $xtpl->assign('VALUE', $value['value']);
            $xtpl->parse('main.main2.loop');
        }

        if (!empty($info))
        {
            $xtpl->assign('INFO', $info);
            $xtpl->parse('main.main2.inf');
        }

        $xtpl->parse('main.main2');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}
elseif (!defined('NV_IS_SPADMIN') and !empty($site_mods))
{
    $arr_mod = array_keys($site_mods);
    $module_name = $arr_mod[0];
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name);
    die();
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>