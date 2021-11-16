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
    $submenu['oa_info'] = $lang_module['oa_info'];
    $submenu['followers'] = $lang_module['followers'];
    $submenu['article'] = $lang_module['article'];
    $submenu['chatbot'] = $lang_module['chatbot'];
    $submenu['tags'] = $lang_module['tags'];
    $submenu['templates'] = $lang_module['templates'];
    $submenu['upload'] = $lang_module['upload'];
    $submenu['video'] = $lang_module['video'];
    $submenu['settings'] = $lang_module['settings'];
} elseif (defined('NV_IS_SPADMIN') and $global_config['idsite']) {
}
