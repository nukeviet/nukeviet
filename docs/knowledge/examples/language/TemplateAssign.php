<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Xtemplate (đã lỗi thời, chỉ mang tính tham khảo)
$xtpl = new XTemplate('file.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

// Trong file.tpl:
// {LANG.hello}
// {GLANG.save}

// Smarty (được khuyến nghị sử dụng)
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->assign('LANG', $nv_Lang);

// Trong .tpl Smarty:
// {$LANG->getModule('hello')}
// {$LANG->getGlobal('save')}
