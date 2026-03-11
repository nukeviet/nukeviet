<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Assign toàn bộ mảng — truy cập trong .tpl bằng {LANG.key}
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Trong .tpl:
// {LANG.hello}
// {GLANG.save}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->assign('LANG', $lang_module);
$tpl->assign('GLANG', $lang_global);

// Trong .tpl Smarty:
// {$LANG.hello}
// {$GLANG.save}
