<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES. All rights reserved
 * @Createdate Dec 22, 2011 10:22:41 AM
 */

if (!defined('NV_IS_MOD_TAGS'))
    die('Stop!!!');

function nv_tags_view($array_item, $htmlpage)
{
    global $global_config, $module_info, $module_name, $module_file, $topictitle, $topicalias, $module_config;
    $xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);

    foreach ($array_item as $item)
    {
        $xtpl->assign('CONTENT', $item);
        if (!empty($item['image']))
        {
            $xtpl->parse('main.loop.image');
        }
        $xtpl->parse('main.loop');
    }
    if (!empty($htmlpage))
    {
        $xtpl->assign('PAGES', $htmlpage);
        $xtpl->parse('main.pages');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
?>