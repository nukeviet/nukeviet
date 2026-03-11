<?php

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
