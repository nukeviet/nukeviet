<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['config'] = $nv_Lang->getModule('config');
$submenu['setuplayout'] = $nv_Lang->getModule('setup_layout');
$submenu['blocks'] = $nv_Lang->getModule('blocks');
$submenu['xcopyblock'] = $nv_Lang->getModule('xcopyblock');

$allow_func = array( 'main', 'deletetheme', 'setuplayout', 'activatetheme', 'change_layout', 'config', 'blocks', 'block_content', 'block_config', 'block_outgroup', 'loadblocks', 'blocks_change_pos', 'blocks_change_order', 'blocks_change_order_group', 'blocks_change_active', 'block_change_show', 'blocks_del', 'blocks_del_group', 'blocks_func', 'blocks_reset_order', 'sort_order', 'xcopyblock', 'loadposition', 'xcopyprocess' );

if (defined('NV_IS_GODADMIN')) {
    $submenu['package_theme_module'] = $nv_Lang->getModule('package_theme_module');
    $allow_func[] = 'package_theme_module';
    $allow_func[] = 'getfile';
}
