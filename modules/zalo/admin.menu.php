<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['oa_info'] = $nv_Lang->getModule('oa_info');
    $submenu['followers'] = $nv_Lang->getModule('followers');
    $submenu['article'] = $nv_Lang->getModule('article');
    $submenu['chatbot'] = $nv_Lang->getModule('chatbot');
    $submenu['tags'] = $nv_Lang->getModule('tags');
    $submenu['templates'] = $nv_Lang->getModule('templates');
    $submenu['upload'] = $nv_Lang->getModule('upload');
    $submenu['video'] = $nv_Lang->getModule('video');
    $submenu['settings'] = $nv_Lang->getModule('settings');
} elseif (defined('NV_IS_SPADMIN') and $global_config['idsite']) {
}
