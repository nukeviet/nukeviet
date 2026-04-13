<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Shared\SchemaHelper;
use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentService;

$page_title = $nv_Lang->getModule('config');

$socialbuttons = ['facebook', 'twitter', 'zalo'];

$contentRepo = new ContentRepository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $service = new ContentService($contentRepo);
    $array_config = $service->collectConfigData($nv_Request);
    $array_config = $service->prepareConfigData($array_config, $global_config, $socialbuttons);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config', '', $admin_info['userid']);

    $contentRepo->saveConfig($array_config);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

// Load config hiện tại từ DB
$array_config = [
    'viewtype' => 0,
    'facebookapi' => '',
    'socialbutton' => '',
    'per_page' => 20,
    'related_articles' => 5,
    'news_first' => 0,
    'copy_page' => 0,
    'alias_lower' => 1,
    'schema_type' => 'newsarticle',
    'schema_about' => 'organization'
];

if (!isset($service)) {
    $service = new ContentService($contentRepo);
}
$saved_config = $contentRepo->getConfig();
$array_config = array_merge($array_config, $saved_config);
$array_config = $service->formatConfigForView($array_config);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->registerPlugin('modifier', 'ucfirst', 'ucfirst');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $array_config);
$tpl->assign('SOCIAL_BUTTONS', $socialbuttons);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('SCHEMA_TYPES', SchemaHelper::$schema_types);
$tpl->assign('SCHEMA_ABOUTS', SchemaHelper::$schema_abouts);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
