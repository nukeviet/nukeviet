<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
    $stmt = $db->prepare('SELECT click_url, exp_time, act, publ_time FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :id AND act IN (0, 1, 2)');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_banner = $stmt->fetch();
    $stmt->closeCursor();

    if (!empty($row_banner['click_url'])) {
        $exp_time = (int) $row_banner['exp_time'];
        $in_schedule = ($row_banner['act'] === 0 && (int) $row_banner['publ_time'] > NV_CURRENTTIME);
        $is_active = !$in_schedule && ($exp_time === 0 || $exp_time > NV_CURRENTTIME);

        // Cho phép redirect trong vòng 2 giờ sau khi hết hạn nhưng không đếm click
        $in_grace = (!$is_active && $exp_time >= NV_CURRENTTIME - 7200);

        if ($is_active || $in_grace) {
            $links = $row_banner['click_url'];

            if ($is_active) {
                $time_set = $nv_Request->get_int($module_name . '_clickid_' . $id, 'cookie', 0);
                if ($time_set == 0 and $nv_Request->get_string('s', 'get', 0) == md5($id . NV_CHECK_SESSION)) {
                    $nv_Request->set_Cookie($module_name . '_clickid_' . $id, 3600, NV_LIVE_COOKIE_TIME);

                    $br = ($client_info['is_mobile']) ? 'Mobile' : $client_info['browser']['key'];
                    $click_ref = '';
                    if (!empty($client_info['referer'])) {
                        $click_ref = nv_is_url($client_info['referer']) ? nv_substr($client_info['referer'], 0, 250) : '#';
                    }

                    $stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET hits_total = hits_total + 1 WHERE id = :id');
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $stmt = $db->prepare('INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_click (
                        bid, click_time, click_day, click_ip, click_country, click_browse_key, click_browse_name, click_os_key, click_os_name, click_ref
                    ) VALUES (
                        :bid, :click_time, 0, :click_ip, :click_country, :click_browse_key, :click_browse_name, :click_os_key, :click_os_name, :click_ref
                    )');
                    $stmt->bindValue(':bid', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':click_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->bindValue(':click_ip', $client_info['ip'], PDO::PARAM_STR);
                    $stmt->bindValue(':click_country', $client_info['country'], PDO::PARAM_STR);
                    $stmt->bindValue(':click_browse_key', '', PDO::PARAM_STR);
                    $stmt->bindValue(':click_browse_name', $br, PDO::PARAM_STR);
                    $stmt->bindValue(':click_os_key', '', PDO::PARAM_STR);
                    $stmt->bindValue(':click_os_name', $client_info['client_os']['name'], PDO::PARAM_STR);
                    $stmt->bindValue(':click_ref', $click_ref, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    }
}

include NV_ROOTDIR . '/includes/header.php';

echo '<script' . (defined('NV_SCRIPT_NONCE') ? ' nonce="' . NV_SCRIPT_NONCE . '"' : '') . '>';
echo '		window.location.href="' . $links . '";';
echo '</script>';
echo '<noscript>';
echo '		<meta http-equiv="refresh" content="0;url=' . $links . '" />';
echo '</noscript>';
include NV_ROOTDIR . '/includes/footer.php';
