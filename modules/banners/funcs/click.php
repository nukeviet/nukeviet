<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

$nv_BotManager->setPrivate();

$links = NV_MY_DOMAIN;
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    $click_url = $db->query('SELECT click_url FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id . ' AND act=1')->fetchColumn();
    if (!empty($click_url)) {
        $links = $click_url;
        $time_set = $nv_Request->get_int($module_name . '_clickid_' . $id, 'cookie', 0);
        if ($time_set == 0 and $nv_Request->get_string('s', 'get', 0) == md5($id . NV_CHECK_SESSION)) {
            $nv_Request->set_Cookie($module_name . '_clickid_' . $id, 3600, NV_LIVE_COOKIE_TIME);

            $browser = ($client_info['is_mobile']) ? 'Mobile' : $client_info['browser']['key'];

            $db->query('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET hits_total=hits_total+1 WHERE id=' . $id);
            $sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_click (
                bid, click_time, click_day, click_ip, click_country, click_browse_key, click_browse_name, click_os_key, click_os_name, click_ref
            ) VALUES (
                ' . $id . ', ' . NV_CURRENTTIME . ', 0, ' . $db->quote($client_info['ip']) . ',
                ' . $db->quote($client_info['country']) . ", '', " . $db->quote($browser) . ", '',
                " . $db->quote($client_info['client_os']['name']) . ',
                ' . $db->quote(nv_substr($client_info['referer'], 0, 250)) . '
            );';
            $db->query($sql);
        }
    }
}

include NV_ROOTDIR . '/includes/header.php';

echo '<script type="text/javascript">';
echo '		window.location.href="' . $links . '";';
echo '</script>';
echo '<noscript>';
echo '		<meta http-equiv="refresh" content="0;url=' . $links . '" />';
echo '</noscript>';
include NV_ROOTDIR . '/includes/footer.php';
